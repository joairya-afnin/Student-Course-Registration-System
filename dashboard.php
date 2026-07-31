<?php

session_start();

include("config.php");

$id=$_SESSION['user'];

$sql="SELECT
student.*,
academic_info.*
FROM student

JOIN academic_info

ON student.student_id=
academic_info.acd_student_id

WHERE student.student_id='$id'";

$result=mysqli_query($conn,$sql);

$row=mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>IIUC Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
}

body{
background:#f4f7fc;
}

.container{
display:flex;
min-height:100vh;
}

/* Sidebar */

.sidebar{

width:260px;

background:#2344b3;

color:white;

position:fixed;

height:100vh;

}

.logo-section{

text-align:center;

padding:25px;

border-bottom:1px solid rgba(255,255,255,.2);

}

.logo-section img{
width:65px;
margin-bottom:10px;
}



.sidebar ul{
list-style:none;
padding:20px 10px;
}

.sidebar ul li{
margin-bottom:10px;
}

.sidebar ul li a{

display:flex;
align-items:center;
gap:12px;

text-decoration:none;

color:white;

padding:14px 18px;

border-radius:10px;

transition:.3s;

}

.sidebar ul li a:hover{
background:rgba(255,255,255,.15);
}

.active{
background:rgba(255,255,255,.15);
}

/* Main */

.main{

margin-left:260px;

width:100%;

}

/* Topbar */

.topbar{

height:80px;

background:white;

display:flex;

justify-content:space-between;

align-items:center;

padding:0 30px;

box-shadow:0 2px 10px rgba(0,0,0,.05);

}

.user-info{

display:flex;
align-items:center;
gap:15px;

}

.avatar{

width:45px;
height:45px;

border-radius:50%;

background:#2344b3;

display:flex;

justify-content:center;
align-items:center;

color:white;

font-weight:600;

}

.logout{

text-decoration:none;

color:#2344b3;

font-size:20px;

}

/* Welcome */

.welcome{

padding:30px;

}

.welcome h1{
margin-bottom:8px;
}

.welcome p{
color:#666;
}

/* Cards */

.stats{

display:grid;

grid-template-columns:repeat(4,1fr);

gap:20px;

padding:0 30px;

}

.card{

background:white;

padding:25px;

border-radius:18px;

box-shadow:0 4px 15px rgba(0,0,0,.06);

transition:.3s;

}

.card:hover{
transform:translateY(-5px);
}

.card i{

font-size:32px;

color:#2344b3;

margin-bottom:15px;

}

.card h4{
color:#666;
margin-bottom:10px;
}

.card h2{
color:#222;
}

/* Quick Actions */

.section-title{
padding:30px;
}

.actions{

display:grid;

grid-template-columns:repeat(5,1fr);

gap:20px;

padding:0 30px 30px;

}

.action-card{

background:white;

padding:25px;

border-radius:18px;

text-decoration:none;

color:#222;

text-align:center;

box-shadow:0 4px 15px rgba(0,0,0,.06);

transition:.3s;

}

.action-card:hover{
transform:scale(1.04);
}

.action-card i{

font-size:35px;

color:#2344b3;

margin-bottom:15px;

}

.action-card h3{
font-size:16px;
}

@media(max-width:1100px){

.stats{
grid-template-columns:repeat(2,1fr);
}

.actions{
grid-template-columns:repeat(2,1fr);
}

}

</style>

</head>

<body>

<div class="container">

<!-- Sidebar -->

<div class="sidebar">

<div class="logo-section">

<img src="images/iiuc.png">

<h2>IIUC</h2>

<p>Student Portal</p>

</div>

<ul>

<li>
<a href="dashboard.php" class="active">
<i class="fa-solid fa-table-cells-large"></i>
Dashboard
</a>
</li>

<li>
<a href="profile.php">
<i class="fa-regular fa-user"></i>
Profile
</a>
</li>

<li>
<a href="academic.php">
<i class="fa-solid fa-book-open"></i>
Academic Information
</a>
</li>

<li>
<a href="courses.php">
<i class="fa-solid fa-graduation-cap"></i>
Courses
</a>
</li>

<li>
<a href="registration.php">
<i class="fa-regular fa-clipboard"></i>
Course Registration
</a>
</li>

<li>
<a href="payment.php">
<i class="fa-regular fa-credit-card"></i>
Payment
</a>
</li>

<li>
<a href="result.php">
<i class="fa-regular fa-file-lines"></i>
Results
</a>
</li>

</ul>

</div>

<!-- Main -->

<div class="main">

<div class="topbar">

<h2>Dashboard</h2>

<div class="user-info">

<div>

<h4><?php echo $row['name']; ?></h4>

<small><?php echo $row['student_id']; ?></small>

</div>

<div class="avatar">

<?php echo strtoupper(substr($row['name'],0,1)); ?>

</div>

<a href="index.php" class="logout">

<i class="fa-solid fa-right-from-bracket"></i>

</a>

</div>

</div>

<div class="welcome">

<h1>Welcome Back, <?php echo $row['name']; ?></h1>

<p>Student Course Registration System</p>

</div>

<!-- Stats -->

<div class="stats">

<div class="card">

<i class="fa-solid fa-graduation-cap"></i>

<h4>Current Semester</h4>

<h2><?php echo $row['current_semester']; ?>th</h2>

</div>

<div class="card">

<i class="fa-solid fa-book-open"></i>

<h4>Current GPA</h4>

<h2><?php echo $row['current_gpa']; ?></h2>

</div>

<div class="card">

<i class="fa-regular fa-file-lines"></i>

<h4>Current CGPA</h4>

<h2><?php echo $row['current_cgpa']; ?></h2>

</div>

<div class="card">

<i class="fa-regular fa-clipboard"></i>

<h4>Completed Credits</h4>

<h2><?php echo $row['completed_credits']; ?></h2>

</div>

</div>

<!-- Quick Actions -->

<h2 class="section-title">

Quick Actions

</h2>

<div class="actions">

<a href="profile.php" class="action-card">

<i class="fa-regular fa-user"></i>

<h3>Profile</h3>

</a>

<a href="academic.php" class="action-card">

<i class="fa-solid fa-book-open"></i>

<h3>Academic Information</h3>

</a>

<a href="courses.php" class="action-card">

<i class="fa-solid fa-graduation-cap"></i>

<h3>Courses</h3>

</a>

<a href="registration.php" class="action-card">

<i class="fa-regular fa-clipboard"></i>

<h3>Registration</h3>

</a>

<a href="payment.php" class="action-card">

<i class="fa-regular fa-credit-card"></i>

<h3>Payment</h3>

</a>

</div>

</div>

</div>

</body>

</html>