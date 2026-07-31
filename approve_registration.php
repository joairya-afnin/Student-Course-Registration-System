<?php

include("config.php");

$id = $_GET['id'];

mysqli_query($conn,"
UPDATE registration
SET registration_status='Approved'
WHERE registration_id='$id'
");

mysqli_query($conn,"
UPDATE pay_order
SET verification_status='Verified'
WHERE registration_id='$id'
");

header("Location: manage-registration.php");

?>