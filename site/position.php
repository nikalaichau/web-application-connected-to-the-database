
<?php session_start(); ?>
<!doctype html>
<html lang="ru">
<head>
    <!-- Необходимые мета теги -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <title>Должности</title>
</head>
<body>
    <header style = "background-color:#89CFF0; height:50px;">
        <style>
            a {
                text-decoration: none;   /* Убираем подчёркивание */
                color: black;
            }
        </style>
        <center><a href="index.php"><h1>АЗС</h1></a></center> 
	</header>
    <div class="container mt-4">

    <!-- Страница для администратора-->
    <?php if ($_SESSION['position_workers']== "Администратор" ):?>
	<center>
	<?php 
	require "blocks/connect.php"; //Подключение к БД
    $sql = $pdo->prepare("SELECT * FROM `position`  ");
    $sql->execute();
    $result = $sql->fetchAll();
    ?>
	
	
	<h1>Список должностей <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddModal"><img src="assets/plus-lg.svg" width="15" height="15"></button> </h1>
    <table class="table shadow ">
		<thead class="thead-dark">
			<tr>
			<th>Должность</th>
			<th>Ставка</th>
			<th>Действие</th>
			</tr>
			<?php foreach ($result as $value) { ?>
			<tr>
			<td><?=$value['name_position'] ?></td>
			<td><?=$value['salary_position'] ?></td>
			<td>
			<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#UpdPosModal<?=$value['id_position'] ?>"><img src="assets/pen.svg" width="15" height="15"></button>
			<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#DelPosModal<?=$value['id_position'] ?>"><img src="assets/trash3.svg" width="15" height="15"></button>								
		</td>

	


			<!-- Modal Обновить-->
			<div class="modal fade" id="UpdPosModal<?=$value['id_position'] ?>" tabindex="-1" aria-labelledby="UpdPosModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="UpdPosModalLabel">Редактировать данные <?=$value['name_position'] ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_position']?>" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="name" id="name" value="<?=$value['name_position'] ?>" placeholder="Введите имя" required> <br>
                    <input type="text" class="form-control" name="salary" id="surname" value="<?=$value['salary_position'] ?>" placeholder="Введите фамилию" required> <br>
    
				</div>
				<div class="modal-footer">
					<button type="submit" name="UpdSubmit" class="btn btn-primary">Обновить</button>
				</div>
				</form>	
				<?php 
				$id = $_GET['id'];
				$name = filter_var(trim($_POST ['name']),FILTER_SANITIZE_STRING) ;              //В переменную помещаятся отфильрованное значение
				$salary = filter_var(trim($_POST ['salary']),FILTER_SANITIZE_STRING) ;
					if (isset($_POST['UpdSubmit'])) {
					$sql = "UPDATE `position` SET `name_position`=?, `salary_position`=? WHERE `id_position` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($name, $salary, $id)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:position.php');     
									} ?>
			</div>
			</div>
			</div>
			</div>

			<!-- Modal Удалить-->
			<div class="modal fade" id="DelPosModal<?=$value['id_position'] ?>" tabindex="-1" aria-labelledby="DelPosModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="DelPosModalLabel">Удалить должность? <?=$value['name_workers'] ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_position']?>" method="post">
				<h5>Вы действительно хотите удалить должность? </h5> 
				<div class="modal-footer">
					<button type="submit"  name="DelSubmit" class="btn btn-danger">Да</button>
					<button type="button" class="btn btn-success" data-bs-dismiss="modal">Нет</button>
				</div>
				</form>	
				<?php 
				$id = $_GET['id'];
				if (isset($_POST['DelSubmit'])) {
					$sql = "DELETE FROM `position` WHERE `id_position` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($id)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:position.php');     
									} ?>
			</div>
			</div>
			</div>
			</div>

			<!-- Modal Добавить-->
		<div class="modal fade" id="AddModal" tabindex="-1" aria-labelledby="AddModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="AddModalLabel">Добавть должность </h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="name" id="name"  placeholder="Введите название" required> <br>
                    <input type="text" class="form-control" name="salary" id="salary"  placeholder="Введите ставку" required> <br>
                                       
				<div class="modal-footer">
					<button type="submit" name="AddSubmit" class="btn btn-primary">Добавить</button>
				</div>
				</form>	
				<?php 
				$name = filter_var(trim($_POST ['name']),FILTER_SANITIZE_STRING) ;              //В переменную помещаятся отфильрованное значение
				$salary = filter_var(trim($_POST ['salary']),FILTER_SANITIZE_STRING) ;
				if (isset($_POST['AddSubmit'])) {
					$sql = "INSERT INTO `position` (`name_position`, `salary_position`) VALUES (?,?)"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($name, $salary)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:position.php');     
									} ?>
			</div>
			</div>
			</div>
			</div>				
			</tr> <?php } ?>
		</thead>
	</table>

	
	<form action="index.php">
        <button class="btn btn-primary btn-lg " ><h2>Назад</h2></button>
    </form><br>    
    </center>  
    <?php endif; ?> 
    </div>

    <!--  Popper and Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>





