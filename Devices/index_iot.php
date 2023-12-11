<?php

//--------------------------Настройки подключения к БД-----------------------
include "connection_db.php";

session_start();
if(!isset($_SESSION["LOGIN"])) {
    header("location: login.php");
}

$user_id = $_SESSION["USER_ID"];

//----------------------------------------------------------------------------------------
//$id = 1;

//-----------------Получаем из БД все данные об устройствах конкретного пользователя-------------------

$query = "SELECT * FROM DEVICE_TABLE D 
JOIN TEMPERATURE_TABLE T ON D.DEVICE_ID=T.DEVICE_ID 
JOIN OUT_STATE_TABLE S ON D.DEVICE_ID=S.DEVICE_ID
JOIN user_device UD ON D.DEVICE_ID=UD.DEVICE_ID
WHERE UD.USER_ID = ?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$title = "Все устройства";
$content = "";
if(mysqli_num_rows($result) > 0) { //Если в БД есть данные об устройстве

while($device = mysqli_fetch_assoc($result)){
$id = $device['DEVICE_ID'];
$device_name = $device['NAME'];
$temperature = $device['TEMPERATURE']/10;
$temperature_dt = $device['DATE_TIME'];
$out_state = $device['OUT_STATE'];
$out_state_dt = $device['DATE_TIME'];    

$content .= "
<div>
<table>
<tr>
<td width=215px> Устройство
</td>
<td width=410px>".$device_name."
</td>
</tr>
</table>

<table border=1>
<tr>
<td width=215px> Tемпература
</td>
<td width=100px>".$temperature."
</td>
<td width=310px>".$temperature_dt."
</td>
</tr>
<tr>
<td width=215pxpx> Реле
</td>
<td width=100px>".$out_state."
</td>
<td width=310px> ".$out_state_dt."
</td>
</tr>
</table>
</div>";

$query = "SELECT * FROM USER_BLOCK WHERE USER_ID = ? AND DEVICE_ID = ? AND END_TIME > NOW()";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'ii', $user_id, $id);
mysqli_stmt_execute($stmt);
$result_query = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result_query) == 0){//Если уже есть блокировка на пользователя по данному устройству
    $content .=
"<div class='group_buttons'>
<div class='on'>
<form>
<button formmethod=POST name=\"button_on\" value=".$id.">Включить реле</button>
</form>
</div>
<div class='off'>
<form>
<button formmethod=POST name=\"button_off\" value=".$id.">Выключить реле</button>
</form>
</div>
<div class='off'>
<form action='history.php'>
<button formmethod=POST name=\"button_history\" value=".$id.">История управления</button>
</form>
</div>
</div>
";
}
else {
    $content .= "<p>Ваш доступ к этому устройству временно заблокирован.</p>";
}
}
} 
else { //Если в БД нет данных об устройстве
// echo "В базе данных нет устройств.";
// exit;
$content .= "<p>В базе данных нет устройств.</p>";
}

// ----------------------------------------------------------------------------------------

// ------Проверяем данные, полученные от пользователя---------------------
$id = 1;

if(isset($_POST['button_on'])){
    $id = $_POST['button_on'];
$date_today = date("Y-m-d H:i:s");
$query = "UPDATE COMMAND_TABLE SET COMMAND='1', DATE_TIME='$date_today' WHERE DEVICE_ID = ?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_affected_rows($mysql) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
{ //вставляем в таблицу строчку с данными о команде для устройства
$query = "INSERT COMMAND_TABLE SET DEVICE_ID=?, COMMAND='1', DATE_TIME='$date_today'";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
}
$query = "INSERT USER_DEVICE_STATUS SET USER_ID=?, DEVICE_ID =?, COMMAND='Реле включено', DATE_TIME='$date_today'";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'ii', $user_id, $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
}

if(isset($_POST['button_off'])){
    $id = $_POST['button_off'];

$date_today = date("Y-m-d H:i:s");
$query = "UPDATE COMMAND_TABLE SET COMMAND='0', DATE_TIME='$date_today' WHERE DEVICE_ID = ?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if(mysqli_affected_rows($mysql) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
{ //вставляем в таблицу строчку с данными о команде для устройства
$query = "INSERT COMMAND_TABLE SET DEVICE_ID=?, COMMAND='0', DATE_TIME='$date_today'";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
}
$query = "INSERT USER_DEVICE_STATUS SET USER_ID=?, DEVICE_ID = ?, COMMAND='Реле выключено', DATE_TIME='$date_today'";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'ii', $user_id, $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
}

if(isset($_POST['button_history'])){
    $id = $_POST['button_history'];
    header("Location: history.php");
    exit();
}
// -----------------------------------------------------------------------

//-------Формируем интерфейс приложения для браузера---------------------
echo '
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Device management</title>
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    </head>

    <body>

        <header>
            <nav>
                <ul>
                    <li><a href="index_iot.php">Device management</a></li>
                    <li><a href="login.php">Authorization</a></li>
                    <li><a href="register.php">Registration</a></li>
                </ul>
            </nav>
        </header>

        <div class="main_content">
        <h1>'.$title.'</h1>
        '.$content.'
        </div>

        <footer>
            Formed on '.date('d.m.Y').'
        </footer> 
    </body>

</html>';
//----------------------------------------------------------------------

?>
