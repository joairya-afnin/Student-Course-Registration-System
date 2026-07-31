<?php
session_start();
include("config.php");

$id = $_SESSION['user'];

$sql = "SELECT student.*, academic_info.*
FROM student
JOIN academic_info
ON student.student_id = academic_info.acd_student_id
WHERE student.student_id = '$id'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$currentSemester = $row['current_semester'];
$lastGPA = $row['current_gpa'];

if ($currentSemester == 1) {
    $maxCredit = 24;
} else if ($lastGPA >= 3.75) {
    $maxCredit = 28;
} else if ($lastGPA >= 3.50) {
    $maxCredit = 26;
} else if ($lastGPA >= 2.75) {
    $maxCredit = 24;
} else if ($lastGPA >= 2.25) {
    $maxCredit = 22;
} else if ($lastGPA >= 2.00) {
    $maxCredit = 20;
} else if ($lastGPA >= 1.70) {
    $maxCredit = 15;
} else {
    $maxCredit = 12;
}

$registration = mysqli_query($conn, "
SELECT *
FROM registration
WHERE student_id = '$id'
ORDER BY registration_date DESC
LIMIT 1
");

$reg = mysqli_fetch_assoc($registration);

if ($reg) {
    $registeredCourses = mysqli_query($conn, "
    SELECT
    course.course_code,
    course.course_title,
    course.credit
    FROM registration_course
    JOIN course
    ON registration_course.course_code = course.course_code
    WHERE registration_course.registration_id = '" . $reg['registration_id'] . "'
    ");
}


$courses = mysqli_query($conn, "
SELECT
course.*,

CASE
WHEN result.grade = 'F' THEN 'Retake'
WHEN result.grade_point <= 2.75 THEN 'Improvement'
ELSE 'Regular'
END AS course_type,

CASE
WHEN result.grade = 'F'
OR result.grade_point <= 2.75
THEN COALESCE(course.course_fee, 0)/2
ELSE COALESCE(course.course_fee, 0)
END AS display_fee

FROM course

LEFT JOIN result
ON result.result_id =
(
SELECT MAX(r2.result_id)
FROM result r2
WHERE r2.student_id='$id'
AND r2.course_code=course.course_code
)

WHERE
result.course_code IS NULL
OR result.grade = 'F'
OR result.grade_point <= 2.75

ORDER BY

CASE
WHEN result.course_code IS NULL THEN 1
ELSE 2
END,

course.course_code
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Course Registration - IIUC</title>

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

/* Page */

.page-header{
padding:30px;
}

.page-header h1{
margin-bottom:10px;
}

.page-header p{
color:#666;
}

/* Side-by-Side Layout */

.registration-layout{
display:grid;
grid-template-columns:3.2fr 1.2fr; 
gap:30px;
padding:0 30px 30px;
align-items:start;
}

/* Table Card */

.table-card{
background:white;
border-radius:20px;
box-shadow:0 4px 15px rgba(0,0,0,.06);
overflow:hidden;
}

table{
width:100%;
border-collapse:collapse;
}

th, td{
padding:15px;
text-align:left;
font-size:14px;
}

th{
background:#2344b3;
color:white;
}

td{
border-bottom:1px solid #eee;
}

tbody tr:hover{
background:#f8fbff;
}

input[type="checkbox"]{
width:18px;
height:18px;
cursor:pointer;
}

/* Summary Card */

.summary-card{
background:white;
padding:25px;
border-radius:20px;
box-shadow:0 4px 15px rgba(0,0,0,.06);
position:sticky;
top:30px;
}

.summary-card h2{
color:#2344b3;
margin-bottom:20px;
font-size:20px;
}

.summary-item{
display:flex;
justify-content:space-between;
padding:12px 0;
border-bottom:1px solid #eee;
font-size:14px;
}

.summary-item strong{
color:#2344b3;
}

.selected-list{
margin-top:20px;
}

.selected-list h3{
font-size:15px;
color:#333;
}

.selected-list ul{
padding-left:18px;
margin-top:10px;
}

.selected-list li{
margin-bottom:8px;
font-size:14px;
}

.btn{
width:100%;
padding:12px;
border:none;
border-radius:10px;
font-weight:600;
cursor:pointer;
margin-top:15px;
transition:.3s;
}

.register-btn{
background:#2344b3;
color:white;
}

.register-btn:hover{
background:#1d3998;
}

.reset-btn{
background:#e5e7eb;
}

.reset-btn:hover{
background:#d1d5db;
}

.note{
margin-top:20px;
background:#eef4ff;
padding:15px;
border-left:4px solid #2344b3;
border-radius:10px;
font-size:13px;
line-height:1.5;
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

<li><a href="registration.php" class="active"><i class="fa-regular fa-clipboard"></i> Course Registration</a></li>

<li><a href="payment.php"><i class="fa-regular fa-credit-card"></i> Payment</a></li>

<li><a href="result.php"><i class="fa-regular fa-file-lines"></i> Results</a></li>

</ul>

</div>

<!-- Main -->

<div class="main">

<div class="topbar">

<h2>Course Registration</h2>

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

<h1>Semester Course Registration</h1>

<p>Select courses for registration.</p>

</div>

<?php
if ($reg && $reg['registration_status'] == "Pending") {
?>
<div style="
background:#fef3c7;
border:2px solid #f59e0b;
color:#92400e;
padding:20px;
margin:0 30px 30px;
border-radius:12px;
">

<h2 style="margin-bottom:10px;">⏳ Registration Submitted</h2>

<p>Your registration has been submitted successfully.</p>

<p>Please wait for the Academic Office to verify your payment and approve your registration.</p>

<p><b>Registration ID:</b> <?php echo $reg['registration_id']; ?></p>

<p><b>Status:</b> Pending Approval</p>

</div>
<?php
}
?>

<?php
if ($reg && $reg['registration_status'] == "Rejected") {
?>
<div style="
background:#fee2e2;
border:2px solid #dc2626;
color:#991b1b;
padding:20px;
margin:0 30px 30px;
border-radius:12px;
">

<h2>❌ Registration Rejected</h2>

<p>Your previous registration request has been rejected by the Academic Office.</p>

<p>You may select courses again and submit a new registration.</p>

</div>
<?php
}
?>

<?php
if ($reg && $reg['registration_status'] == "Approved") {
?>
<div style="
background:#dcfce7;
border:2px solid #22c55e;
color:#166534;
padding:20px;
margin:0 30px 30px;
border-radius:12px;
font-size:16px;
">

<h2 style="margin-bottom:10px;">✅ Registration Completed</h2>

<p>Your course registration has been approved by the Academic Office.</p>

<p><b>Registration ID:</b> <?php echo $reg['registration_id']; ?></p>

<p><b>Semester:</b> <?php echo $reg['semester']; ?></p>

<p><b>Status:</b> Approved</p>

<h3 style="
margin-top:30px;
margin-bottom:15px;
color:#166534;
">
Confirmed Courses
</h3>

<table style="
width:100%;
border-collapse:collapse;
background:white;
border-radius:10px;
overflow:hidden;
">

<tr style="background:#16a34a;color:white;">
<th style="padding:12px;">Course Code</th>
<th style="padding:12px;">Course Title</th>
<th style="padding:12px;">Credit</th>
<th style="padding:12px;">Status</th>
</tr>

<?php
while ($c = mysqli_fetch_assoc($registeredCourses)) {
?>

<tr>

<td style="padding:12px;border-bottom:1px solid #eee;">
<?php echo $c['course_code']; ?>
</td>

<td style="padding:12px;border-bottom:1px solid #eee;">
<?php echo $c['course_title']; ?>
</td>

<td style="padding:12px;border-bottom:1px solid #eee;text-align:center;">
<?php echo $c['credit']; ?>
</td>

<td style="padding:12px;border-bottom:1px solid #eee;">

<span style="
background:#dcfce7;
color:#166534;
padding:6px 12px;
border-radius:20px;
font-weight:bold;
">
✓ Confirmed
</span>

</td>

</tr>

<?php
}
?>

</table>

</div>

<?php
exit();
}
?>

<?php
if ($reg && ($reg['registration_status'] == "Pending" || $reg['registration_status'] == "Approved")) {
    echo "</div></div></body></html>";
    exit();
}
?>

<form action="save_registration.php" method="POST">

<div class="registration-layout">

<!-- Course Table -->

<div class="table-card">

<table>

<thead>

<tr>
<th>Select</th>
<th>Course Code</th>
<th>Course Title</th>
<th>Credit</th>
<th>Fee</th>
<th>Prerequisite</th>
<th>Corequisite</th>
</tr>

</thead>

<tbody>

<?php
while ($course = mysqli_fetch_assoc($courses)) {

    static $suggestionShown = false;

    if (!$suggestionShown &&
    ($course['course_type'] == "Retake" || $course['course_type'] == "Improvement")) {
?>
<tr>
<td colspan="7"
style="
background:#fff3cd;
font-weight:bold;
color:#856404;
padding:15px;
font-size:16px;
">
Suggested for Improvement and Retake
</td>
</tr>
<?php
        $suggestionShown = true;
    }

    $disable = "";
    $isDisabled = false;

    // PREREQUISITE CHECK LOGIC
    if (!empty($course['prerequisite'])) {
        $prereqCode = $course['prerequisite'];

        $checkPrereq = mysqli_query($conn, "
            SELECT grade_point 
            FROM result 
            WHERE student_id = '$id' 
            AND course_code = '$prereqCode' 
            AND grade_point >= 2.00
        ");

        if (mysqli_num_rows($checkPrereq) == 0) {
            $disable = "disabled";
            $isDisabled = true;
        }
    }
?>

<tr>

<td>

<input
type="checkbox"
class="course"
name="courses[]"
value="<?php echo $course['course_code'];?>"
<?php echo $disable; ?>
data-credit="<?php echo floatval($course['credit']);?>"
data-fee="<?php echo floatval($course['display_fee']);?>"
data-name="<?php echo $course['course_code'];?>"
<?php if ($isDisabled) echo "style='cursor: not-allowed;'"; ?>>

</td>

<td <?php if ($isDisabled) echo "style='color:#aaa;'"; ?>><?php echo $course['course_code']; ?></td>

<td <?php if ($isDisabled) echo "style='color:#aaa;'"; ?>><?php echo $course['course_title']; ?></td>

<td><?php echo $course['credit']; ?></td>

<td>

<?php
if ($course['course_type'] == "Regular") {
    echo number_format($course['display_fee'],0);
} else {
   echo "<span style='color:#16a34a;font-weight:bold;'>"
. number_format($course['display_fee'],0)
. " </span>";
}
?>

</td>

<td>
<?php 
if (!empty($course['prerequisite'])) {
    if ($isDisabled) {
        echo "<span style='color:#dc2626;font-weight:bold;'>" . $course['prerequisite'] . " (Not Cleared)</span>";
    } else {
        echo "<span style='color:#16a34a;'>" . $course['prerequisite'] . " (Passed)</span>";
    }
}
?>
</td>

<td><?php echo $course['corequisite']; ?></td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

<!-- Summary Card on Right -->

<div class="summary-card">

<h2>Registration Summary</h2>

<div class="summary-item">
<span>Registration ID</span>
<strong style="font-size:12px;">Generated after submit</strong>
</div>

<div class="summary-item">
<span>Total Courses</span>
<strong id="courseCount">0</strong>
</div>

<div class="summary-item">
<span>Total Credits</span>
<strong id="totalCredits">0</strong>
</div>

<div class="summary-item">
<span>Maximum Allowed</span>
<strong><?php echo $maxCredit; ?></strong>
</div>

<div class="summary-item">
<span>Total Amount</span>
<strong id="totalAmount">0 BDT</strong>
</div>

<div class="selected-list">

<h3>Selected Courses</h3>

<ul id="selectedCourses"></ul>

<input type="hidden"
name="total_credit"
id="hiddenCredit">

<input type="hidden"
name="total_amount"
id="hiddenAmount">


<button type="submit" class="btn register-btn">
Register Courses
</button>


<button type="button" class="btn reset-btn" onclick="location.reload()">
Reset Selection
</button>

<div class="note">

<p style="margin-bottom:8px;">
<b>Note:</b> You must select a <strong>minimum of 12 credits</strong> and a maximum of <strong><?php echo $maxCredit; ?> credits</strong>.
</p>


</div>

</div>

</div>

</div>

</form>

</div>

</div>

<script>

const checkboxes = document.querySelectorAll('.course');

const totalCredits = document.getElementById('totalCredits');
const totalAmount = document.getElementById('totalAmount');
const courseCount = document.getElementById('courseCount');
const selectedCourses = document.getElementById('selectedCourses');

checkboxes.forEach(function(box){

box.addEventListener("change", function(){

updateSummary.call(this);

});

});

function updateSummary(){

let credits = 0;
let amount = 0;
let count = 0;

selectedCourses.innerHTML = '';

checkboxes.forEach(box => {

if(box.checked && !box.disabled){

    let cCredit = parseFloat(box.dataset.credit) || 0;
    let cFee = parseFloat(box.dataset.fee) || 0;

    credits += cCredit;
    amount += cFee;
    count++;

    let li = document.createElement('li');
    li.textContent = box.dataset.name;
    selectedCourses.appendChild(li);

}

});

if(credits > <?php echo $maxCredit; ?>)
{
alert("Maximum allowed credit is <?php echo $maxCredit; ?>");

if(this) this.checked = false;

updateSummary();

return;
}

totalCredits.textContent = credits;
totalAmount.textContent = amount.toLocaleString() + ' BDT';
courseCount.textContent = count;

document.getElementById("hiddenCredit").value = credits;
document.getElementById("hiddenAmount").value = amount;

}

// PREVENT SUBMISSION IF CREDITS ARE LESS THAN 12
document.querySelector('form').addEventListener('submit', function(e) {

    let credits = parseFloat(document.getElementById("hiddenCredit").value) || 0;

    if (credits < 12) {
        e.preventDefault(); // Stop form from submitting
        alert("Minimum credit requirement is 12. You have selected " + credits + " credits.");
    }

});

</script>

</body>
</html>