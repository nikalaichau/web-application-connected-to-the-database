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

    <title>АЗС</title>
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

    <!-- Общая главная страница -->
    
    <?php if ($_SESSION['id_clients']==0 and $_SESSION['position_workers']==0):?>
    <center>
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#LogUserModal"><h2>Вход для клиентов</h2></button>
        <br><br>
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#ListBranchModal"><h2>Список АЗС</h2></button>
        <br><br>
        <button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#LogWorkerModal"><h2>Вход для сотрудников</h2></button>
        <br><br>
    </center>

    <!-- Главная страница для авторизованных пользователей -->
    <?php elseif ($_SESSION['id_clients']>=1): ?>
    <center>       
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#AccountModal"><h2>Личный кабинет</h2></button>
        <br><br>
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#ListBranchModal"><h2>Список АЗС</h2></button>
        <br><br>
        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#LogOutModal"><h2>Выход</h2></button>
        <br><br>
    </center>  
    
    <!-- Главная страница для управляющего -->
    <?php elseif ($_SESSION['position_workers']== "Управляющий" ): ?>
    <center>
        
        <!-- Статус работы филиала -->
        <?php 
        $branch = $_SESSION["branch_workers"];
         require "blocks/connect.php"; //Подключение к БД
        $sql = $pdo->prepare("SELECT * FROM `branch` WHERE `id_branch` = '$branch' ");
        $sql->execute();
        $result = $sql->fetchAll();
        foreach ($result as $value) {  //Перебор массива, задаваемый с помощью $result.На каждой итерации значение текущего элемента присваивается переменной $value. 
            $current_date = date('Y-m-d');
            if($value['open_branch'] == 1){ 
                $open= "работает";
            }else {$open= "не работает";}

            if($value['schedule_branch'] != $current_date ){ 
                $schedule_branch= "1";
            }else {$schedule_branch= "0";}
            echo "<h1>Филиал №$branch сейчас $open </h1>";
            ?> 
        <form action="" method="post">
        <button type="submit" name="Open" class="btn btn-success btn-lg"><h2>Открыт</h2></button>
        <button type="submit" name="Close" class="btn btn-danger btn-lg"><h2>Закрыть</h2></button>
        </form>
        <?php

    //Изменение статуса работы филиала 
		    if (isset($_POST['Open'])) {
            
            $work = $schedule_branch;
		    $sql = "UPDATE `branch` SET `open_branch` = ?  WHERE `id_branch` = ?"; 
		    $query = $pdo->prepare($sql);
		    $query->execute(array($work , $branch)); //Внесение данных в БД
		    					$sql = null;
		    					$query = null;
		    					$pdo = null;
		    				header('location:index.php');     
		    				}
		    if (isset($_POST['Close'])) {
            $work = 0;
		    $sql = "UPDATE `branch` SET `open_branch` = ?  WHERE `id_branch` = ?"; 
		    $query = $pdo->prepare($sql);
		    $query->execute(array($work, $branch)); //Внесение данных в БД
		    					$sql = null;
		    					$query = null;
		    					$pdo = null;
		    				header('location:index.php');     
		    				} ?>                                                           
        <?php } ?>

        <!-- Дата проведения работ -->
        <?php 

        $branch = $_SESSION["branch_workers"];

        
        $sql = $pdo->prepare("SELECT `schedule_branch` FROM `branch` WHERE `id_branch` = '$branch' ");
        $sql->execute();
        $result = $sql->fetchAll();
        foreach ($result as $value) {  //Перебор массива, задаваемый с помощью $result.На каждой итерации значение текущего элемента присваивается переменной $value.  
            
            ?> 
        <form action="" method="post">
       <br> <h1>Дата проведения работ: </h1>   
        <input type="date" class="form-control" name="schedule_branch" id="schedule_branch" value="<?=$value['schedule_branch'] ?>" placeholder="Введите дату"  required> <br>
        </h1> 
        <button type="submit" name="Upd" class="btn btn-success btn-lg"><h2>Обновить</h2></button>
        </form>
        <?php 
    //Изменение даты проведения работ 
            if (isset($_POST['Upd'])) {
                
            $schedule_branch = filter_var(trim($_POST ['schedule_branch']),FILTER_SANITIZE_STRING) ;
		    $sql = "UPDATE `branch` SET `schedule_branch` = ?  WHERE `id_branch` = ?"; 
		    $query = $pdo->prepare($sql);
		    $query->execute(array($schedule_branch , $branch)); //Внесение данных в БД
		    					$sql = null;
		    					$query = null;
		    					$pdo = null;
		    				header('location:index.php');     
		    				}
		    ?>                                                           
        <?php } ?>
        <br>
        <form action="sales.php">
        <button class="btn btn-primary btn-lg " ><h2>Продажи филиала</h2></button>
        </form><br>  
        <form action="leftover.php">
        <button class="btn btn-primary btn-lg " ><h2>Остаток топлива филиала</h2></button>
        </form><br> 
        <form action="workers.php">
        <button class="btn btn-primary btn-lg " ><h2>Сотрудники филиала</h2></button>
        </form><br>          
        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#LogOutModal"><h2>Выход</h2></button>
        <br><br>
    </center>

    <!-- Главная страница для касира -->
    <?php elseif ($_SESSION['position_workers']== "Кассир" ): ?>
    <center> 
        <?php 
        $branch = $_SESSION["branch_workers"];
        echo "<h1>Филиал №$branch</h1>"; 
        ?>
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#AddUserModal"><h2>Добавить пользователя</h2></button>
        <br><br>
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#AddSaleModal"><h2>Добавить операцию</h2></button>
        <br><br>
        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#LogOutModal"><h2>Выход</h2></button>
        <br><br>

    </center>

    <!-- Главная страница для администратора -->
    <?php elseif ($_SESSION['position_workers']== "Администратор" ): ?>
    <center> 
        <form action="sales.php">
        <button class="btn btn-primary btn-lg " ><h2>Продажи</h2></button>
        </form><br> 
        <form action="leftover.php">
        <button class="btn btn-primary btn-lg " ><h2>Остаток топлива</h2></button>
        </form><br> 
        <form action="workers.php">
        <button class="btn btn-primary btn-lg " ><h2>Сотрудники</h2></button>
        </form><br>
        <form action="position.php">
        <button class="btn btn-primary btn-lg " ><h2>Должности</h2></button>
        </form><br>
        <form action="branch.php">
        <button class="btn btn-primary btn-lg " ><h2>Филиалы</h2></button>
        </form><br> 
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#ListClientsModal"><h2>Клиенты</h2></button>
        <br><br>
        <form action="fuel.php">
        <button class="btn btn-primary btn-lg " ><h2>Топливо</h2></button>
        </form><br>  
    <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#LogOutModal"><h2>Выход</h2></button>
        <br><br>
    </center>        
    <?php endif; ?> 
    </div>


    <!-- Modal -->
    <!-- Modal Вход пользователя -->
    <div class="modal fade" id="LogUserModal" tabindex="-1" aria-labelledby="LogUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="LogUsereModalLabel">Вход для клиентов</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <div class="row">
            <div class="col">
                <h5>Форма регистрации</h5>
                <form action="PDO/check_clients.php" method="post">
                    
                    <input type="text" class="form-control" name="name" id="name" placeholder="Введите имя" required> <br>
                    <input type="text" class="form-control" name="surname" id="surname" placeholder="Введите фамилию" required> <br>
                    <input type="text" class="form-control" name="middlename" id="middlename" placeholder="Введите отчество" required> <br>
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Введите номер телефона" required> <br>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль" required> <br>
                    <input type="text" class="form-control" name="address" id="address" placeholder="Введите адресс" required> <br>
                    <h5>Дата рождения</h5>
                    <input type="date" class="form-control" name="birthdate" id="birthdate" placeholder="Введите дату рождения"><br>
                    
                    <h5>Тип топлива:</h5><br>
                    <input type="radio" name="fuel" id="AI95" value="АИ95" required/>
                    <label for="AI95">АИ95</label>
                    <input type="radio" name="fuel" id="AI98" value="АИ98" required/>
                    <label for="AI98">АИ98</label>
                    <input type="radio" name="fuel" id="Diesel" value="Дизель" required/>
                    <label for="AI98">ДТ</label> 
                    <input type="radio" name="fuel" id="Gas" value="Газ" />
                    <label for="AI98">Газ</label> <br>
                    <button class="btn btn-success" type="submit" value="submit">Регистрирация</button>
                 </form>
            </div>
            <div class="col">
                <h5>Форма авторизации</h5>
                <form action="PDO/auth_clients.php" method="post">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Введите номер телефона"> <br>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль"> <br>
                    <button class="btn btn-success" type="submit">Авторизация</button>
                </form>
            </div>
        </div>
        </div>
        </div>
        </div>
    </div>

    <!-- Modal Список АЗС -->
    <div class="modal fade" id="ListBranchModal" tabindex="-1" aria-labelledby="ListBranchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="ListBranchModalLabel">Список АЗС</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
                <?php 
                    require "blocks/connect.php"; //Подключение к БД
                    $sql = $pdo->prepare("SELECT * FROM `branch`");
                    $sql->execute();
                    $result = $sql->fetchAll();
                ?>
                <table class="table shadow ">
				<thead class="thead-dark">
					<tr>
						<th>№</th>
						<th>Адрес</th>
						<th>Телефон</th>
                        <th>Открыт</th>

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
                        <td><?=$open ?> </td>
					</tr> <?php } ?>
                    
				</thead>
			</table>
        </div>
        </div>
        </div>
    </div>

    <!-- Modal Список клиентов -->
    <div class="modal fade" id="ListClientsModal" tabindex="-1" aria-labelledby="ListClientsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="ListClientsModalLabel">Список клиентов</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
                <?php 
                    require "blocks/connect.php"; //Подключение к БД
                    $sql = $pdo->prepare("SELECT * FROM `clients`");
                    $sql->execute();
                    $result = $sql->fetchAll();
                ?>
                <table class="table shadow ">
				<thead class="thead-dark">
					<tr>
						<th>№</th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Отчество</th>
						<th>Телефон</th>
                        <th>Адресс</th>
                        <th>День рождения</th>
                        <th>Тип топлива</th>

					</tr>
						<?php foreach ($result as $value) {  //Перебор массива, задаваемый с помощью $result.На каждой итерации значение текущего элемента присваивается переменной $value. 
                            
                            ?> 
					<tr>
						<td><?=$value['id_clients'] ?></td>
                        <td><?=$value['name_clients'] ?></td>
                        <td><?=$value['surname_clients'] ?></td>
                        <td><?=$value['middlename_clients'] ?></td>
                        <td><?=$value['phone_clients'] ?></td>
                        <td><?=$value['address_clients'] ?></td>
                        <td><?=$value['birthdate_clients'] ?></td>
                        <td><?=$value['fuel_client'] ?></td>
					</tr> <?php } ?>
                    
				</thead>
			</table>
        </div>
        </div>
        </div>
    </div>

    <!-- Modal Вход сотрудника -->
    <div class="modal fade" id="LogWorkerModal" tabindex="-1" aria-labelledby="LogWorkerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="LogWorkerModalLabel">Введите данные</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form action="PDO/auth_workers.php" method="post">
                <input type="text" class="form-control" name="id" id="id" placeholder="Введите id"> <br>
                <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль"> <br>
                <button class="btn btn-success" type="submit">Авторизация</button>
            </form>
        </div>
        </div>
        </div>
    </div>

    <!-- Modal Добавить пользователя -->
    <div class="modal fade" id="AddUserModal" tabindex="-1" aria-labelledby="AddUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="AddUsereModalLabel">Введите данные</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form action="PDO/check_clients.php" method="post">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Введите имя" required> <br>
                    <input type="text" class="form-control" name="surname" id="surname" placeholder="Введите фамилию" required> <br>
                    <input type="text" class="form-control" name="middlename" id="middlename" placeholder="Введите отчество" required> <br>
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Введите номер телефона" required> <br>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль" required> <br>
                    <input type="text" class="form-control" name="address" id="address" placeholder="Введите адресс" required> <br>
                    <h5>Дата рождения</h5>
                    <input type="date" class="form-control" name="birthdate" id="birthdate" placeholder="Введите дату рождения"><br>
                    <h5>Тип топлива:</h5><br>
                    <input type="radio" name="fuel" id="AI95" value="АИ95" required/>
                    <label for="AI95">АИ95</label>
                    <input type="radio" name="fuel" id="AI98" value="АИ98" required/>
                    <label for="AI98">АИ98</label>
                    <input type="radio" name="fuel" id="Diesel" value="Дизель" required/>
                    <label for="AI98">ДТ</label> 
                    <input type="radio" name="fuel" id="Gas" value="Газ" required/>
                    <label for="AI98">Газ</label> <br>
                    <button class="btn btn-success" type="submit" value="submit">Регистрирация</button>
                 </form>
        </div>
        </div>
        </div>
    </div>


    <!-- Modal Личный кабинет -->
    <div class="modal fade" id="AccountModal" tabindex="-1" aria-labelledby="AccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="AccountModalLabel">Введите данные</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <?php 
            require "blocks/connect.php"; //Подключение к БД
            $id = $_SESSION["id_clients"];
            $sql = $pdo->prepare("SELECT * FROM `clients` WHERE `id_clients` = '$id' "); //Формирование SQL запроса 
            $sql->execute(); //Выполнение SQL запроса
            $result = $sql->fetchAll();
            foreach ($result as $value); /*Перебор массива, задаваемый с помощью $result. 
            На каждой итерации значение текущего элемента присваивается переменной $value.*/
        ?>
        <form action="PDO/update_clients.php" method="post">
            <h5>Изменить имя:</h5>
            <input type="text" class="form-control" name="name" id="name" placeholder="Введите имя" value="<?php echo $value['name_clients']; ?>" required> <br>
            <h5>Изменить фамилию:</h5>
            <input type="text" class="form-control" name="surname" id="surname" placeholder="Введите фамилию" value="<?php echo $value['surname_clients']; ?>" required> <br>
            <h5>Изменить отчество:</h5>
            <input type="text" class="form-control" name="middlename" id="middlename" placeholder="Введите отчество" value="<?php echo $value['middlename_clients']; ?>" required> <br>
            <h5>Изменить номер телефона:</h5>
            <input type="text" class="form-control" name="phone" id="phone" placeholder="Введите номер телефона" value="<?php echo $value['phone_clients']; ?>" required> <br>
            <h5>Новый пароль:</h5>
            <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль" required> <br>
            <h5>Изменить адресс:</h5>
            <input type="text" class="form-control" name="address" id="address" placeholder="Введите адресс" value="<?php echo $value['address_clients']; ?>" required> <br>
            <h5>Изменить дату рождения</h5>
            <input type="date" class="form-control" name="birthdate" id="birthdate" placeholder="Введите дату рождения" value="<?php echo $value['birthdate_clients']; ?>"> <br>
            <h5>Изменить тип топлива:</h5><br>
            <input type="radio" name="fuel" id="AI95" value="АИ95" required/>
            <label for="AI95">АИ95</label>
            <input type="radio" name="fuel" id="AI98" value="АИ98" required/>
            <label for="AI98">АИ98</label>
            <input type="radio" name="fuel" id="Diesel" value="Дизель" required/>
            <label for="AI98">ДТ</label> 
            <input type="radio" name="fuel" id="Gas" value="Газ" required/>
            <label for="AI98">Газ</label> <br>
            <br><button class="btn btn-success" type="submit" value="submit">Обновить данные</button>
            </form>   
        </div>
        </div>
        </div>
    </div>

    <!-- Modal Добавить операцию -->
    <div class="modal fade" id="AddSaleModal" tabindex="-1" aria-labelledby="AddSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="AddSaleModalLabel">Введите данные</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form action="PDO/add_sales.php" method="post">
                    <input type="text" class="form-control" name="volume" id="volume" placeholder="Введите объем" required> <br>
                    <h5>Тип топлива:</h5><br>
                    <input type="radio" name="fuel" id="AI95" value="АИ95" required/>
                    <label for="AI95">АИ95</label>
                    <input type="radio" name="fuel" id="AI98" value="АИ98" required/>
                    <label for="AI98">АИ98</label>
                    <input type="radio" name="fuel" id="Diesel" value="Дизель" required/>
                    <label for="AI98">ДТ</label> 
                    <input type="radio" name="fuel" id="Gas" value="Газ" required/>
                    <label for="AI98">Газ</label> <br>
                    <button class="btn btn-success" type="submit" value="submit">Отправить</button>
                 </form>
        </div>
        </div>
        </div>
    </div>

    <!-- Modal Выход -->
    <div class="modal fade" id="LogOutModal" tabindex="-1" aria-labelledby="LogOutModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="LogOutModalLabel">Выход</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <h5>Вы действительно хотите выйти?</h5>
        <form action="blocks/exit.php" method="post">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Нет</button>
                <button class="btn btn-danger" type="submit">Да</button>
            </form>
        </div>
        </div>
        </div>
    </div>
    


    
    <!--  Popper and Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>



