<?php
session_start();
include("config.php");

$id = $_SESSION['user'];

$sql = "SELECT student.*, academic_info.*, user.email
FROM student
JOIN academic_info
ON student.student_id = academic_info.acd_student_id
JOIN user
ON student.student_id = user.user_id
WHERE student.student_id='$id'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profile - IIUC</title>

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

padding:14px 18px;

text-decoration:none;

color:white;

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
color:#2344b3;
font-size:20px;
text-decoration:none;
}

/* Profile Card */

.profile-card{

background:white;

margin:30px;

padding:35px;

border-radius:20px;

box-shadow:0 4px 15px rgba(0,0,0,.06);

}

.profile-header{

display:flex;
align-items:center;
gap:20px;

margin-bottom:30px;

}

.large-avatar{

width:90px;
height:90px;

border-radius:50%;

background:#2344b3;

display:flex;
justify-content:center;
align-items:center;

font-size:32px;
font-weight:700;
color:white;

}

.profile-header h2{
margin-bottom:8px;
}

.profile-header p{
color:#666;
}

.info-grid{

display:grid;

grid-template-columns:repeat(2,1fr);

gap:20px;

}

.info-box{

background:#f8f9fd;

padding:20px;

border-radius:15px;

transition:.3s;

}

.info-box:hover{
transform:translateY(-5px);
}

.info-box label{

display:block;

color:#777;

margin-bottom:8px;

font-size:14px;

}

.info-box h4{
font-size:17px;
}

@media(max-width:900px){

.info-grid{
grid-template-columns:1fr;
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
<a href="dashboard.php">
<i class="fa-solid fa-table-cells-large"></i>
Dashboard
</a>
</li>

<li>
<a href="profile.php" class="active">
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

<h2>Student Profile</h2>

<div class="user-info">

<div>

<h4><?php echo $row['name']; ?></h4>
<small><?php echo $row['student_id']; ?></small>

</div>

<div class="avatar"><?php echo strtoupper(substr($row['name'],0,1)); ?></div>

<a href="index.php" class="logout">

<i class="fa-solid fa-right-from-bracket"></i>

</a>

</div>

</div>

<!-- Profile -->

<div class="profile-card">

<div class="profile-header">

<div class="large-avatar">
J
</div>

<div>

<h2><?php echo $row['name']; ?></h2>

<p>B.Sc. in Computer Science Engineering</p>

</div>

</div>

<div class="info-grid">

<div class="info-box">
<label>Student ID</label>
<h4><?php echo $row['student_id']; ?></h4>
</div>

<div class="info-box">
<label>Email</label>
<h4><?php echo $row['email']; ?></h4>
</div>

<div class="info-box">
<label>Phone</label>
<h4><?php echo $row['phone']; ?></h4>
</div>

<div class="info-box">
<label>Section</label>
<h4><?php echo $row['section']; ?></h4>
</div>

<div class="info-box">
<label>Father Name</label>
<h4><?php echo $row['father_name']; ?></h4>
</div>

<div class="info-box">
<label>Mother Name</label>
<h4><?php echo $row['mother_name']; ?></h4>
</div>

<div class="info-box">
<label>Program</label>
<h4><?php echo $row['program']; ?></h4>
</div>

<div class="info-box">
<label>Current Semester</label>
<h4><?php echo $row['current_semester']; ?>th Semester</h4>
</div>

</div>

</div>

</div>

</div>

</body>

</html>