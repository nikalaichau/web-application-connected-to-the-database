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

    <title>Остаток</title>
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

    <!-- Страница для управляющего-->
    <?php if ($_SESSION['position_workers']== "Управляющий" ):?>
    <center>
	<?php 
	$branch = $_SESSION["branch_workers"];
	require "blocks/connect.php"; //Подключение к БД
    $sql = $pdo->prepare("SELECT * FROM `leftover` WHERE `branch_leftover` = '$branch' ");
    $sql->execute();
    $result = $sql->fetchAll();
    ?>
    <table class="table shadow ">
		<thead class="thead-dark">
			<tr>
			<th>Тип топлива</th>
			<th>Остаток</th>
			<th>Действие</th>
			</tr>
			<?php foreach ($result as $value) { ?>
			<tr>
			<td><?=$value['fuel_leftover'] ?></td>
			<td><?=$value['quantity_leftover'] ?> л</td>
			<td>
			<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#UpdSalesModal<?=$value['id_leftover'] ?>"><img src="assets/pen.svg" width="15" height="15"></button>
									
		</td>
			<!-- Modal Edit-->
			<div class="modal fade" id="UpdSalesModal<?=$value['id_leftover'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Редактировать остаток<?=$value['fuel_leftover'] ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_leftover']?>" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="upd_value" value="<?=$value['quantity_leftover'] ?>" placeholder="Остаток">
				</div>
				<div class="modal-footer">
					<button type="submit" name="UpdSubmit" class="btn btn-primary">Обновить</button>
				</div>
				</form>	
				<?php 
				$id = $_GET['id'];
				$upd_value = filter_var(trim($_POST ['upd_value']),FILTER_SANITIZE_STRING) ;
					if (isset($_POST['UpdSubmit'])) {
					$sql = "UPDATE `leftover` SET `quantity_leftover` = ?  WHERE `id_leftover` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($upd_value, $id)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:leftover.php');     
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
    
    <!-- Страница для администратора -->
    <?php elseif ($_SESSION['position_workers']== "Администратор" ): ?>
		<center>
	<?php 
	
	require "blocks/connect.php"; //Подключение к БД
    $sql = $pdo->prepare("SELECT * FROM `leftover`");
    $sql->execute();
    $result = $sql->fetchAll();
    ?>
    <table class="table shadow ">
		<thead class="thead-dark">
			<tr>
			<th>№ филиала</th>
			<th>Тип топлива</th>
			<th>Остаток</th>
			<th>Действие</th>
			</tr>
			<?php foreach ($result as $value) { ?>
			<tr>
			<td><?=$value['branch_leftover'] ?></td>
			<td><?=$value['fuel_leftover'] ?></td>
			<td><?=$value['quantity_leftover'] ?> л</td>
			<td>
			<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#UpdSalesModal<?=$value['id_leftover'] ?>"><img src="assets/pen.svg" width="15" height="15"></button>
									
		</td>
			<!-- Modal Edit-->
			<div class="modal fade" id="UpdSalesModal<?=$value['id_leftover'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Редактировать остаток <?=$value['fuel_leftover'] ?> в филиале <?=$value['branch_leftover'] ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_leftover']?>" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="upd_value" value="<?=$value['quantity_leftover'] ?>" placeholder="Остаток">
				</div>
				<div class="modal-footer">
					<button type="submit" name="UpdSubmit" class="btn btn-primary">Обновить</button>
				</div>
				</form>	
				<?php 
				$id = $_GET['id'];
				$upd_value = filter_var(trim($_POST ['upd_value']),FILTER_SANITIZE_STRING) ;
					if (isset($_POST['UpdSubmit'])) {
					$sql = "UPDATE `leftover` SET `quantity_leftover` = ?  WHERE `id_leftover` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($upd_value, $id)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:leftover.php');     
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
