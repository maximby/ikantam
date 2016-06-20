<?php
session_start();
if(!isset($_SESSION['id_user'])) {
   header('Location: login.php');
}

$link = mysqli_connect('localhost', 'root', '', 'file');

$message = '';
$error_caption = '';

if(isset($_POST['submit'])) {

    $caption = trim($_POST['caption']);

    if(empty($caption)) {
        $error_caption = 'Empty field Caption.';
    }

    if($_FILES['filename']['error'] === 0 && !empty($caption)) {

        $uploadfile = __DIR__ . '/files/' . basename($_FILES['filename']['name']);

        if (move_uploaded_file($_FILES['filename']['tmp_name'], $uploadfile)) {

            if ($link) {

                $id_user = (int) $_SESSION['id_user'];
                $caption = mysqli_real_escape_string($link, $caption);
                $filename = mysqli_real_escape_string($link, $_FILES['filename']['name']);
                $type = mysqli_real_escape_string($link,$_FILES['filename']['type'] );
                $size =(int)$_FILES['filename']['size'] ;
                $sql = "INSERT INTO files VALUES (null,'$id_user','$caption','$filename', '$type', '$size' )";
                $result =  mysqli_query($link, $sql);

                if( $result) {

                    $message = 'File has been successfully downloaded.';
                }
            }
        } else {

            $message = 'Was unable to save the file.';
        }

    } else {

        $message = 'File failed to load.';
    }

}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FILE</title>
    <link href="main.css" media="all" rel="stylesheet" type="text/css">
</head>
<body>
<div id="header">
    <h1>FILE</h1>
    <p><a href="logout.php">Logout</a></p>
</div>
<div>
    <h2>List of downloaded files</h2>
    <p><?=$message?></p>
    <table>

        <tr>
            <th>Caption</th>
            <th>Filename</th>
            <th>Mime-Type</th>
            <th>Size</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>E-mail</th>
        </tr>
       <?php
       $sql = "SELECT files.id, caption, filename, type, size, first_name, last_name, email
                      FROM files LEFT OUTER JOIN user ON files.id_user = user.id ORDER BY files.id  DESC";
       if ($result = mysqli_query($link, $sql)) :
           while ($row = mysqli_fetch_assoc($result)) :
        ?>
               <tr>
                   <td>
                       <a href="files/<?=htmlentities($row['filename'])?>?id=<?=urlencode($row['id'])?>">
                           <?=$row['caption']?>
                       </a>
                   </td>
                   <td><?=htmlentities($row['filename'])?></td>
                   <td><?=htmlentities($row['type'])?></td>
                   <td><?=htmlentities($row['size'])?></td>
                   <td><?=htmlentities($row['first_name'])?></td>
                   <td><?=htmlentities($row['last_name'])?></td>
                   <td><?=htmlentities($row['email'])?></td>
               </tr>
         <?php
           endwhile;
           mysqli_free_result($result);
       endif;
      ?>
    </table>
</div>
<br/>
<br/>
<div>
    <h3>Add new file</h3>

    <form action="index.php" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="MAX_FILE_SEZE" value="1000000">
        <p><input type="file" name="filename"/></p>
        <p><?= $error_caption?></p>
        <p>Caption:<input type="text" name="caption"/></p>
        <br/>
        <input type="submit"  name="submit" value="Submit"/>
    </form>
</div>
