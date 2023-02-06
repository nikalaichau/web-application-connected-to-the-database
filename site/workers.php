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

    <title>Сотрудники</title>
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
    $sql = $pdo->prepare("SELECT * FROM `workers` WHERE `branch_workers` = '$branch' ");
    $sql->execute();
    $result = $sql->fetchAll();
    ?>
    <table class="table shadow ">
		<thead class="thead-dark">
			<tr>
			<th>Имя</th>
			<th>Фамилия</th>
			<th>Отчество</th>
			<th>Адресс</th>
			<th>Телефон</th>
			<th>День рождения</th>
			<th>Должность</th>
			<th>Стаж(лет)</th>
			</tr>
			<?php foreach ($result as $value) { ?>
			<tr>
			<td><?=$value['name_workers'] ?></td>
			<td><?=$value['surname_workers'] ?></td>
			<td><?=$value['middlename_workers'] ?></td>
			<td><?=$value['address_workers'] ?></td>
			<td><?=$value['phone_workers'] ?></td>
			<td><?=$value['birthday_workers'] ?></td>
			<td><?=$value['position_workers'] ?></td>
			<td><?=$value['exp_workers'] ?></td>

					
								
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
    $sql = $pdo->prepare("SELECT * FROM `workers` ");
    $sql->execute();
    $result = $sql->fetchAll();
    ?>
	<h1>Список сотрудников   <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddModal"><img src="assets/plus-lg.svg" width="15" height="15"></button></h1>
	
    <table class="table shadow ">
		<thead class="thead-dark">
			<tr>
			<th>№ филиала</th>
			<th>Имя</th>
			<th>Фамилия</th>
			<th>Отчество</th>
			<th>Адресс</th>
			<th>Телефон</th>
			<th>День рождения</th>
			<th>Должность</th>
			<th>Стаж (лет)</th>
			<th>Пароль</th>
			<th>Действие</th>
			</tr>
			<?php foreach ($result as $value) { ?>
			<tr>
			<td><?=$value['branch_workers'] ?></td>
			<td><?=$value['name_workers'] ?></td>
			<td><?=$value['surname_workers'] ?></td>
			<td><?=$value['middlename_workers'] ?></td>
			<td><?=$value['address_workers'] ?></td>
			<td><?=$value['phone_workers'] ?></td>
			<td><?=$value['birthday_workers'] ?></td>
			<td><?=$value['position_workers'] ?></td>
			<td><?=$value['exp_workers'] ?></td>
			<td><?=$value['pass_workers'] ?></td>
			<td>
			<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#UpdWorkerModal<?=$value['id_workers'] ?>"><img src="assets/pen.svg" width="15" height="15"></button>
			<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#DelWorkerModal<?=$value['id_workers'] ?>"><img src="assets/trash3.svg" width="15" height="15"></button>								
		</td>

		<!-- Modal Добавить-->
		<div class="modal fade" id="AddModal" tabindex="-1" aria-labelledby="AddModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="AddModalLabel">Добавть сотрудника </h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="name" id="name"  placeholder="Введите имя" required> <br>
                    <input type="text" class="form-control" name="surname" id="surname"  placeholder="Введите фамилию" required> <br>
                    <input type="text" class="form-control" name="middlename" id="middlename"  placeholder="Введите отчество" required> <br>
					<input type="text" class="form-control" name="address" id="address"  placeholder="Введите адресс" required> <br>
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Введите номер телефона" required> <br>
                    <h5>Дата рождения</h5>
                    <input type="date" class="form-control" name="birthdate" id="birthdate" placeholder="Введите дату рождения"><br>
					<br><h5>Должность:</h5>
                        <?php 
                            $sql = "SELECT * FROM `position`";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $result2 = $query->fetchAll(); 
                        foreach ($result2 as $value2) { ?>
                    <input type="radio" name="position" id="<?=$value2['name_position'] ?>" value="<?=$value2['name_position'] ?>" />
                    <label for="<?=$value2['name_position'] ?>"><?=$value2['name_position'] ?></label>
                    <?php 
                }?>
					<br><br><h5>№ филиала:</h5>
                        <?php 
                            $sql = "SELECT * FROM `branch`";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $result2 = $query->fetchAll(); 
                        foreach ($result2 as $value2) { ?>
                    <input type="radio" name="branch" id="<?=$value2['id_branch'] ?>" value="<?=$value2['id_branch'] ?>" />
                    <label for="<?=$value2['id_branch'] ?>"><?=$value2['id_branch'] ?></label>
                    <?php 
                }?>
					<input type="text" class="form-control" name="exp" id="exp" placeholder="Введите стаж" required> <br>
					<input type="text" class="form-control" name="password" id="password" placeholder="Введите пароль" required> <br>
                    
				</div>
				<div class="modal-footer">
					<button type="submit" name="AddSubmit" class="btn btn-primary">Добавить</button>
				</div>
				</form>	
				<?php 
				$name = filter_var(trim($_POST ['name']),FILTER_SANITIZE_STRING) ;              //В переменную помещаятся отфильрованное значение
				$surname = filter_var(trim($_POST ['surname']),FILTER_SANITIZE_STRING) ;
				$middlename = filter_var(trim($_POST ['middlename']),FILTER_SANITIZE_STRING) ;
				$address = filter_var(trim($_POST ['address']),FILTER_SANITIZE_STRING) ;
				$phone = filter_var(trim($_POST ['phone']),FILTER_SANITIZE_STRING) ;
				$birthdate = filter_var(trim($_POST ['birthdate']),FILTER_SANITIZE_STRING) ;
				$position = filter_var(trim($_POST ['position']),FILTER_SANITIZE_STRING) ;
				$branch = filter_var(trim($_POST ['branch']),FILTER_SANITIZE_STRING) ;
				$exp = filter_var(trim($_POST ['exp']),FILTER_SANITIZE_STRING) ;
				$password = filter_var(trim($_POST ['password']),FILTER_SANITIZE_STRING) ;
					if (isset($_POST['AddSubmit'])) {
					$sql = "INSERT INTO `workers` (`name_workers`, `surname_workers`, `middlename_workers`, `address_workers`, `phone_workers`, `birthday_workers`, `branch_workers`, `position_workers`, `exp_workers`, `pass_workers`) VALUES (?,?,?,?,?,?,?,?,?,?)"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($name, $surname, $middlename, $address, $phone, $birthdate, $branch, $position, $exp, $password)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:workers.php');     
									} ?>
			</div>
			</div>
			</div>
			</div>

			<!-- Modal Обновить-->
			<div class="modal fade" id="UpdWorkerModal<?=$value['id_workers'] ?>" tabindex="-1" aria-labelledby="UpdWorkerModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="UpdWorkerModalLabel">Редактировать данные <?=$value['name_workers'] ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_workers']?>" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="name" id="name" value="<?=$value['name_workers'] ?>" placeholder="Введите имя" required> <br>
                    <input type="text" class="form-control" name="surname" id="surname" value="<?=$value['surname_workers'] ?>" placeholder="Введите фамилию" required> <br>
                    <input type="text" class="form-control" name="middlename" id="middlename" value="<?=$value['middlename_workers'] ?>" placeholder="Введите отчество" required> <br>
					<input type="text" class="form-control" name="address" id="address" value="<?=$value['address_workers'] ?>" placeholder="Введите адресс" required> <br>
                    <input type="text" class="form-control" name="phone" id="phone" value="<?=$value['phone_workers'] ?>" placeholder="Введите номер телефона" required> <br>
                    <h>Дата рождения</h>
                    <input type="date" class="form-control" name="birthdate" id="birthdate" value="<?=$value['birthday_workers'] ?>" placeholder="Введите дату рождения"><br>
					<br><h5>Должность:</h5>
                        <?php 
                            $sql = "SELECT * FROM `position`";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $result2 = $query->fetchAll(); 
                        foreach ($result2 as $value2) { ?>
                    <input type="radio" name="position" id="<?=$value2['name_position'] ?>" value="<?=$value2['name_position'] ?>" />
                    <label for="<?=$value2['name_position'] ?>"><?=$value2['name_position'] ?></label>
                    <?php 
                }?>
					<br><br><h5>№ филиала:</h5>
                        <?php 
                            $sql = "SELECT * FROM `branch`";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $result2 = $query->fetchAll(); 
                        foreach ($result2 as $value2) { ?>
                    <input type="radio" name="branch" id="<?=$value2['id_branch'] ?>" value="<?=$value2['id_branch'] ?>" />
                    <label for="<?=$value2['id_branch'] ?>"><?=$value2['id_branch'] ?></label>
                    <?php 
                }?>
					<br><h>Стаж:</h><br>
					<input type="text" class="form-control" name="exp" id="exp" value="<?=$value['exp_workers'] ?>" placeholder="Введите стаж" required> <br>
					<input type="text" class="form-control" name="password" id="password" value="<?=$value['pass_workers'] ?>" placeholder="Введите пароль" required> <br>
                    
				</div>
				<div class="modal-footer">
					<button type="submit" name="UpdSubmit" class="btn btn-primary">Обновить</button>
				</div>
				</form>	
				<?php 
				$id = $_GET['id'];
				$name = filter_var(trim($_POST ['name']),FILTER_SANITIZE_STRING) ;              //В переменную помещаятся отфильрованное значение
				$surname = filter_var(trim($_POST ['surname']),FILTER_SANITIZE_STRING) ;
				$middlename = filter_var(trim($_POST ['middlename']),FILTER_SANITIZE_STRING) ;
				$address = filter_var(trim($_POST ['address']),FILTER_SANITIZE_STRING) ;
				$phone = filter_var(trim($_POST ['phone']),FILTER_SANITIZE_STRING) ;
				$birthdate = filter_var(trim($_POST ['birthdate']),FILTER_SANITIZE_STRING) ;
				$position = filter_var(trim($_POST ['position']),FILTER_SANITIZE_STRING) ;
				$branch = filter_var(trim($_POST ['branch']),FILTER_SANITIZE_STRING) ;
				$exp = filter_var(trim($_POST ['exp']),FILTER_SANITIZE_STRING) ;
				$password = filter_var(trim($_POST ['password']),FILTER_SANITIZE_STRING) ;
					if (isset($_POST['UpdSubmit'])) {
					$sql = "UPDATE `workers` SET `name_workers`=?, `surname_workers`=?, `middlename_workers`=?, `address_workers`=?, `phone_workers`=?, `birthday_workers`=?, `position_workers`=?, `branch_workers`=?, `exp_workers`=?, `pass_workers`=?  WHERE `id_workers` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($name, $surname, $middlename, $address, $phone, $birthdate, $position, $branch, $exp, $password, $id)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:workers.php');     
									} ?>
			</div>
			</div>
			</div>
			</div>

			<!-- Modal Удалить-->
			<div class="modal fade" id="DelWorkerModal<?=$value['id_workers'] ?>" tabindex="-1" aria-labelledby="DelWorkerModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="DelWorkerModalLabel">Удалить уработника? <?=$value['name_workers'] ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_workers']?>" method="post">
				<h5>Вы действительно хотите удалить работника? </h5> 
				<div class="modal-footer">
					<button type="submit"  name="DelSubmit" class="btn btn-danger">Да</button>
					<button type="button" class="btn btn-success" data-bs-dismiss="modal">Нет</button>
				</div>
				</form>	
				<?php 
				$id = $_GET['id'];
				if (isset($_POST['DelSubmit'])) {
					$sql = "DELETE FROM `workers` WHERE `id_workers` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($id)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:workers.php');     
									} ?>
			</div>
			</div>
			</div>
			</div>
							
			</tr> <?php } ?>
		</thead>
	</table>

	
	<form action="PDO/update_workers.php">
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
