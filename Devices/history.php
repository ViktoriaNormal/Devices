<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration</title>
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
        <h1>История управления устройством</h1>

        <?php
        include "connection_db.php";

        session_start();
        $user_id = $_SESSION["USER_ID"];
        $id = $_POST["button_history"];

        $query = "SELECT * FROM USER_DEVICE_STATUS WHERE USER_ID=? AND DEVICE_ID=?;";
        $stmt = mysqli_prepare($mysql, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $count = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            if($count==0) {
                echo "<table><thead><tr><th width=312px>Выполненная команда</th><th width=312px>Дата и время выполнения</th></tr></thead><tbody>";
            }
            $count += 1;
            echo "<tr><td width=312px>". $row['COMMAND']."</td><td width=312px>". $row['DATE_TIME']."</td></tr>";
        }
        echo "</tbody></table>";
        ?>
        </div>

        <footer>
                Formed on <?php echo date('d.m.Y'); ?>
        </footer> 

    </body>

</html>