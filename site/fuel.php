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

    <title>Топливо</title>
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

    
    <!-- Страница для администратора -->
    <?php if ($_SESSION['position_workers']== "Администратор" ): ?>
	<center>
	<?php 
	require "blocks/connect.php"; //Подключение к БД
    $sql = $pdo->prepare("SELECT * FROM `fuel` ");
    $sql->execute();
    $result = $sql->fetchAll();
    ?>
	<h1>Список типов топлива 
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddModal"><img src="assets/plus-lg.svg" width="15" height="15"></button></h1>
     <table class="table shadow ">
	<thead class="thead-dark">
		<tr>
            
			<th>Тип</th>
			<th>Отпускная цена</th>
			<th>Поставщик</th>
			<th>Закупочная цена</th>
			<th>Наценка</th>
			<th>Действие</th>
		</tr>
        <?php foreach ($result as $value) {  //Перебор массива, задаваемый с помощью $result.На каждой итерации значение текущего элемента присваивается переменной $value. 
                 
                 ?> 
		<tr>
            
			<td><?=$value['type_fuel'] ?></td>
			<td><?=$value['price_fuel'] ?> руб/л</td>
			<td><?=$value['supplier_fuel'] ?> </td>
			<td><?=$value['price_fuel_suppl'] ?> руб/л</td>
			<td><?=$value['margin_fuel'] ?> руб</td>
    		<td>
			<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#UpdModal<?=$value['id_fuel'] ?>"><img src="assets/pen.svg" width="15" height="15"></button>
			<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#DelModal<?=$value['id_fuel'] ?>"><img src="assets/trash3.svg" width="15" height="15"></button>								
		</td>

		

			<!-- Modal Обновить-->
			<div class="modal fade" id="UpdModal<?=$value['id_fuel'] ?>" tabindex="-1" aria-labelledby="UpdModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="UpdModalLabel">Редактировать <?=$value['type_fuel'] ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_fuel']?>" method="post">
                <div class="form-group">
					<input type="text" class="form-control" name="type" id="type" value="<?=$value['type_fuel'] ?>" placeholder="Введите тип" required> <br>
                    <input type="text" class="form-control" name="price_fuel_suppl" id="price_fuel_suppl" value="<?=$value['price_fuel_suppl'] ?>" placeholder="Введите закупочную цену" required> <br>
                    <input type="text" class="form-control" name="margin_fuel" id="margin_fuel" value="<?=$value['margin_fuel'] ?>" placeholder="Введите наценку (через точку)" required> <br>
                    <h>Поставщик:</h><br>
                        <?php 
                            $sql = "SELECT * FROM `supplier`";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $result2 = $query->fetchAll(); 
                        foreach ($result2 as $value2) { ?>
                    <input type="radio" name="supplier_fuel" id="<?=$value2['name_supplier'] ?>" value="<?=$value2['name_supplier'] ?>" />
                    <label for="<?=$value2['name_supplier'] ?>"><?=$value2['name_supplier'] ?></label>
                    <?php 
                }?>
				</div>
				<div class="modal-footer">
					<button type="submit" name="UpdSubmit" class="btn btn-primary">Обновить</button>
				</div>
				</form>	
				<?php 
                $id = $_GET['id'];
				$type = filter_var(trim($_POST ['type']),FILTER_SANITIZE_STRING) ;              //В переменную помещаятся отфильрованное значение
				$supplier_fuel = filter_var(trim($_POST ['supplier_fuel']),FILTER_SANITIZE_STRING) ;
				$price_fuel_suppl = filter_var(trim($_POST ['price_fuel_suppl']),FILTER_SANITIZE_STRING) ;
				$margin_fuel = filter_var(trim($_POST ['margin_fuel']),FILTER_SANITIZE_STRING) ;
					if (isset($_POST['UpdSubmit'])) {
					$sql = "UPDATE `fuel` SET `type_fuel`=?, `supplier_fuel`=?, `price_fuel_suppl`=?, `margin_fuel`=?  WHERE `id_fuel` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($type, $supplier_fuel, $price_fuel_suppl, $margin_fuel, $id)); //Внесение данных в БД
					$sql = "UPDATE `fuel` SET `price_fuel`= `margin_fuel`+`price_fuel_suppl`"; 
					$query = $pdo->prepare($sql);
					$query->execute(); //Внесение данных в БД
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:fuel.php');        
									} ?>

			</div>
			</div>
			</div>
			</div>

			<!-- Modal Удалить-->
			<div class="modal fade" id="DelModal<?=$value['id_fuel'] ?>" tabindex="-1" aria-labelledby="DelWorkerModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="DelWorkerModalLabel">Удалить <?=$value['type_fuel'] ?> </h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="?id=<?=$value['id_fuel']?>" method="post">
				<h5>Вы действительно хотите удалить? </h5> 
				<div class="modal-footer">
					<button type="submit"  name="DelSubmit" class="btn btn-danger">Да</button>
					<button type="button" class="btn btn-success" data-bs-dismiss="modal">Нет</button>
				</div>
				</form>	
				<?php 
				$id = $_GET['id'];
				if (isset($_POST['DelSubmit'])) {
					$sql = "DELETE FROM `fuel` WHERE `id_fuel` = ?"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($id)); //Внесение данных в БД
					
										$sql = null;
										$query = null;
										$pdo = null;
									header('location:fuel.php');    
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
				<h5 class="modal-title" id="AddModalLabel">Добавть топливо </h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="type" id="type"  placeholder="Введите тип" required> <br>
                    <input type="text" class="form-control" name="price_fuel_suppl" id="price_fuel_suppl"  placeholder="Введите закупочную цену" required> <br>
                    <input type="text" class="form-control" name="margin_fuel" id="margin_fuel"  placeholder="Введите наценку (через точку)" required> <br>
                    <h>Поставщик:</h><br>
                        <?php 
                            $sql = "SELECT * FROM `supplier`";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $result2 = $query->fetchAll(); 
                        foreach ($result2 as $value2) { ?>
                    <input type="radio" name="supplier_fuel" id="<?=$value2['name_supplier'] ?>" value="<?=$value2['name_supplier'] ?>" />
                    <label for="<?=$value2['name_supplier'] ?>"><?=$value2['name_supplier'] ?></label>
                    <?php 
                }?>
				</div>
				<div class="modal-footer">
					<button type="submit" name="AddSubmit" class="btn btn-primary">Добавить</button>
				</div>
				</form>	
				<?php 
				$type = filter_var(trim($_POST ['type']),FILTER_SANITIZE_STRING) ; //В переменную помещаятся отфильрованное значение
				$supplier_fuel = filter_var(trim($_POST ['supplier_fuel']),FILTER_SANITIZE_STRING) ;
				$price_fuel_suppl = filter_var(trim($_POST ['price_fuel_suppl']),FILTER_SANITIZE_STRING) ;
				$margin_fuel = filter_var(trim($_POST ['margin_fuel']),FILTER_SANITIZE_STRING) ;
					if (isset($_POST['AddSubmit'])) {
					$sql = "INSERT INTO `fuel` (`type_fuel`, `supplier_fuel`, `price_fuel_suppl`, `margin_fuel`) VALUES (?,?,?,?)"; 
					$query = $pdo->prepare($sql);
					$query->execute(array($type, $supplier_fuel, $price_fuel_suppl, $margin_fuel,)); //Внесение данных в БД
                    $sql2 = "UPDATE `fuel` SET `price_fuel`= `margin_fuel`+`price_fuel_suppl`"; 
					$query = $pdo->prepare($sql2);
					$query->execute(); //Внесение данных в БД
										$sql = null;
                                        $sql2 = null;
										$query = null;
										$pdo = null;
									header('location:fuel.php');     
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













