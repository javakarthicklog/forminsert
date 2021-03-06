<?php

$dsn = 'mysql:host=localhost;dbname=my';
$username = 'root';
$password = '';

try{
    // Connect To MySQL Database
    $con = new PDO($dsn,$username,$password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (Exception $ex) {
    
    echo 'Not Connected '.$ex->getMessage();
    
}

$id = '';
$firstname= '';
$lastname = '';
$email = '';

function getPosts()
{
    $posts = array();
    
    $posts[0] = $_POST['id'];
    $posts[1] = $_POST['firstname'];
    $posts[2] = $_POST['lastname'];
    $posts[3] = $_POST['email'];
    
    return $posts;
}

//Search And Display Data 

if(isset($_POST['search']))
{
    $data = getPosts();
    if(empty($data[0]))
    {
        echo 'Enter The User Id To Search';
    }  else {
        
        $searchStmt = $con->prepare('SELECT * FROM guest WHERE id = :id');
        $searchStmt->execute(array(
                    ':id'=> $data[0]
        ));
        
        if($searchStmt)
        {
            $user = $searchStmt->fetch();
            if(empty($user))
            {
                echo 'No Data For This Id';
            }
            
            $id    = $user[0];
            $firstname = $user[1];
            $lastname = $user[2];
            $email = $user[3];
        }
        
    }
}

// Insert Data

if(isset($_POST['insert']))
{
    $data = getPosts();
    if(empty($data[1]) || empty($data[2]) || empty($data[3]))
    {
        echo 'Enter The User Data To Insert';
    }  else {
        
        $insertStmt = $con->prepare('INSERT INTO guest(firstname,lastname,email) VALUES(:firstname,:lastname,:email)');
        $insertStmt->execute(array(
                    ':firstname'=> $data[1],
                    ':lastname'=> $data[2],
                    ':email'=> $data[3],
        ));
        
        if($insertStmt)
        {
                echo 'Data Inserted';
        }
        
    }
}

//Update Data

if(isset($_POST['update']))
{
    $data = getPosts();
    if(empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3]))
    {
        echo 'Enter The User Data To Update';
    }  else {
        
        $updateStmt = $con->prepare('UPDATE guest SET firstname = :firstname, lastname = :lastname, email = :email WHERE id = :id');
        $updateStmt->execute(array(
                    ':id'=> $data[0],
                    ':firstname'=> $data[1],
                    ':lastname'=> $data[2],
                    ':email'=> $data[3],
        ));
        
        if($updateStmt)
        {
                echo 'Data Updated';
        }
        
    }
}

// Delete Data

if(isset($_POST['delete']))
{
    $data = getPosts();
    if(empty($data[0]))
    {
        echo 'Enter The User ID To Delete';
    }  else {
        
        $deleteStmt = $con->prepare('DELETE FROM guest WHERE id = :id');
        $deleteStmt->execute(array(
                    ':id'=> $data[0]
        ));
        
        if($deleteStmt)
        {
                echo 'User Deleted';
        }
        
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>PHP (MySQL PDO): Insert, Update, Delete, Search</title>  
    </head>
    <body>
        <form action="" method="POST">

            <input type="number" name="id" placeholder="id" value="<?php echo $id;?>"><br><br>
            <input type="text" name="firstname" placeholder="First Name" value="<?php echo $firstname;?>"><br><br>
            <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $lastname;?>"><br><br>
            <input type="text"name="email" placeholder="email" value="<?php echo $email;?>"><br><br>
            
            <input type="submit" name="insert" value="Insert">
            <input type="submit" name="update" value="Update">
            <input type="submit" name="delete" value="Delete">
            <input type="submit" name="search" value="Search">

        </form>
        
    </body>    
</html>