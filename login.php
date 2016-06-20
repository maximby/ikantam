<?php
session_start();
if(isset($_SESSION['id_user'])) {
    header('Location: index.php');
}
$message = '';
$error_email = '';
$error_password = '';

if(isset($_POST['submit'])) {

    if(empty($error_email) && empty($error_password )) {

        $link = mysqli_connect('localhost', 'root', '', 'file');

        if ($link) {

            $email = mysqli_real_escape_string($link, $_POST['email']);
            $sql = "SELECT id, password  FROM user WHERE email='" . $email . "'";
            $mysql_result =  mysqli_query($link, $sql);

            if( $mysql_result) {

                $user = mysqli_fetch_assoc($mysql_result);

                if (password_verify($_POST['password'], $user['password'])) {

                    $_SESSION['id_user'] = $user['id'];
                    header('Location: index.php');
                }

            } else {

                $message = 'Email/Password incorrect.';
            }
        }
    }
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FILE</title>
    <link href="main.css" media="all" rel="stylesheet" type="text/css">
</head>
<body>
<div id="header">
    <h1>FILE</h1>
    <p><a href="registration.php">Registration</a></p>
</div>
<div id="form_login">
    <h1>Login</h1>
    <p><?=$message?></p>
    <form action="login.php" method="post">
        <input type="hidden" name="MAX_FILE_SEZE" value="1000000">
        <p>Your e-mail:</p>
        <input type="email" name="email"/>
        <p>Your password:</p>
        <input type="password" name="password"/>
        <br/>
        <input type="submit"  name="submit" value="Submit"/>
    </form>  
</div>