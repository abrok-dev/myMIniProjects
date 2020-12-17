<?php
$mySQL = new PDO("mysql:host=localhost;dbname=train" , "admin" , '662743');
$temp=0;
$count = $mySQL->query("SELECT COUNT(*) FROM guestbook");
$pages = $count->fetchColumn();

$pages = ceil($pages/5);
var_dump($pages);
if((!isset($_GET['page']))||($_GET['page']<1))
$page=1;
else
$page = $_GET['page'];
?>


<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
	</head>
	<body>
		<div id="wrapper">
			<?php
if(isset($_POST['name'])){
	$temp=1;
	$sql = $mySQL->prepare("INSERT INTO guestbook (name,date,feedback) VALUES (? , ? , ?)");
	$name = $_POST['name'];
	$date = date("Y-m-d H:i:s");
	$text = $_POST['text'];
	$sql->execute(array($name , $date , $text));
}
?>
			<h1>Гостевая книга</h1>
			<div>
				<nav>
				  <ul class="pagination">
					<?php
					
					
						if($page==1)
						echo "<li class=\"disabled\">";
						else 
						echo "<li>";
						echo "<a href=\"?page=". ($page-1) ."\"aria-label=\"Previous\">
						<span aria-hidden=\"true\">&laquo;</span>
					</a>";
						for($i=1;$i<=$pages;$i++){
							if ($i==$page){
								echo" <li class=\"active\"><a href=\"?page=$i\">$i</a></li>";
							}
							else  echo "<li><a href=\"?page=$i\">$i</a></li>";
						}
						if($page==$pages)
						echo"<li class=\"disabled\">
						<a href=\"?page=1\" aria-label=\"Next\">
							<span aria-hidden=\"true\">&raquo;</span>
						</a>
					</li>";
					else echo "<li> <a href=\"?page=".($page+1) . " \" aria-label=\"Next\">
					<span aria-hidden=\"true\">&raquo;</span>
				</a>
			</li>";
					
					?>

				  </ul>
				</nav>
				<?php
		
		


function image ($date , $name , $feedback){
 echo	"<div class=\"note\"><p> <span class=\"date\">$date </span><span class=\"name\">$name</span> </p> <p> $feedback</p></div>";
}
$offset = ($page-1)*5;
$select = $mySQL->query("SELECT * from guestbook ORDER BY date DESC LIMIT 5 OFFSET $offset ");
//$select->execute(array($offset));
while ($data = $select->fetch(PDO::FETCH_LAZY)){
	image($data['date'] , $data['name'] , $data['feedback']);

}
		?>
			</div>
			
			
			<?php
			if($temp){
			echo "<div class=\"info alert alert-info\">Запись успешно сохранена!</div>";
			}
			 ?>
			<div id="form">
				<form action="" method="POST">
					<p><input name="name"  class="form-control" placeholder="Ваше имя"></p>
					<p><textarea name="text" class="form-control" placeholder="Ваш отзыв"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>
	</body>
</html>

