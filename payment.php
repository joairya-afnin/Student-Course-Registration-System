<?php
session_start();
include("config.php");

$id = $_SESSION['user'];

$student = mysqli_query($conn,"
SELECT student.*, registration.*
FROM student
LEFT JOIN registration
ON student.student_id = registration.student_id
WHERE student.student_id='$id'
ORDER BY registration.registration_date DESC
LIMIT 1
");

$row = mysqli_fetch_assoc($student);
$payment = mysqli_query($conn,"
SELECT *
FROM pay_order
WHERE registration_id='".$row['registration_id']."'
");

$pay = mysqli_fetch_assoc($payment);

if(empty($row['registration_id']))
{
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
</head>
<body>

<script>
alert("You have not registered any courses yet.");
window.location="registration.php";
</script>

</body>
</html>

<?php
exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Payment - IIUC</title>

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

/* Header */

.page-header{
padding:30px;
}

.page-header h1{
margin-bottom:10px;
}

.page-header p{
color:#666;
}

/* Payment Layout */

.payment-wrapper{
padding:0 30px 30px;
}

.payment-card{

background:white;

border-radius:20px;

padding:30px;

box-shadow:0 4px 15px rgba(0,0,0,.06);

max-width:800px;

}

/* Summary */

.summary-grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
margin-bottom:30px;
}

.summary-box{

background:#f8fbff;

padding:20px;

border-radius:12px;

border-left:4px solid #2344b3;

}

.summary-box h4{
color:#666;
margin-bottom:8px;
}

.summary-box h2{
color:#2344b3;
}

/* Form */

.form-group{
margin-bottom:20px;
}

.form-group label{
display:block;
margin-bottom:8px;
font-weight:600;
}

.form-group input,
.form-group select{

width:100%;

padding:12px;

border:1px solid #ddd;

border-radius:10px;

font-size:15px;

}

.form-group input:focus,
.form-group select:focus{
outline:none;
border-color:#2344b3;
}

/* Button */

.submit-btn{

width:100%;

padding:14px;

background:#2344b3;

color:white;

border:none;

border-radius:10px;

font-size:16px;

font-weight:600;

cursor:pointer;

transition:.3s;

}

.submit-btn:hover{
background:#1c3898;
}

/* Notice */

.notice{

margin-top:25px;

padding:18px;

background:#eef4ff;

border-left:4px solid #2344b3;

border-radius:10px;

color:#555;

line-height:1.6;

}

.status{
font-weight:700;
color:#f59e0b;
}

@media(max-width:768px){

.summary-grid{
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

<li><a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a></li>

<li><a href="profile.php"><i class="fa-regular fa-user"></i> Profile</a></li>

<li><a href="academic.php"><i class="fa-solid fa-book-open"></i> Academic Information</a></li>

<li><a href="courses.php"><i class="fa-solid fa-graduation-cap"></i> Courses</a></li>

<li><a href="registration.php"><i class="fa-regular fa-clipboard"></i> Course Registration</a></li>

<li><a href="payment.php" class="active"><i class="fa-regular fa-credit-card"></i> Payment</a></li>

<li><a href="result.php"><i class="fa-regular fa-file-lines"></i> Results</a></li>

</ul>

</div>

<!-- Main -->

<div class="main">

<div class="topbar">

<h2>Payment</h2>

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

<div class="page-header">

<h1>Course Registration Payment</h1>

<?php
if($row['registration_status']=="Approved")
{
?>
<p>Your registration has been approved successfully.</p>
<?php
}
else
{
?>
<p>Submit your payment information for verification.</p>
<?php
}
?>

</div>

<div class="payment-wrapper">

<div class="payment-card">

<!-- Summary -->

<div class="summary-grid">

<div class="summary-box">
<h4>Registration ID</h4>
<h2><?php echo $row['registration_id']; ?></h2>
</div>

<div class="summary-box">
<h4>Total Amount</h4>
<h2><?php echo $row['total_amount']; ?> BDT</h2>
</div>

</div>

<!-- Form -->
 <?php
if($row['registration_status']=="Approved")
{
?>

<div style="
background:#dcfce7;
border:2px solid #22c55e;
padding:20px;
border-radius:12px;
color:#166534;
margin-bottom:20px;
">

<h2>✅ Payment Verified</h2>

<p>Your payment has been verified by the Academic Administration Office.</p>

<p>Your course registration has been approved successfully.</p>

</div>

<?php
}
else
{
?>

<form action="save_payment.php" method="POST">

<input type="hidden"
name="registration_id"
value="<?php echo $row['registration_id']; ?>">

<input type="hidden"
name="amount"
value="<?php echo $row['total_amount']; ?>">

<?php

$getLast = mysqli_query($conn,"
SELECT pay_order_code
FROM pay_order
ORDER BY pay_order_code DESC
LIMIT 1
");

if(mysqli_num_rows($getLast)>0)
{
    $last = mysqli_fetch_assoc($getLast);

    $number = intval(substr($last['pay_order_code'],3));

    $newPayOrder = "PAY".str_pad($number+1,3,"0",STR_PAD_LEFT);
}
else
{
    $newPayOrder = "PAY001";
}

?>

<div class="form-group">
<label>Pay Order Code</label>

<input
type="text"
name="pay_order_code"
value="<?php echo $newPayOrder; ?>"
readonly>

</div>

<div class="form-group">
<label>Transaction ID</label>
<input type="text" name="transaction_id" required>
</div>

<div class="form-group">
<label>Payment Date</label>
<input type="date" name="payment_date" required>
</div>

<button type="submit" class="submit-btn">
Submit Payment
</button>

</form>

<?php
}
?>

<!-- Notice -->



<?php
if($row['registration_status']=="Approved")
{
?>

<div class="notice">

<strong>Payment Status:</strong>

<span style="color:green;font-weight:bold;">
Verified
</span>

<br><br>

Your payment has been verified and your registration has been approved.

</div>

<?php
}
else
{
?>

<div class="notice">

<strong>Current Status:</strong>

<span class="status">
<?php echo $row['registration_status']; ?>
</span>

<br><br>

Your payment information will be reviewed by the Academic Administration Office.
After verification, your registration will be approved or rejected.

</div>

<?php
}
?>

</div>

</div>

</div>

</div>

</body>

</html>