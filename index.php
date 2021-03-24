<html>
<head>
 
</head>
<h1>Таблица</h1>
<link rel="stylesheet" type="text/css" href="css/style.css">
<br>
<body>

<?php
session_start();
$host = 'localhost';  // Хост, у нас все локально
$user = 'root';    // Имя созданного вами пользователя
$pass = ''; // Установленный вами пароль пользователю
$db_name = 'bdpolz';   // Имя базы данных
$link = mysqli_connect($host, $user, $pass, $db_name); // Соединяемся с базой
mysqli_set_charset($link, 'utf8');
// Ругаемся, если соединение установить не удалось
if (!$link) 
{
    echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
    exit;
}

if (!isset($_SESSION['polz']))
{
	echo "Войдите в систему";
	echo "<form method='POST'>";
	echo  "Логин <input name='login' type='text' required><br>";
	echo  "Пароль <input name='password' type='password' required><br>";
	echo  "<input name='submit' type='submit' value='Войти'>";
	echo "</form>";
}
else
{
	if ($_SESSION['adm'] == "1")
	{
		if (isset($_GET['delit'])) 
		{ 
			//удаляем строку из таблицы
			$sql = mysqli_query($link, "DELETE FROM `human` WHERE `id` = {$_GET['delit']}");
				if ($sql) 
				{
				echo "<p>Пользователь удален.</p>";
				unset($_GET['delit']);
				
				} 
				else 
				{
				echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
				}
		}
		$tabl = mysqli_query($link, 'SELECT `ID`, `login`, `password`, `name`, `fam`, `gen`, `old` FROM `human`');
		echo "<table>";
		echo  "<tr><th>Логин</th><th>Пароль</th><th>Имя</th><th>Фамили</th><th>Пол</th><th>Возраст</th>
		<th>Действие</th></tr>";
		while ($result = mysqli_fetch_array($tabl)) 
		{
		echo "<tr>
				<td> {$result['login']} </td>
				<td>{$result['password']}</td>
				<td>{$result['name']}</td>
				<td>{$result['fam']}</td>
				<td>{$result['gen']}</td>
				<td>{$result['old']}</td>
				<td><a href='?delit={$result['ID']}'>Удалить</a></td>
			  </tr>";
		}
		echo "Вы вошли как администратор: ",$_SESSION['polz'], "<br>";
		echo "<form method='POST'>";
		echo  "<input name='submit2' type='submit' value='Выйти'>";
		echo "</form>";
		
		
	}
	else
	{
		$tabl = mysqli_query($link, 'SELECT `ID`, `login`, `name`, `fam`, `gen`, `old` FROM `human`');
		echo "<table>";
		echo  "<tr><th>Логин</th><th>Имя</th><th>Фамили</th><th>Пол</th><th>Возраст</th></tr>";
		while ($result = mysqli_fetch_array($tabl)) 
		{
		echo "<tr>
				<td> {$result['login']} </td> 
				<td>{$result['name']}</td>
				<td>{$result['fam']}</td>
				<td>{$result['gen']}</td>
				<td>{$result['old']}</td>
			  </tr>";
		}
		echo "Вы вошли как пользователь: ",$_SESSION['polz'], "<br>";
		echo "<form method='POST'>";
		echo "<input name='submit2' type='submit' value='Выйти'>";
		echo "</form>";

	}
}
if (isset($_POST['submit'])) {
	$query = mysqli_query($link, "SELECT id, password FROM human WHERE login='" . mysqli_real_escape_string($link, $_POST['login']) . "' LIMIT 1");
	$data = mysqli_fetch_assoc($query);
if ($_POST['password'] == $data['password']){
	$_SESSION['polz'] = $_POST['login'];
	$_SESSION['adm'] = $data['id'];
	header("Refresh:0");
}
else
{ 
	unset($_SESSION['polz']);
	echo "Неверно";
}
}
if (isset($_POST['submit2'])) 
{
	unset($_SESSION['polz']);
	header("Refresh:0");
}
?>

</body>
</html>