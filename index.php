<?php
session_start();
include("config.php");

if(isset($_POST['login']))
{
$user=$_POST['userid'];
$pass=$_POST['password'];

$sql="SELECT * FROM user WHERE user_id='$user' AND password='$pass'";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==1)
{
    // Admin Login
    if($user=="ADM001")
    {
        $_SESSION['admin']=$user;
        header("Location: admin-dashboard.php");
        exit();
    }

    // Student Login
    else
    {
        $_SESSION['user']=$user;
        header("Location: dashboard.php");
        exit();
    }
}
else
{
echo "<script>alert('Invalid Login');</script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>IIUC Login</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
}

body{
background:#edf2f7;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.login-card{

width:100%;
max-width:420px;

background:white;

padding:35px;

border-radius:20px;

box-shadow:0 10px 30px rgba(0,0,0,.1);

text-align:center;

}

.logo{
width:70px;
margin-bottom:15px;
}

h1{
font-size:22px;
color:#2344b3;
line-height:1.4;
}

h2{
font-size:18px;
color:#2344b3;
margin-top:5px;
margin-bottom:25px;
}

.input-group{
text-align:left;
margin-bottom:18px;
}

.input-group label{
display:block;
margin-bottom:8px;
font-weight:500;
}

.input-group input{

width:100%;
height:50px;

border:1px solid #ddd;

border-radius:10px;

padding:0 15px;

font-size:15px;

}

.forgot{
text-align:right;
margin-bottom:20px;
}

.forgot a{
text-decoration:none;
color:#2344b3;
font-size:14px;
}

.login-btn{

display:flex;
justify-content:center;
align-items:center;

width:100%;
height:50px;

background:#2344b3;

color:white;

text-decoration:none;

border-radius:10px;

font-weight:600;

transition:.3s;

}

.login-btn:hover{
background:#1d3998;
}

.demo-text{
margin-top:20px;
font-size:12px;
color:#777;
}

</style>

</head>

<body>

<div class="login-card">

<img src="images/iiuc.png" class="logo">

<h1>
International Islamic University Chittagong
</h1>

<h2>(IIUC)</h2>

<div class="input-group">

<label>User ID</label>

<form method="POST">

<input type="text"
name="userid"
placeholder="Enter User ID"
required>

</div>

<div class="input-group">

<label>Password</label>

<input
type="password"
name="password"
placeholder="Enter Password"
required>

</div>


<button
type="submit"
name="login"
class="login-btn">

Login

</button>

</form>


</div>

</body>
</html>



