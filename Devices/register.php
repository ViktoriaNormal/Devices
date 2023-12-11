<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration</title>
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
                <!-- HTML форма для регистрации -->
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

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $username = $_POST["username"];
                    $login = $_POST["login"];
                    $password = $_POST["password"];
                    $hash = hashPassword($password, $salt);

                    // Проверка, что пароль и имя пользователя уникальны

                    $sql = "SELECT * FROM user WHERE LOGIN = ?";
                    $stmt = mysqli_prepare($mysql, $sql);
                    mysqli_stmt_bind_param($stmt, 's', $login);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        echo '<p>A user with the same name already exists.</p>';
                    } 
                    else {
                        $sql = "INSERT INTO user (NAME, LOGIN, PASSWORD) VALUES (?, ?, ?)";
                        $stmt = mysqli_prepare($mysql, $sql);
                        mysqli_stmt_bind_param($stmt, 'sss', $username, $login, $hash);
                        try {
                            if (mysqli_stmt_execute($stmt) === TRUE) {
                                echo '<p>Registration completed successfully.</p>';
                            } 
                        }
                        catch (Exception $e) {
                            echo '<p>Error during registration.</p>';
                        }
                    }
                }

                $mysql->close();
                ?>

                    <h1>Registration</h1>  

                    <div id="box">
                        <div class="field">
                            <div class="lab"><label for="username">Username</label></div>
                            <input  class="inputfield" type="text" name="username" id="username" required>
                        </div>

                        <div class="field">
                            <div class="lab"><label for="username">Login</label></div>
                            <input  class="inputfield" type="text" name="login" id="login" required>
                        </div>

                        <div class="field">
                            <div class="lab"><label for="password">Password</label></div>
                            <input  class="inputfield" type="password" name="password" id="password" required>
                        </div>
                    </div>

                    <div><button type="submit">Submit</button></div>

                </form>
            </div>

            <div class="image"><img class="light" src="background_light.jpg"></div>

        <footer>
                Formed on <?php echo date('d.m.Y'); ?>
        </footer> 

    </body>

</html>
