<?php
session_start();
$_SESSION['id_user']=null;
unset ($_SESSION);
header('Location: login.php');