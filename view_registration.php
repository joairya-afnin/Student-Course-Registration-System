<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location:index.php");
    exit();
}

include("config.php");

if(!isset($_GET['id']))
{
    header("Location:manage-registration.php");
    exit();
}

$registration_id = $_GET['id'];

// Registration + Student Information
$sql = "SELECT registration.*, student.*
        FROM registration
        JOIN student
        ON registration.student_id = student.student_id
        WHERE registration.registration_id='$registration_id'";

$result = mysqli_query($conn,$sql);
$reg = mysqli_fetch_assoc($result);

// Academic Information
$academic = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM academic_info
WHERE acd_student_id='".$reg['student_id']."'
"));

// Payment Information
$payment = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM pay_order
WHERE registration_id='$registration_id'
"));


// Registered Courses
$courses = mysqli_query($conn,"
SELECT course.course_code,
course.course_title,
course.credit
FROM registration_course
JOIN course
ON registration_course.course_code=course.course_code
WHERE registration_course.registration_id='$registration_id'
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Registration Details - IIUC Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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
background:#f8fafc;
color:#334155;
}

.container{
width:95%;
max-width:1400px;
margin:30px auto;
}

.top-banner{

background:linear-gradient(135deg,#1e40af,#3b82f6);
color:white;
padding:30px;
border-radius:20px;
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
box-shadow:0 10px 30px rgba(30,64,175,.25);

}

.banner-left{
display:flex;
align-items:center;
gap:20px;
}

.icon-box{

width:65px;
height:65px;
border-radius:15px;
background:rgba(255,255,255,.2);

display:flex;
justify-content:center;
align-items:center;

font-size:30px;

}

.banner-left h1{
font-size:30px;
margin-bottom:6px;
}

.banner-left p{
opacity:.95;
font-size:15px;
}

.banner-right{

background:rgba(255,255,255,.15);
padding:15px 25px;
border-radius:14px;
text-align:center;

}

.banner-right small{
display:block;
opacity:.8;
margin-bottom:5px;
}

.banner-right strong{
font-size:18px;
}

.card{

background:white;
border-radius:18px;
box-shadow:0 8px 25px rgba(0,0,0,.07);
padding:25px;
margin-bottom:25px;

}

.section-title{

font-size:20px;
color:#1e40af;
margin-bottom:20px;
display:flex;
align-items:center;
gap:10px;

}

.info-grid{

display:grid;
grid-template-columns:repeat(2,1fr);
gap:18px;

}

.info-item{

background:#f8fafc;
padding:16px;
border-radius:12px;
border-left:4px solid #2563eb;

}

.info-item span{

display:block;
font-size:13px;
color:#64748b;
margin-bottom:6px;

}

.info-item strong{

font-size:16px;
color:#0f172a;

}

.table-card{

background:white;
border-radius:18px;
overflow:hidden;
box-shadow:0 8px 25px rgba(0,0,0,.07);
margin-bottom:25px;

}

.table-title{

padding:20px 25px;
font-size:20px;
font-weight:600;
color:#1e40af;

}

table{

width:100%;
border-collapse:collapse;

}

thead{

background:linear-gradient(90deg,#1e40af,#3b82f6);
color:white;

}

th{

padding:16px;
text-align:left;
font-size:13px;
text-transform:uppercase;

}

td{

padding:16px;
border-bottom:1px solid #eef2f7;

}

tbody tr:hover{

background:#f8fafc;

}

.badge{

display:inline-block;
padding:5px 12px;
border-radius:20px;
font-size:12px;
font-weight:600;

}

.badge-approved{

background:#d1fae5;
color:#065f46;

}

.badge-pending{

background:#fef3c7;
color:#92400e;

}

.badge-rejected{

background:#fee2e2;
color:#b91c1c;

}

.button-area{

display:flex;
gap:15px;
margin-top:25px;

}

.btn{

padding:13px 24px;
border-radius:10px;
text-decoration:none;
font-weight:600;
color:white;
transition:.3s;

}

.btn:hover{

transform:translateY(-2px);

}

.approve{

background:#16a34a;

}

.reject{

background:#dc2626;

}

.back{

background:#2563eb;

}

</style>

</head>

<body>

<div class="container">

<div class="top-banner">

<div class="banner-left">

<div class="icon-box">
📋
</div>

<div>

<h1>Registration Details</h1>

<p>Review student registration, payment information and registered courses.</p>

</div>

</div>

<div class="banner-right">

<small>Registration ID</small>

<strong><?php echo $reg['registration_id']; ?></strong>

</div>

</div>

<!-- Student Information -->

<div class="card">

<h2 class="section-title">
<i class="fas fa-user-graduate"></i>
Student Information
</h2>

<div class="info-grid">

<div class="info-item">
<span>Student ID</span>
<strong><?php echo $reg['student_id']; ?></strong>
</div>

<div class="info-item">
<span>Student Name</span>
<strong><?php echo $reg['name']; ?></strong>
</div>

<div class="info-item">
<span>Phone Number</span>
<strong><?php echo $reg['phone']; ?></strong>
</div>

<div class="info-item">
<span>Section</span>
<strong><?php echo $reg['section']; ?></strong>
</div>

<div class="info-item">
<span>Program</span>
<strong><?php echo $reg['program']; ?></strong>
</div>

<div class="info-item">
<span>Father's Name</span>
<strong><?php echo $reg['father_name']; ?></strong>
</div>

<div class="info-item">
<span>Mother's Name</span>
<strong><?php echo $reg['mother_name']; ?></strong>
</div>

</div>

</div>

<!-- Academic Information -->

<div class="card">

<h2 class="section-title">
<i class="fas fa-book-open"></i>
Academic Information
</h2>

<div class="info-grid">

<div class="info-item">
<span>Current Semester</span>
<strong><?php echo $academic['current_semester']; ?></strong>
</div>

<div class="info-item">
<span>Current GPA</span>
<strong><?php echo $academic['current_gpa']; ?></strong>
</div>

<div class="info-item">
<span>Current CGPA</span>
<strong><?php echo $academic['current_cgpa']; ?></strong>
</div>

</div>

</div>

<!-- Registration Information -->

<div class="card">

<h2 class="section-title">
<i class="fas fa-clipboard-check"></i>
Registration Information
</h2>

<div class="info-grid">

<div class="info-item">
<span>Registration ID</span>
<strong><?php echo $reg['registration_id']; ?></strong>
</div>

<div class="info-item">
<span>Semester</span>
<strong><?php echo $reg['semester']; ?></strong>
</div>

<div class="info-item">
<span>Session</span>
<strong><?php echo $reg['session']; ?></strong>
</div>

<div class="info-item">
<span>Registration Date</span>
<strong><?php echo $reg['registration_date']; ?></strong>
</div>

<div class="info-item">
<span>Total Credit</span>
<strong><?php echo $reg['total_credit']; ?></strong>
</div>

<div class="info-item">
<span>Total Amount</span>
<strong>BDT <?php echo number_format($reg['total_amount']); ?></strong>
</div>

<div class="info-item">
<span>Registration Status</span>

<strong>

<?php

if($reg['registration_status']=="Approved")
{
echo "<span class='badge badge-approved'>Approved</span>";
}
else if($reg['registration_status']=="Rejected")
{
echo "<span class='badge badge-rejected'>Rejected</span>";
}
else
{
echo "<span class='badge badge-pending'>Pending</span>";
}

?>

</strong>

</div>

</div>

</div>

<!-- Registered Courses -->

<div class="table-card">

<div class="table-title">
Registered Courses
</div>

<table>

<thead>

<tr>

<th>Course Code</th>
<th>Course Title</th>
<th>Credit</th>

</tr>

</thead>

<tbody>

<?php
while($course=mysqli_fetch_assoc($courses))
{
?>

<tr>

<td><?php echo $course['course_code']; ?></td>

<td><?php echo $course['course_title']; ?></td>

<td><?php echo $course['credit']; ?></td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

<!-- Payment Information -->

<div class="card">

<h2 class="section-title">
<i class="fas fa-credit-card"></i>
Payment Information
</h2>

<div class="info-grid">

<div class="info-item">
<span>Pay Order Code</span>
<strong>
<?php
echo $payment ? $payment['pay_order_code'] : "Not Submitted";
?>
</strong>
</div>

<div class="info-item">
<span>Transaction ID</span>
<strong>
<?php
echo $payment ? $payment['transaction_id'] : "Not Submitted";
?>
</strong>
</div>

<div class="info-item">
<span>Amount</span>
<strong>
<?php
if($payment)
{
    echo "BDT ".number_format($payment['amount']);
}
else
{
    echo "-";
}
?>
</strong>
</div>

<div class="info-item">
<span>Payment Date</span>
<strong>
<?php
echo $payment ? $payment['payment_date'] : "-";
?>
</strong>
</div>

<div class="info-item">
<span>Verification Status</span>

<strong>

<?php

if(!$payment)
{
    echo "<span class='badge badge-pending'>Not Submitted</span>";
}
else if($payment['verification_status']=="Verified")
{
    echo "<span class='badge badge-approved'>Verified</span>";
}
else if($payment['verification_status']=="Rejected")
{
    echo "<span class='badge badge-rejected'>Rejected</span>";
}
else
{
    echo "<span class='badge badge-pending'>Pending</span>";
}

?>

</strong>
</div>

</div>

</div>

<div class="button-area">

<?php
if($reg['registration_status']=="Pending")
{
?>

<a class="btn approve"
href="approve_registration.php?id=<?php echo $reg['registration_id']; ?>"
onclick="return confirm('Approve this registration?')">
<i class="fas fa-check"></i> Approve
</a>

<a class="btn reject"
href="reject_registration.php?id=<?php echo $reg['registration_id']; ?>"
onclick="return confirm('Reject this registration?')">
<i class="fas fa-times"></i> Reject
</a>

<?php
}
?>

<a class="btn back"
href="manage-registration.php">
<i class="fas fa-arrow-left"></i> Back
</a>

</div>

</div>

</body>

</html>