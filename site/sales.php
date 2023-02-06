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
            $sql = $pdo->prepare("SELECT * FROM `sales` WHERE `branch_sales` = '$branch' ");
            $sql->execute();
            $result = $sql->fetchAll();
        ?>
        <table class="table shadow ">
		<thead class="thead-dark">
			<tr>
				<th>Тип топлива</th>
				<th>Объем</th>
				<th>Дата</th>

			</tr>
				<?php foreach ($result as $value) {  //Перебор массива, задаваемый с помощью $result.На каждой итерации значение текущего элемента присваивается переменной $value. 
                    
                    ?> 
			<tr>
				<td><?=$value['fuel_sales'] ?></td>
				<td><?=$value['volume_sales'] ?> л</td>
				<td><?=$value['date_sales'] ?></td>
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
        $branch = $_SESSION["branch_workers"];
        require "blocks/connect.php"; //Подключение к БД
        $sql = $pdo->prepare("SELECT * FROM `sales`");
        $sql->execute();
        $result = $sql->fetchAll();
    ?>
    <table class="table shadow ">
	<thead class="thead-dark">
		<tr>
            <th>№ филиала</th>
			<th>Тип топлива</th>
			<th>Объем</th>
			<th>Дата</th>

		</tr>
			<?php foreach ($result as $value) {  //Перебор массива, задаваемый с помощью $result.На каждой итерации значение текущего элемента присваивается переменной $value. 
                
                ?> 
		<tr>
            <td><?=$value['branch_sales'] ?></td>
			<td><?=$value['fuel_sales'] ?></td>
			<td><?=$value['volume_sales'] ?> л</td>
			<td><?=$value['date_sales'] ?></td>
		</tr> <?php } ?>
        
	</thead>
	</table>
	<form action="index.php">
        <button class="btn btn-primary btn-lg " ><h2>Назад</h2></button>
    </form><br>  
    <?php endif; ?> 
    </div>

    <!--  Popper and Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>
