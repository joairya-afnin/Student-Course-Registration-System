<?php
session_start();
include("config.php");

$id = $_SESSION['user'];

$student = mysqli_query($conn,"
SELECT student.*, academic_info.*
FROM student
JOIN academic_info
ON student.student_id=academic_info.acd_student_id
WHERE student.student_id='$id'");

$row = mysqli_fetch_assoc($student);

$sem2=mysqli_query($conn,"
SELECT result.*,course.course_title,course.credit
FROM result
JOIN course
ON result.course_code=course.course_code
WHERE result.student_id='$id'
AND result.semester=2");

$sem3=mysqli_query($conn,"
SELECT result.*,course.course_title,course.credit
FROM result
JOIN course
ON result.course_code=course.course_code
WHERE result.student_id='$id'
AND result.semester=3");


$semesterCreditQuery = mysqli_query($conn,"
SELECT SUM(course.credit) AS semester_credit
FROM result
JOIN course
ON result.course_code=course.course_code
WHERE result.student_id='$id'
AND result.semester=3
AND result.grade <> 'F'
");

$semesterCredit = mysqli_fetch_assoc($semesterCreditQuery);

if($row['current_cgpa'] >= 3.75)
{
    $standing = "Excellent";
}
elseif($row['current_cgpa'] >= 3.50)
{
    $standing = "Very Good";
}
elseif($row['current_cgpa'] >= 3.00)
{
    $standing = "Good";
}
elseif($row['current_cgpa'] >= 2.00)
{
    $standing = "Satisfactory";
}
else
{
    $standing = "Academic Probation";
}




?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Results - IIUC</title>

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
position:fixed;
height:100vh;
color:white;
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
color:white;
text-decoration:none;
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
background:#2344b3;
border-radius:50%;
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

/* Page Header */

.page-header{
padding:30px;
}

.page-header h1, .page-header h2{
margin-bottom:10px;
}

.page-header p{
color:#666;
}

/* Result Table Card Fixed */

.table-card{
background:white;
margin:0 30px;
border-radius:20px;
box-shadow:0 4px 15px rgba(0,0,0,.06);
overflow:hidden;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#2344b3;
color:white;
padding:15px;
text-align:left;
font-size:14px;
}

td{
padding:15px;
border-bottom:1px solid #eee;
font-size:14px;
}

tbody tr:hover{
background:#f8fbff;
}

/* Grade Styles */

.grade{
padding:6px 12px;
border-radius:20px;
font-size:13px;
font-weight:600;
}

.aplus{
background:#dcfce7;
color:#15803d;
}

.a{
background:#dbeafe;
color:#1d4ed8;
}

.bplus{
background:#fef3c7;
color:#b45309;
}

/* Summary Cards */

.summary-section{
display:grid;
grid-template-columns:1fr 1fr;
gap:25px;
padding:30px;
}

.summary-card{
background:white;
padding:25px;
border-radius:20px;
box-shadow:0 4px 15px rgba(0,0,0,.06);
transition:.3s;
}

.summary-card:hover{
transform:translateY(-5px);
}

.summary-card h3{
color:#2344b3;
margin-bottom:20px;
}

.summary-item{
display:flex;
justify-content:space-between;
padding:12px 0;
border-bottom:1px solid #eee;
}

.summary-item strong{
color:#2344b3;
}

/* Grading System */

.grading-card{
background:white;
margin:0 30px 30px;
padding:25px;
border-radius:20px;
box-shadow:0 4px 15px rgba(0,0,0,.06);
}

.grading-card h3{
color:#2344b3;
margin-bottom:20px;
}

.grading-grid{
display:grid;
grid-template-columns:repeat(5,1fr);
gap:15px;
}

.grade-box{
background:#f8fbff;
padding:15px;
text-align:center;
border-radius:10px;
font-weight:600;
transition:.3s;
}

.grade-box:hover{
transform:translateY(-4px);
}

/* Responsive */

@media(max-width:1000px){

.summary-section{
grid-template-columns:1fr;
}

.grading-grid{
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

<li><a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a></li>

<li><a href="profile.php"><i class="fa-regular fa-user"></i> Profile</a></li>

<li><a href="academic.php"><i class="fa-solid fa-book-open"></i> Academic Information</a></li>

<li><a href="courses.php"><i class="fa-solid fa-graduation-cap"></i> Courses</a></li>

<li><a href="registration.php"><i class="fa-regular fa-clipboard"></i> Course Registration</a></li>

<li><a href="payment.php"><i class="fa-regular fa-credit-card"></i> Payment</a></li>

<li><a href="result.php" class="active"><i class="fa-regular fa-file-lines"></i> Results</a></li>

</ul>

</div>

<!-- Main -->

<div class="main">

<!-- Topbar -->

<div class="topbar">

<h2>Previous Semester Results</h2>

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

<h1>Semester 2 Results</h1>

<p>Academic results of completed Semester 2 courses.</p>

</div>

<!-- Semester 2 Results Table -->

<div class="table-card">

<table>

<thead>

<tr>

<th>Course Code</th>
<th>Course Title</th>
<th>Credit</th>
<th>Grade</th>
<th>Grade Point</th>

</tr>

</thead>

<tbody>

<?php while($r=mysqli_fetch_assoc($sem2)){ ?>

<tr>

<td><?php echo $r['course_code']; ?></td>

<td><?php echo $r['course_title']; ?></td>

<td><?php echo $r['credit']; ?></td>

<td>

<?php

$class="a";

if($r['grade']=="A+")
$class="aplus";

elseif($r['grade']=="B+")
$class="bplus";

?>

<span class="grade <?php echo $class; ?>">
<?php echo $r['grade']; ?>
</span>

</td>

<td><?php echo $r['grade_point']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<!-- Semester 3 Results Header -->

<div class="page-header">

<h2>Semester 3 Results</h2>

<p>Academic results of completed Semester 3 courses.</p>

</div>

<!-- Semester 3 Results Table -->

<div class="table-card">

<table>

<thead>

<tr>
<th>Course Code</th>
<th>Course Title</th>
<th>Credit</th>
<th>Grade</th>
<th>Grade Point</th>
</tr>

</thead>

<tbody>

<?php while($r=mysqli_fetch_assoc($sem3)){ ?>

<tr>

<td><?php echo $r['course_code']; ?></td>

<td><?php echo $r['course_title']; ?></td>

<td><?php echo $r['credit']; ?></td>

<td>

<?php

$class="a";

if($r['grade']=="A+")
$class="aplus";

elseif($r['grade']=="B+")
$class="bplus";

?>

<span class="grade <?php echo $class; ?>">
<?php echo $r['grade']; ?>
</span>

</td>

<td><?php echo $r['grade_point']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<!-- Summary Cards -->

<div class="summary-section">

<div class="summary-card">

<h3>3rd Semester Summary</h3>

<div class="summary-item">
<span>Semester GPA</span>
<strong><?php echo $row['current_gpa']; ?></strong>
</div>

<div class="summary-item">
<span>Total Credits Earned</span>
<strong><?php echo $semesterCredit['semester_credit']; ?></strong>
</div>


<div class="summary-item">
<span>Academic Standing</span>
<strong><?php echo $standing; ?></strong>
</div>

</div>

<div class="summary-card">

<h3>Cumulative Academic Record</h3>

<div class="summary-item">
<span>Current Semester</span>
<strong><?php echo $row['current_semester']; ?>th</strong>
</div>

<div class="summary-item">
<span>Current CGPA</span>
<strong><?php echo $row['current_cgpa']; ?></strong>
</div>

<div class="summary-item">
<span>Completed Credits</span>
<strong><?php echo $row['completed_credits']; ?></strong>
</div>

<div class="summary-item">
<span>Program</span>
<strong>CSE</strong>
</div>

</div>

</div>

<!-- Grading System -->

<div class="grading-card">

<h3>IIUC Grading System</h3>

<div class="grading-grid">

<div class="grade-box">A+<br>80-100</div>
<div class="grade-box">A<br>75-79</div>
<div class="grade-box">A-<br>70-74</div>
<div class="grade-box">B+<br>65-69</div>
<div class="grade-box">B<br>60-64</div>
<div class="grade-box">B-<br>55-59</div>
<div class="grade-box">C+<br>50-54</div>
<div class="grade-box">C<br>45-49</div>
<div class="grade-box">D<br>40-44</div>
<div class="grade-box">F<br>0-39</div>

</div>

</div>

</div>

</div>

</body>

</html>