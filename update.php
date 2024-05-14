<?php
session_start();
if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit;
}

require 'functions.php';
//connecting to the DB
$conn = mysqli_connect("localhost","root","","basicphp_restaurant");
//get the selected id to change the data entry
$id = $_GET["id"];
//querying by id selection
$menu_selected = query("SELECT * FROM menu_items WHERE id = $id")[0];
//var_dump($menu_selected["price"]);

//checkin the availability of the form
if (isset($_POST["submit"])){
    //var_dump($_POST);

    if (edit($_POST)>0){
        echo "<script>
                alert('Successfully edited!');
                document.location.href = 'index.php';
            </script>";
    }
    else{
        echo "<script>
        alert('Failed to update data!');
    </script>";    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Items Form</title>
</head>
<body>
    <h1>Edit Menu Item</h1>

    <form action="" method="post">
        <ul>
            <li>
                <input type="hidden" name="id" value="<?=$menu_selected["id"]?>">
            </li>
            <li>
                <label for="dish_name">Dish Name</label>
                <input type="text" name="dish_name" id="dish_name" value="<?=$menu_selected["dish_name"]?>">
            </li>
            <li>
                <label for="dish_price">Price</label>
                <input type="text" name="dish_price" id="dish_price" value="<?=$menu_selected["price"]?>">
            </li>
            <li>
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10"><?=$menu_selected["description"]?></textarea>
            </li>
            <li>
                <label for="photo">Image</label>
                <input type="file" name="photo" id="photo" value="<?=$menu_selected["image"]?>">
            </li>
            <li>
                <!-- input submit dapat digunakan, namun sudah tertinggal pada versi 4 lalu-->
                <button type="submit" name="submit">Edit Menu Item</button>
            </li>
        </ul>
    </form>

</body>

</html>