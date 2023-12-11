<?php
//добавить хеширование, механизм соли
//Настройки подключения к БД
include "connection_db.php";

if(isset($_GET["ID"])){ //Если запрос от устройства содержит идентификатор
$query = "SELECT * FROM DEVICE_TABLE WHERE DEVICE_ID=?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($result) == 1){ //Если найдено устройство с таким ID в БД

if(isset($_GET['Rele'])) { //Если устройство передало новое состояние реле
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT OUT_STATE FROM OUT_STATE_TABLE WHERE DEVICE_ID = ?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE OUT_STATE_TABLE SET OUT_STATE=?, DATE_TIME='$date_today' WHERE DEVICE_ID = ?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'ii', $_GET['Rele'], $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$query = "INSERT DEVICES_CHANGE SET DEVICE_ID=?, TYPE_CHANGE='UPDATE OUT_STATE', DATE_TIME='$date_today'";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
} else { //Если данных для такого устройства нет - добавляем
$query = "INSERT OUT_STATE_TABLE SET DEVICE_ID=?, OUT_STATE=?, DATE_TIME='$date_today'"; //Записать данные
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'ii', $_GET['ID'], $_GET['Rele']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$query = "INSERT DEVICES_CHANGE SET DEVICE_ID=?, TYPE_CHANGE='INSERT OUT_STATE', DATE_TIME='$date_today'";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
}
}

if(isset($_GET['Term'])) { //Если устройство передало новое значение температуры
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT TEMPERATURE FROM TEMPERATURE_TABLE WHERE DEVICE_ID=?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE TEMPERATURE_TABLE SET TEMPERATURE=?, DATE_TIME='$date_today' WHERE DEVICE_ID = ?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'ii', $_GET['Term'], $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$query = "INSERT DEVICES_CHANGE SET DEVICE_ID=?, TYPE_CHANGE='UPDATE TEMPERATURE', DATE_TIME='$date_today'";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
} else { //Если данных для этого устройства нет - добавляем
$query = "INSERT TEMPERATURE_TABLE SET DEVICE_ID=?, TEMPERATURE=?, DATE_TIME='$date_today'"; //Записать данные
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'ii', $_GET['ID'], $_GET['Term']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$query = "INSERT DEVICES_CHANGE SET DEVICE_ID=?, TYPE_CHANGE='INSERT TEMPERATURE', DATE_TIME='$date_today'";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
}
}

$Command = -1;

//Достаём из БД текущую команду управления реле
$query = "SELECT COMMAND FROM COMMAND_TABLE WHERE DEVICE_ID = ?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, 'i', $_GET['ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства
$Arr = mysqli_fetch_array($result);
$Command = $Arr['COMMAND'];
}

//Отвечаем на запрос текущей командой
if($Command != -1) //Есть данные для этого устройства
{
echo "COMMAND $Command EOC";
}
else
{
echo "COMMAND ? EOC";
}
}
}

?>