<?php
session_start();
include("config.php");

$id = $_SESSION['user'];

$check = mysqli_query($conn,"
SELECT *
FROM registration
WHERE student_id='$id'
AND registration_status='Pending'
");

if(mysqli_num_rows($check)>0)
{
    echo "<script>
    alert('You already have a pending registration.');
    window.location='registration.php';
    </script>";
    exit();
}

if(!isset($_POST['courses']))
{
    echo "<script>
    alert('Please select at least one course.');
    window.location='registration.php';
    </script>";
    exit();
}

$courses = $_POST['courses'];

$totalCredit = (float)($_POST['total_credit'] ?? 0);
$totalAmount = (float)($_POST['total_amount'] ?? 0);

if ($totalCredit < 12.0)
{
    echo "<script>
    alert('Registration failed. You must register for a minimum of 12 credits. You selected " . $totalCredit . " credits.');
    window.location='registration.php';
    </script>";
    exit();
}

foreach($courses as $selectedCourse)
{
    $query = mysqli_query($conn, "
        SELECT prerequisite 
        FROM course 
        WHERE course_code='$selectedCourse'
    ");

    $courseRow = mysqli_fetch_assoc($query);

    if(!empty($courseRow['prerequisite']))
    {
        $prereq = $courseRow['prerequisite'];

        
        $checkPrereq = mysqli_query($conn, "
            SELECT grade_point 
            FROM result 
            WHERE student_id='$id' 
            AND course_code='$prereq' 
            AND grade_point >= 2.00
        ");

        if(mysqli_num_rows($checkPrereq) == 0)
        {
            echo "<script>
            alert('Cannot register for ".$selectedCourse.". You have not passed its prerequisite course (".$prereq.").');
            window.location='registration.php';
            </script>";
            exit();
        }
    }
}



foreach($courses as $selectedCourse)
{
    $query = mysqli_query($conn,"
    SELECT corequisite
    FROM course
    WHERE course_code='$selectedCourse'
    ");

    $courseRow = mysqli_fetch_assoc($query);

    if(!empty($courseRow['corequisite']))
    {
        $corequisite = $courseRow['corequisite'];

        if(!in_array($corequisite, $courses))
        {
            echo "<script>
            alert('".$selectedCourse." requires the corequisite course ".$corequisite.". Please select both courses.');
            window.location='registration.php';
            </script>";
            exit();
        }
    }
}

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