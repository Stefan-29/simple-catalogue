<?php
//connecting the php to the database (initialization)
$conn = mysqli_connect("localhost", "root", "", "basicphp_restaurant");

//Function to unload the package of the menu_items first
function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function add($data)
{
    global $conn;
    //ambil data dari tiap elemen dalam form
    //taken from name of the input because in post method
    // htmlspecialchars only returns the string, prevents the undesired outcomes
    // executed by other html elements
    $dish_name = htmlspecialchars($data["dish_name"]); 
    $dish_price = htmlspecialchars($data["dish_price"]);
    $description = htmlspecialchars($data["description"]);
    $image = upload();

    if(!$image){
        return false;
    }
    else{
    //query edit data
    $query = "INSERT INTO menu_items
                    VALUES
                    ('','$dish_name','$dish_price','$description','$image')
                    ";
    //mengecek supaya tidak ada data rumpang
    if ($dish_name == '' || $dish_price == '' || $description = '') {
        echo "<script>
            alert('Please fill all the form!');
            </script>";
        return false;
    } else {
        //jalankan query ke database
        mysqli_query($conn, $query);
        return mysqli_affected_rows($conn);
    }
    }
}

function delete($id){
    global $conn;
    $imageQuery = "SELECT image FROM menu_items WHERE id=$id";  
    //connect to db with the image selecting query
    $result = mysqli_query($conn,$imageQuery);
    //retrieved in associate array form (key => value)
    $row = mysqli_fetch_assoc($result);
    $imagePath = $row["image"];
    //query delete data
    $query = "DELETE FROM menu_items WHERE id= $id";
    mysqli_query($conn, $query);
    if(file_exists($imagePath)){
        unlink($imagePath);
    }
    else {
        echo "Error deleting the file.";
    }

    return mysqli_affected_rows($conn);
}

function edit($data){
    global $conn;
    $id = $data["id"]; 
    $dish_name = htmlspecialchars($data["dish_name"]); 
    $dish_price = htmlspecialchars($data["dish_price"]);
    $description = htmlspecialchars($data["description"]);
    $image = upload();

    if(!$image){
        return false;
    }
    else{
    //query edit data
    $query = "UPDATE menu_items 
                SET dish_name = '$dish_name',
                    price = '$dish_price',
                    description = '$description',
                    image = '$image'
                WHERE id = $id";
    if ($dish_name == '' || $dish_price == '' || $description = '') {
        echo "<script>
            alert('Please fill all the form!');
            </script>";
        return false;
    } else {
        //execute query
        mysqli_query($conn, $query);
        return mysqli_affected_rows($conn);
    }
    }
}

function register($data){
    global $conn;
    //see documentation of string function to get to know the use of it
    $name = strtolower(stripslashes($data["name"]));
    $password = mysqli_real_escape_string($conn,$data["password"]);
    $password2 = mysqli_real_escape_string($conn,$data["password2"]);
    
    //confirm password
    if($password !== $password2){
        echo "<script>
            alert('Password does not match!');
            </script>";
        return false;
    }

    //check if username is already exist
    $result = mysqli_query($conn,"SELECT name FROM users WHERE name = '$name'");
    if (mysqli_fetch_assoc($result)){
        echo "<script>
            alert('Username already exist!');
            </script>";
        return false;
    }

    //encrpyt password
    $password = password_hash($password, PASSWORD_DEFAULT);
    var_dump($password);
    $query = "INSERT INTO users
                VALUES
                ('','$name','$password')
                ";
    if ($name == '' || $password == '' || $password2 = '') {
        echo "<script>
            alert('Please fill all the form!');
            </script>";
        return false;
    } else {
        //execute query
        mysqli_query($conn, $query);
        return mysqli_affected_rows($conn);
    }

}

function search_item($keyword){
    $query = "SELECT *
              FROM menu_items
              WHERE dish_name LIKE '%$keyword%' OR
              price LIKE '%$keyword%' OR
              description LIKE '%$keyword%'";
    return query($query);
}

function upload(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Check if file was uploaded without errors
        if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
            var_dump($_FILES["photo"]);
            $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
            $filename = $_FILES["photo"]["name"];
            $filetype = $_FILES["photo"]["type"];
            $filesize = $_FILES["photo"]["size"];
        
            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
        
            // Verify file size - 5MB maximum
            $maxsize = 5 * 1024 * 1024;
            if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
        
            // Verify MYME type of the file
            if(in_array($filetype, $allowed)){
                // Check whether file exists before uploading it
                if(file_exists("upload/" . $filename)){
                    echo $filename . " is already exists.";
                } else{
                    move_uploaded_file($_FILES["photo"]["tmp_name"], "upload/" . $filename);
                    echo "Your file was uploaded successfully.";
                    $dir = "upload/" . $filename;
                    return $dir;
                } 
            } else{
                echo "Error: There was a problem uploading your file. Please try again.";
                
            }
        } else{
            var_dump($_FILES["photo"]);
            echo "Error: " . $_FILES["photo"]["error"];
        }
    
}

}
