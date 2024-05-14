<?php 
session_start();
if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit;
}
require 'functions.php';
//pagination
//assign variable to the retrieved data from the necessary tables
$dataPerPage = 2;
$totalData = count(query("SELECT * FROM menu_items"));
$totalPages = ceil($totalData/$dataPerPage);
// var_dump($totalPages);
$activePage = (isset($_GET["page"])) ? $_GET["page"] : 1;
$initialData = ($dataPerPage * $activePage) - $dataPerPage;
//execute on fetching data
$menus = query("SELECT * FROM menu_items LIMIT $initialData, $dataPerPage" );

//users data
$users = query("SELECT * FROM users");

//search function execution
if(isset($_POST["search"])){
    //retrieve all objects that are indexed accordingly
    $lookup = query("SELECT * FROM menu_items"); 
    $menus = search_item($_POST["keyword"]);
    $value = $menus[0]; //select the object
    $index = array_search($value,$lookup);
    $activePage = intval($index/$dataPerPage)+1;
    if($index !== false){
        echo "Index: ".$index;
    }else{
        echo "Data not found!";
    }
    // var_dump(($lookup));
    // var_dump($menus);
}


// var_dump($users[0]["password"]);

//error handling if there is any mistake on configuring the connection or query
// if (!$result){
//     echo mysqli_error($conn);
// }

//fetch the data from the menu_items table
//mysqli_fetch_row(); //returns numeric array
//mysqli_fetch_assoc(); //returns associatve array
//mysqli_fetch_array(); //returns both associatve or numeric array
//mysqli_fetch_object(); //returns the object using -> to choose the field

// while ($menu = mysqli_fetch_assoc($result)){
//     var_dump($menu);
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <!-- Calling the jquery library -->
   <script src="js/jquery-3.7.1.min.js"></script>
   <script src="js/script.js"></script>
    <style>
        img{
            width: 200px;
            border:1px solid black;
            border-radius: 10%;
            display: flex;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <a href="logout.php">Logout</a>
   <h1>Menu Items</h1>

   <a href="Add.php">Add Menu Items</a><br>
   <br>
    <!-- Searching -->
   <form action="" method="post">

        <input type="text" id ="keyword" name="keyword" size="40" autofocus
        placeholder="Insert Query Here" autocomplete="off">

        <button type="submit" name="search" id="search-button">Search</button>
   </form>
<!-- Pagination -->
    <?php if($activePage > 1): ?>
        <a href="?page=<?=$activePage-1?>">Prev</a>
    <?php endif; ?>

   <?php for($i = 1; $i<=$totalPages;$i++):?>
    <?php if($i == $activePage): ?>
        <a href="?page=<?=$i?>" style="font-weight: bold; color:red;"><?=$i?></a>
    <?php else: ?>
        <a href="?page=<?=$i?>"><?=$i?></a>
    <?php endif; ?>
   <?php endfor;?>

   <?php if($activePage < $totalPages): ?>
        <a href="?page=<?=$activePage+1?>">Next</a>
    <?php endif; ?>
   <br>
   
   <div id="container">
   <table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Order</th>
        <th>Action</th>
        <th>Image</th>
        <th>Id</th>
        <th>Dish Name</th>
        <th>Price</th>
        <th>Description</th>
    </tr>

    <?php $i = 1?> <!-- id auto update after deletion occured -->
    <!-- distributing the unloaded package assigned to the necessary outputs-->
    <?php foreach($menus as $menu) : ?>
    <tr>
        <td><?=$i?></td>
        <td>
            <a href="update.php?id=<?=$menu["id"]?>">Update</a>
            <a href="delete.php?id=<?=$menu["id"]?>" onclick="
            return confirm('Are you sure?')">Delete</a>
        </td>
        <td><img src="<?=$menu["image"]?>"></td>
        <td><?=$menu["id"]?></td>
        <td><?=$menu["dish_name"]?></td>
        <td><?= "$". $menu["price"]?></td>
        <td><?=$menu["description"]?></td>
    </tr>
    <?php $i++ ?>
    <?php endforeach; ?>
   </table>
   </div>
   <!-- Script is placed before the end tag of the body so that the page can be loaded first, then the js logics and such can be applied, if without jquery -->
   
</body>
</html>