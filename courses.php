<?php
session_start();
include("config.php");

$id = $_SESSION['user'];

$student = mysqli_query($conn, "SELECT * FROM student WHERE student_id='$id'");
$row = mysqli_fetch_assoc($student);

$sql = "SELECT * FROM course";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Courses - IIUC</title>

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

/* Main */

.main{
margin-left:260px;
width:100%;
}

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

.page-header{
padding:30px;
}

.page-header h1{
margin-bottom:10px;
}

.page-header p{
color:#666;
}

.table-container{
padding:0 30px 30px;
}

.course-table{

width:100%;

background:white;

border-radius:20px;

overflow:hidden;

border-collapse:collapse;

box-shadow:0 4px 15px rgba(0,0,0,.06);

}

.course-table thead{

background:#2344b3;

color:white;

}

.course-table th,
.course-table td{

padding:15px;
text-align:left;

border-bottom:1px solid #eee;

font-size:14px;

}


.badge{

padding:5px 10px;

border-radius:20px;

font-size:12px;

font-weight:600;

}

.badge-theory{
background:#dbeafe;
color:#1e40af;
}

.badge-lab{
background:#dcfce7;
color:#166534;
}

.note-card{

background:white;

margin:30px;

padding:25px;

border-radius:20px;

box-shadow:0 4px 15px rgba(0,0,0,.06);

}

.note-card h3{
color:#2344b3;
margin-bottom:15px;
}

.note-card p{
line-height:1.8;
color:#555;
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

<li><a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i>Dashboard</a></li>

<li><a href="profile.php"><i class="fa-regular fa-user"></i>Profile</a></li>

<li><a href="academic.php"><i class="fa-solid fa-book-open"></i>Academic Information</a></li>

<li><a href="courses.php" class="active"><i class="fa-solid fa-graduation-cap"></i>Courses</a></li>

<li><a href="registration.php"><i class="fa-regular fa-clipboard"></i>Course Registration</a></li>

<li><a href="payment.php"><i class="fa-regular fa-credit-card"></i>Payment</a></li>

<li><a href="result.php"><i class="fa-regular fa-file-lines"></i>Results</a></li>

</ul>

</div>

<!-- Main -->

<div class="main">

<div class="topbar">

<h2>Courses</h2>

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

<div class="page-header">

<h1>All Courses for CSE</h1>

<p>Course information</p>

</div>

<div class="table-container">

<table class="course-table">

<thead>

<tr>

<th>Code</th>
<th>Course Title</th>
<th>Credit</th>
<th>Contact Hour</th>
<th>Course Fee</th>
<th>Prerequisite</th>
<th>Corequisite</th>


</tr>

</thead>

<tbody>

<?php while($course = mysqli_fetch_assoc($result)) { ?>

<tr>
    <td><?php echo $course['course_code']; ?></td>
    <td><?php echo $course['course_title']; ?></td>
    <td><?php echo $course['credit']; ?></td>
    <td><?php echo $course['contact_hour']; ?></td>
    <td><?php echo $course['course_fee']; ?></td>
    <td><?php echo $course['prerequisite']; ?></td>
    <td><?php echo $course['corequisite']; ?></td>
   
</tr>

<?php } ?>

</tbody>

</table>

</div>

<div class="note-card">

<h3>Course Registration Note</h3>

<p>

To enroll in a lab course, students must register for the corresponding theory course in the same term. Example:

<br><br>

• CSE-2422 Computer Algorithms Lab → CSE-2421 Computer Algorithms 

<br>

• CSE-2424 Database Management Systems Lab → CSE-2423 Database Management Systems 

</p>

</div>

</div>

</div>

</body>

</html>