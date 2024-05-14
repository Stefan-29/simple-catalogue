<?php
    require 'functions.php';

    if(isset($_POST["register"])){
        if(register($_POST) > 0){
            echo "<script>
                alert('User has been registered!');
                </script>";
        }
        else{
            echo mysqli_error($conn);
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        label{
            display: block;
        }
    </style>
</head>
<body>
    <h1>Registration Page</h1>

    <form action="" method="post">
        <ul>
            <li>
                <label for="name">Name: </label>
                <input type="text" id="name" name="name">
            </li>
            <li>
                <label for="password">Password: </label>
                <input type="password" id="password" name="password">
            </li>
            <li>
                <label for="password2">Confirm Password: </label>
                <input type="password" id="password2" name="password2">
            <li>
                <button type="submit" name="register">Register Here</button>
            </li>
        </ul>
    </form>
</body>
</html>