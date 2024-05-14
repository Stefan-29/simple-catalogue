<?php
require "../../functions.php";
$keyword = $_GET["keyword"];
$query = "SELECT *
    FROM menu_items
    WHERE dish_name LIKE '%$keyword%' OR
    price LIKE '%$keyword%' OR
    description LIKE '%$keyword%'";

$menus = query($query);

// var_dump($menus);

?>
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
