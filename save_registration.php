<?php
session_start();
include("config.php");

$id = $_SESSION['user'];

if(!isset($_POST['courses']))
{
    echo "<script>
    alert('Please select at least one course.');
    window.location='registration.php';
    </script>";
    exit();
}

$courses = $_POST['courses'];
$totalCredit = $_POST['total_credit'];
$totalAmount = $_POST['total_amount'];

$student = mysqli_query($conn,"
SELECT current_semester
FROM academic_info
WHERE acd_student_id='$id'
");

$row = mysqli_fetch_assoc($student);

$currentSemester = $row['current_semester'];

$session = "Spring-2026";

$result = mysqli_query($conn,"
SELECT registration_id
FROM registration
ORDER BY registration_id DESC
LIMIT 1
");

if(mysqli_num_rows($result)>0)
{
    $last = mysqli_fetch_assoc($result);

    $number = (int)substr($last['registration_id'],3);
    $number++;

    $registrationID = "REG".str_pad($number,3,"0",STR_PAD_LEFT);
}
else
{
    $registrationID = "REG001";
}

$status = "Pending";

$date = date("Y-m-d");

$insert = mysqli_query($conn,"
INSERT INTO registration
(
registration_id,
student_id,
semester,
session,
total_credit,
total_amount,
registration_status,
registration_date
)
VALUES
(
'$registrationID',
'$id',
'$currentSemester',
'$session',
'$totalCredit',
'$totalAmount',
'$status',
'$date'
)
");

if(!$insert)
{
    die(mysqli_error($conn));
}

foreach($courses as $course)
{

mysqli_query($conn,"
INSERT INTO registration_course
(
registration_id,
course_code
)
VALUES
(
'$registrationID',
'$course'
)
");

}

echo "<script>
alert('Registration submitted successfully. Waiting for admin approval.');
window.location='registration.php';
</script>";

?>