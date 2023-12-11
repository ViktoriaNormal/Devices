<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Authorization</title>
        <link rel="stylesheet" href="style_form.css">
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
                <!-- Форма для авторизации -->
                <form method="POST" action="">

                <?php
                include 'connection_db.php';

                $salt = 'my_unique_salt_for_users';

                function hashPassword($password, $salt) {
                    // Хеширование пароля с сохраненной солью
                    $hashedPassword = hash('sha256', $password . $salt);
                    
                    // Возвращение захешированного пароля
                    return $hashedPassword;
                }

                session_start();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $login = $_POST["username"];
                    $password = hashPassword($_POST["password"], $salt);

                    $sql = "SELECT * FROM user WHERE LOGIN = ? AND PASSWORD = ?";
                    $stmt = mysqli_prepare($mysql, $sql);
                    mysqli_stmt_bind_param($stmt, 'ss', $login, $password);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result->num_rows == 1) {
                        $_SESSION["LOGIN"] = $login;
                        $_SESSION["USER_ID"] = $result->fetch_assoc()["USER_ID"];
                        
                        header("Location: index_iot.php");
                        exit();
                    } else {
                        echo '<p>The username or password you entered is incorrect.</p>';
                    }
                }

                $mysql->close();
                ?>

                    <h1>Authorization</h1>  

                    <div id="box">
                        <div class="field">
                            <div class="lab"><label for="username">Login</label></div>
                            <input  class="inputfield" type="text" name="username" id="username" required>
                        </div>

                        <div class="field">
                            <div class="lab"><label for="password">Password</label></div>
                            <input  class="inputfield" type="password" name="password" id="password" required>
                        </div>
                    </div>

                    <div><button id="signIn" type="submit">Submit</button></div>

                </form>
            </div>

            <div class="image"><img class="light" src="background_light.jpg"></div>

            <footer>
                    Formed on <?php echo date('d.m.Y'); ?>
            </footer> 
    </body>
</html>
