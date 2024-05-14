<?php
require "functions.php";

session_start();

//check the cookie first if it is still there 
//for the cookie session, then check the database. 
//If not in the cookie, then the cookie will be deleted
if(isset($_COOKIE["id"]) && isset($_COOKIE["key"])){
    $id = $_COOKIE["id"];
    $key = $_COOKIE["key"];

    //retrieve username based on id
    $result = mysqli_query($conn, "SELECT name FROM users WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

    //check the cookie and encrypted name, prevent the cookie tampering
    if($key === hash("sha256", $row["name"])){
        $_SESSION["login"] = true;
    }
}
//go to index if login is true
if(isset($_SESSION["login"])){
    header("Location: index.php");
    exit;
}

    if(isset($_POST["login"])){
        
        $name = $_POST["name"];
        $password = $_POST["password"];
        
        $result = mysqli_query($conn, "SELECT * FROM users WHERE name = '$name'");
        
        if(mysqli_num_rows($result) === 1){
            $row = mysqli_fetch_assoc($result);
            if(password_verify($password, $row["password"])){
                    //set session
                    $_SESSION["login"] = true;
                    //remember me (cookie) condition check
                    if(isset($_POST["remember"])){
                        //create cookie
                        // setcookie("login", "true", time()+60);
                        setcookie("id", $row["id"], time()+60);
                        //set hash for cookie encryption, to make it more secure
                        setcookie("key", hash("sha256",$row["name"]), time()+60);
            }
            echo "<script>
            alert('Login successful!');</script>";    
            echo "<script>document.location.href = 'index.php';
        </script>";
        exit;
        }
       $error=true;
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
        label[for]{
            display: inline;
        }
        .error{
            color: red;
            font-style: italic;
            font-weight: bolder;
        }
    </style>
</head>
<body>
    <h1>Login Page</h1>

    <?php if(isset($error)):?>
        <p class="error">Username / Password is incorrect</p>
    <?php endif; ?>

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
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </li>

            </li>
                <button type="submit" name="login">Login</button>
            </li>
        </ul>
    </form>
</body>
</html>