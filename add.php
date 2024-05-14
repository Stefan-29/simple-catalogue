<?php
session_start();
if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit;
}

require 'functions.php';
//connecting to the DB
$conn = mysqli_connect("localhost","root","","basicphp_restaurant");

//checkin the availability of the form
if (isset($_POST["submit"])){
    //var_dump($_POST);

    if (add($_POST)>0){
        echo "<script>
                alert('Successfully added!');
                document.location.href = 'index.php';
            </script>";
    }
    else{
        echo "<script>
        alert('Failed to add data!');
    </script>";    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Items Form</title>
</head>
<body>
    <h1>Add Menu Items</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <ul>
            <li>
                <label for="dish_name">Dish Name</label>
                <input type="text" name="dish_name" id="dish_name">
            </li>
            <li>
                <label for="dish_price">Price</label>
                <input type="text" name="dish_price" id="dish_price">
            </li>
            <li>
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10"></textarea>
            </li>
            <li>
                <label for="photo">Photo</label>
                <input type="file" name="photo" id="photo">
            </li>
            <li>
                <!-- input submit can still be done, but it is deprecated on version 4-->
                <button type="submit" name="submit">Add Menu Item</button>
            </li>
        </ul>
    </form>
    <a href="index.php">Back to Menu Items Page</a>

</body>

</html>