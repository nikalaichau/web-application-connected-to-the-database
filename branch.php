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

    <title>Филиалы</title>
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
         $sql = $pdo->prepare("SELECT * FROM `branch`");
         $sql->execute();
         $result = $sql->fetchAll(); 
     ?>
	 <h1>Список филиалов <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddModal"><img src="assets/plus-lg.svg" width="15" height="15"></button></h1>
     <table class="table shadow ">
	<thead class="thead-dark">
		<tr>
			<th>№</th>
			<th>Адрес</th>
			<th>Телефон</th>
			<th>Количество сотрудников</th>
			<th>Количество ТРК</th>
			<th>Дата обслуживания</th>
            <th>Открыт</th>
			<th>Действие</th>
		</tr>
			<?php foreach ($result as $value) {  //Перебор массива, задаваемый с помощью $result.На каждой итерации значение текущего элемента присваивается переменной $value. 
                 if($value['open_branch'] == 1){ 
                     $open= "Да";
                 }else {$open= "Нет";}
                 ?> 
		<tr>
			<td><?=$value['id_branch'] ?></td>
			<td><?=$value['address_branch'] ?></td>
			<td><?=$value['phone_branch'] ?></td>
			<td><?=$value['quantity_workers_branch'] ?></td>
			<td><?=$value['quantity_trk_branch'] ?></td>
			<td><?=$value['schedule_branch'] ?></td>
             <td><?=$open ?> </td>
			 <td>
			<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#UpdBranchModal<?=$value['id_branch'] ?>"><img src="assets/pen.svg" width="15" height="15"></button>
			<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#DelBranchModal<?=$value['id_branch'] ?>"><img src="assets/trash3.svg" width="15" height="15"></button>								
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
					<input type="text" class="form-control" name="address" id="address"  placeholder="Введите адрес" required> <br>
                    <input type="text" class="form-control" name="phone" id="phone"  placeholder="Введите телефон" required> <br>
                    <input type="text" class="form-control" name="quantity_workers" id="quantity_workers"  placeholder="Введите количество сотрудников" required> <br>
					<input type="text" class="form-control" name="quantity_trk" id="quantity_trk"  placeholder="Введите количество ТРК" required> <br>
                    <h5>Дата проведения работ</h5>
                    <input type="date" class="form-control" name="schedule_branch" id="schedule_branch" placeholder="Введите дату проведения работ"><br>
				</div>
				<div class="modal-footer">
					<button type="submit" name="AddSubmit" class="btn btn-primary">Добавить</button>
				</div>
				</form>	
				<?php 
				$address = filter_var(trim($_POST ['address']),FILTER_SANITIZE_STRING) ; //В переменную помещаятся отфильрованное значение
				$phone = filter_var(trim($_POST ['phone']),FILTER_SANITIZE_STRING) ;
				$quantity_workers = filter_var(trim($_POST ['quantity_workers']),FILTER_SANITIZE_STRING) ;
				$quantity_trk = filter_var(trim($_POST ['quantity_trk']),FILTER_SANITIZE_STRING) ;
				$schedule_branch = filter_var(trim($_POST ['schedule_branch']),FILTER_SANITIZE_STRING) ;
					if (isset($_POST['AddSubmit'])) {
					$sql = "INSERT INTO `branch` (`address_branch`, `phone_branch`, `quantity_workers_branch`, `quantity_trk_branch`, `schedule_branch`) VALUES (?,?,?,?,?)"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($address, $phone, $quantity_workers, $quantity_trk, $schedule_branch)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:branch.php');     
									} ?>
			</div>
			</div>
			</div>
			</div>

			<!-- Modal Обновить-->
			<div class="modal fade" id="UpdBranchModal<?=$value['id_branch'] ?>" tabindex="-1" aria-labelledby="UpdBranchModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="UpdBranchModalLabel">Редактировать данные <?=$value['id_branch'] ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_branch']?>" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="address" id="address" value="<?=$value['address_branch'] ?>" placeholder="Введите адрес" required> <br>
                    <input type="text" class="form-control" name="phone" id="phone" value="<?=$value['phone_branch'] ?>" placeholder="Введите телефон" required> <br>
                    <input type="text" class="form-control" name="quantity_workers" id="quantity_workers" value="<?=$value['quantity_workers_branch'] ?>" placeholder="Введите количество сотрудников" required> <br>
					<input type="text" class="form-control" name="quantity_trk" id="quantity_trk" value="<?=$value['quantity_trk_branch'] ?>" placeholder="Введите количество ТРК" required> <br>
                    <h5>Дата проведения работ</h5>
                    <input type="date" class="form-control" name="schedule_branch" id="schedule_branch" value="<?=$value['schedule_branch'] ?>" placeholder="Введите дату проведения работ"><br>
				</div>
				<div class="modal-footer">
					<button type="submit" name="UpdSubmit" class="btn btn-primary">Обновить</button>
				</div>
				</form>	
				<?php 
				$id = $_GET['id'];
				$address = filter_var(trim($_POST ['address']),FILTER_SANITIZE_STRING) ; //В переменную помещаятся отфильрованное значение
				$phone = filter_var(trim($_POST ['phone']),FILTER_SANITIZE_STRING) ;
				$quantity_workers = filter_var(trim($_POST ['quantity_workers']),FILTER_SANITIZE_STRING) ;
				$quantity_trk = filter_var(trim($_POST ['quantity_trk']),FILTER_SANITIZE_STRING) ;
				$schedule_branch = filter_var(trim($_POST ['schedule_branch']),FILTER_SANITIZE_STRING) ;
					if (isset($_POST['UpdSubmit'])) {
						$sql = "UPDATE `branch` SET `address_branch`=?, `phone_branch`=?, `quantity_workers_branch`=?, `quantity_trk_branch`=?, `schedule_branch`=?  WHERE `id_branch` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($address, $phone, $quantity_workers, $quantity_trk, $schedule_branch, $id)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:branch.php');     
									} ?>
			</div>
			</div>
			</div>
			</div>

			<!-- Modal Удалить-->
			<div class="modal fade" id="DelBranchModal<?=$value['id_branch'] ?>" tabindex="-1" aria-labelledby="DelBranchModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="DelBranchModalLabel">Удалить филиал №<?=$value['id_branch'] ?> ?</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_branch']?>" method="post">
				<h5>Вы действительно хотите удалить филиал? </h5> 
				<div class="modal-footer">
					<button type="submit"  name="DelSubmit" class="btn btn-danger">Да</button>
					<button type="button" class="btn btn-success" data-bs-dismiss="modal">Нет</button>
				</div>
				</form>	
				<?php 
				$id = $_GET['id'];
				if (isset($_POST['DelSubmit'])) {
					$sql = "DELETE FROM `branch` WHERE `id_branch` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($id)); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:branch.php');     
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
