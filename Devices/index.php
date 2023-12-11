<?php
$connect = mysqli_connect("localhost", "db", "qwerty", "db");

if (!$connect){
    echo "Error: unable to establish a connection with MySQL.";
    exit;
}

mysqli_set_charset($connect, "utf8");

$result = mysqli_query($connect, "SELECT * FROM users");

echo "<table width=\"\" border='1'>";
echo "<tr><td>id</td><td>name</td><td>login</td><td>password</td></tr>";

while($row = mysqli_fetch_assoc($result)){
echo "
<tr>
<td>".$row[user_id]."</td>
<td>$row[name]</td>
<td>$row[login]</td>
<td>$row[password]</td>
</tr>
";
}

echo "</table>";

?>