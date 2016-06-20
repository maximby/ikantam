<?php
session_start();
if(isset($_SESSION['id_user'])) {
    header('Location: index.php');
}
$message = '';
$error_email = '';
$error_password = '';
$error_first = '';
$error_last = '';

if(isset($_POST['submit'])) {

    if(empty($_POST['first_name'])) {
        $error_first = 'First Name field must be filled.';
    }

    if(empty($_POST['last_name'])) {
        $error_last = 'Last Name field must be filled.';
    }

    if(empty($_POST['email'])) {
        $error_email = 'Email field must be filled.';

    } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error_email = 'Incorrect email.';
    }

    if(empty($_POST['password'])) {
        $error_password = 'Password field must be filled.';

    } elseif (mb_strlen($_POST['password'],'UTF-8') < 8) {
        $error_password = 'The password must be longer than 8 characters.';

    }



    if(empty($error_email) && empty($error_password )) {

        $link = mysqli_connect('localhost', 'root', '', 'file');

        if ($link) {

            $email = mysqli_real_escape_string($link, $_POST['email']);
            $password = mysqli_real_escape_string($link,$_POST['password']);
            $last_name = mysqli_real_escape_string($link,$_POST['last_name']);
            $first_name = mysqli_real_escape_string($link,$_POST['first_name']);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user VALUES (null,'$email','$password_hash','$first_name','$last_name' )";
            $result =  mysqli_query($link, $sql);

            if($result) {
              header('Location: login.php');

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
</div>
<div id="form_login">
    <h2>Registration</h2>
    <p><?=$message?></p>
    <form action="registration.php" method="post">
        <p>Your e-mail:</p>
        <p><?= $error_email ?></p>
        <input type="email" name="email"/>
        <p><?= $error_password?></p>
        <p>Your password:</p>
        <input type="password" name="password"/>
        <p><?= $error_first?></p>
        <p>Your First Name</p>
        <input type="text" name="first_name"/>
        <p><?= $error_last?></p>
        <p>Your Last Name:</p>
        <input type="text" name="last_name"/>

        <br/>
        <input type="submit"  name="submit" value="Submit"/>
    </form>
</div>