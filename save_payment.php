<?php
session_start();
include("config.php");

if($_SERVER["REQUEST_METHOD"]=="POST")
{

$registrationID=$_POST['registration_id'];
$payOrderCode=$_POST['pay_order_code'];
$amount=$_POST['amount'];
$paymentDate=$_POST['payment_date'];
$transactionID=$_POST['transaction_id'];

$check=mysqli_query($conn,"
SELECT *
FROM pay_order
WHERE registration_id='$registrationID'
AND verification_status IN ('Pending','Verified')
");

if(mysqli_num_rows($check)>0)
{
echo "<script>
alert('Payment has already been submitted.');
window.location='payment.php';
</script>";
exit();
}

mysqli_query($conn,"
INSERT INTO pay_order
(
pay_order_code,
registration_id,
amount,
payment_date,
transaction_id,
verification_status
)
VALUES
(
'$payOrderCode',
'$registrationID',
'$amount',
'$paymentDate',
'$transactionID',
'Pending'
)
");

echo "<script>
alert('Payment submitted successfully. Waiting for verification.');
window.location='payment.php';
</script>";

}
?>