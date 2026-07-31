<?php
session_start();
include("config.php");

$id = $_SESSION['user'];

$sql = "SELECT student.*, academic_info.*
FROM student
JOIN academic_info
ON student.student_id = academic_info.acd_student_id
WHERE student.student_id='$id'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

if($row['current_cgpa'] >= 3.75)
{
    $status = "Excellent academic performance. You are in excellent academic standing.";
}
elseif($row['current_cgpa'] >= 3.50)
{
    $status = "Very good academic performance. Keep up the good work and continue maintaining your CGPA.";
}
elseif($row['current_cgpa'] >= 3.00)
{
    $status = "Good academic standing. You are making satisfactory academic progress.";
}
elseif($row['current_cgpa'] >= 2.00)
{
    $status = "Satisfactory academic standing. Continue improving your academic performance.";
}
else
{
    $status = "Academic probation. Please improve your academic performance to continue your studies successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Academic Information - IIUC</title>

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

border-radius:10px;

color:white;
text-decoration:none;

transition:.3s;

}

.sidebar ul li a:hover{
background:rgba(255,255,255,.15);
}

.active{
background:rgba(255,255,255,.15);
}

/* Main Content */

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

/* Header */

.page-header{
padding:30px;
}

.page-header h1{
margin-bottom:10px;
color:#222;
}

.page-header p{
color:#666;
}

/* Academic Cards */

.academic-grid{

display:grid;
grid-template-columns:repeat(4,1fr);

gap:20px;

padding:0 30px 30px;

}

.academic-card{

background:white;

padding:25px;

border-radius:18px;

box-shadow:0 4px 15px rgba(0,0,0,.06);

transition:.3s;

text-align:center;

}

.academic-card:hover{

transform:translateY(-8px);

box-shadow:0 10px 25px rgba(0,0,0,.1);

}

.academic-card i{

font-size:38px;

color:#2344b3;

margin-bottom:15px;

}

.academic-card h4{

color:#666;

margin-bottom:12px;

}

.academic-card h2{

color:#222;

font-size:30px;

}

/* Summary Section */

.summary-section{

padding:0 30px 30px;

}

.summary-card{

background:white;

padding:30px;

border-radius:20px;

box-shadow:0 4px 15px rgba(0,0,0,.06);

}

.summary-card h2{

margin-bottom:20px;

color:#2344b3;

}

.summary-table{

width:100%;

border-collapse:collapse;

}

.summary-table tr{
border-bottom:1px solid #eee;
}

.summary-table td{

padding:15px;

font-size:15px;

}

.summary-table td:first-child{
font-weight:600;
color:#444;
}

.summary-table td:last-child{
color:#2344b3;
font-weight:600;
}

/* Status Card */

.status-card{

margin-top:20px;

background:#eef4ff;

padding:20px;

border-left:5px solid #2344b3;

border-radius:12px;

}

.status-card h3{
margin-bottom:10px;
color:#2344b3;
}

.status-card p{
color:#555;
line-height:1.6;
}

@media(max-width:1100px){

.academic-grid{
grid-template-columns:repeat(2,1fr);
}

}

@media(max-width:768px){

.academic-grid{
grid-template-columns:1fr;
}

.sidebar{
width:220px;
}

.main{
margin-left:220px;
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
<a href="profile.php">
<i class="fa-regular fa-user"></i>
Profile
</a>
</li>

<li>
<a href="academic.php" class="active">
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

<!-- Topbar -->

<div class="topbar">

<h2>Academic Information</h2>

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

<!-- Header -->

<div class="page-header">

<h1>Academic Overview</h1>

<p>View your academic performance and semester information.</p>

</div>

<!-- Academic Cards -->

<div class="academic-grid">

<div class="academic-card">

<i class="fa-solid fa-graduation-cap"></i>

<h4>Current Semester</h4>

<h2><?php echo $row['current_semester']; ?></h2>

</div>

<div class="academic-card">

<i class="fa-solid fa-book-open"></i>

<h4>Current GPA</h4>

<h2><?php echo $row['current_gpa']; ?></h2>

</div>

<div class="academic-card">

<i class="fa-solid fa-chart-line"></i>

<h4>Current CGPA</h4>

<h2><?php echo $row['current_cgpa']; ?></h2>

</div>

<div class="academic-card">

<i class="fa-solid fa-award"></i>

<h4>Completed Credits</h4>

<h2><?php echo $row['completed_credits']; ?></h2>

</div>

</div>

<!-- Summary -->

<div class="summary-section">

<div class="summary-card">

<h2>Academic Summary</h2>

<table class="summary-table">

<tr>
<td>Student Name</td>
<td><?php echo $row['name']; ?></td>
</tr>

<tr>
<td>Student ID</td>
<td><?php echo $row['student_id']; ?></td>
</tr>

<tr>
<td>Program</td>
<td><?php echo $row['program']; ?></td>  
</tr>

<tr>
<td>Current Semester</td>
<td><?php echo $row['current_semester']; ?>th Semester</td>
</tr>

<tr>
<td>Current GPA</td>
<td><?php echo $row['current_gpa']; ?></td>
</tr>

<tr>
<td>Current CGPA</td>
<td><?php echo $row['current_cgpa']; ?></td>
</tr>

<tr>
<td>Completed Credits</td>
<td><?php echo $row['completed_credits']; ?></td>
</tr>

</table>

<div class="status-card">

<h3>Academic Status</h3>

<p><?php echo $status; ?></p>

</div>

</div>

</div>

</div>

</div>

</body>

</html>