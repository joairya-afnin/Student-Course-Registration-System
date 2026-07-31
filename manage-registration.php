<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: index.php");
    exit();
}

include("config.php");

$id = $_SESSION['admin'];
$sql = "SELECT * FROM user WHERE user_id='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Dashboard Statistics
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM registration"));

$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM registration WHERE registration_status='Pending'"));

$approved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM registration WHERE registration_status='Approved'"));

$rejected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM registration WHERE registration_status='Rejected'"));

// Registration List
$registrations = mysqli_query($conn,"
SELECT
registration.*,
student.name,
pay_order.verification_status
FROM registration
JOIN student
ON registration.student_id=student.student_id
LEFT JOIN pay_order
ON registration.registration_id = pay_order.registration_id
ORDER BY registration.registration_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course Registrations - IIUC Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar */
        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #1e40af, #1e3a8a);
            color: white;
            padding-top: 30px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 10;
        }
        .logo-area {
            padding: 0 28px 40px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo { width: 55px; }

        .nav li { list-style: none; }
        .nav li a {
            padding: 16px 28px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-weight: 500;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        .nav li a:hover, 
        .nav li.active a {
            background: rgba(255,255,255,0.15);
            border-left: 4px solid #60a5fa;
        }

        /* Main Content */
        .main { 
            flex: 1; 
            display: flex;
            flex-direction: column;
            overflow: hidden; 
        }

        /* Topbar */
        .topbar {
            height: 80px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
            z-index: 5;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            background: #1e40af;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: 600;
        }

        .logout {
            color: #1e40af;
            font-size: 20px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .logout:hover {
            color: #ef4444;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* Top Banner */
        .top-banner {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 28px 32px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            box-shadow: 0 10px 30px rgba(30, 64, 175, 0.3);
        }
        .icon-box {
            width: 62px;
            height: 62px;
            background: rgba(255,255,255,0.25);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        /* Stats Cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        }
        .stat-card .number { font-size: 36px; font-weight: 700; margin-bottom: 4px; }
        .stat-card .label { font-size: 14.5px; color: #64748b; }
        .card-blue .number { color: #1e40af; }
        .card-yellow .number { color: #d97706; }
        .card-green .number { color: #10b981; }
        .card-red .number { color: #ef4444; }

        /* Search Box */
        .search-box {
            background: white;
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }
        .search-box i { color: #94a3b8; }
        .search-box input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 15px;
            background: transparent;
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        }
        table { width: 100%; border-collapse: collapse; }
        thead { background: linear-gradient(90deg, #1e40af, #3b82f6); color: white; }
        th {
            padding: 15px 16px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }
        td {
            padding: 15px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14.5px;
            color: #334155;
        }
        tbody tr:hover { background: #f8fafc; }
        .student-id { color: #1e40af; font-weight: 600; }

        /* Badges & Buttons */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 600;
        }
        .badge-paid { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-reject { background: #fee2e2; color: #b91c1c; }

        .action-btns { display: flex; gap: 8px; }
        .action-btns a {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-view { background: #e0f2fe; color: #0369a1; }
        .btn-approve { background: #d1fae5; color: #065f46; }
        .btn-reject { background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-area">
            <img src="images/iiuc.png" class="logo" alt="IIUC"
                 onerror="this.src='https://via.placeholder.com/55x55/1e40af/ffffff?text=IIUC'">
            <div>
                <strong style="font-size:22px;">IIUC</strong><br>
                <small style="opacity:0.9;">Admin Portal</small>
            </div>
        </div>
        <ul class="nav">
            <li>
                <a href="admin-dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="active">
                <a href="manage-registration.php">
                    <i class="fas fa-clipboard-list"></i> Manage Registrations
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">

        <!-- Topbar -->
        <div class="topbar">
            <h2>Manage Course Registrations</h2>

            <div class="user-info">
                <div style="text-align: right;">
                    <h4><?php echo isset($row['name']) ? $row['name'] : 'Administrator'; ?></h4>
                    <small><?php echo isset($row['user_id']) ? $row['user_id'] : $id; ?></small>
                </div>

                <div class="avatar">
                    <?php 
                        $displayName = isset($row['name']) && !empty($row['name']) ? $row['name'] : $id;
                        echo strtoupper(substr($displayName, 0, 1)); 
                    ?>
                </div>

                <a href="index.php" class="logout" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </div>

        <!-- Scrollable Content Area -->
        <div class="content-area">

            <!-- Top Banner -->
            <div class="top-banner">
                <div style="display:flex; align-items:center; gap:20px;">
                    <div class="icon-box">📋</div>
                    <div>
                        <p style="font-size:16px; font-weight:600; opacity:0.95;">REGISTRATION MANAGEMENT</p>
                        <h1 style="font-size:29px; font-weight:700; margin:4px 0;">Manage Course Registrations</h1>
                        <p style="font-size:16px; opacity:0.9;">Review, approve, or reject student course registrations and verify payments</p>
                    </div>
                </div>

                <div style="display:flex; gap:14px;">
                    <div style="background:rgba(255,255,255,0.2); padding:12px 20px; border-radius:12px; text-align:center;">
                        <small>Admin ID</small><br>
                        <strong><?php echo $id; ?></strong>
                    </div>
                    <div style="background:rgba(255,255,255,0.2); padding:12px 20px; border-radius:12px; text-align:center;">
                        <small>Department</small><br>
                        <strong>CSE</strong>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats">
                <div class="stat-card card-blue">
                    <div class="number"><?php echo $total['total']; ?></div>
                    <div class="label">Total Registrations</div>
                </div>

                <div class="stat-card card-yellow">
                    <div class="number"><?php echo $pending['total']; ?></div>
                    <div class="label">Pending Registrations</div>
                </div>

                <div class="stat-card card-green">
                    <div class="number"><?php echo $approved['total']; ?></div>
                    <div class="label">Approved Registrations</div>
                </div>

                <div class="stat-card card-red">
                    <div class="number"><?php echo $rejected['total']; ?></div>
                    <div class="label">Rejected Registrations</div>
                </div>
            </div>

            <!-- Search Box -->
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search by Student ID, Name, or Registration ID..."
                    onkeyup="filterTable()">
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table id="regTable">
                    <thead>
                        <tr>
                            <th>REGISTRATION ID</th>
                            <th>STUDENT ID</th>
                            <th>STUDENT NAME</th>
                            <th>SEMESTER</th>
                            <th>CREDITS</th>
                            <th>AMOUNT</th>
                            <th>PAYMENT</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($reg=mysqli_fetch_assoc($registrations)) { ?>
                        <tr>
                            <td><?php echo $reg['registration_id']; ?></td>
                            <td class="student-id"><?php echo $reg['student_id']; ?></td>
                            <td><?php echo $reg['name']; ?></td>
                            <td><?php echo $reg['semester']; ?></td>
                            <td><?php echo $reg['total_credit']; ?></td>
                            <td>BDT <?php echo number_format($reg['total_amount']); ?></td>
                            <td>
                                <?php
                                if($reg['verification_status']=="Verified") {
                                    echo "<span class='badge badge-paid'>Verified</span>";
                                } else if($reg['verification_status']=="Rejected") {
                                    echo "<span class='badge badge-reject'>Rejected</span>";
                                } else {
                                    echo "<span class='badge badge-pending'>Pending</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($reg['registration_status']=="Approved") {
                                    echo "<span class='badge badge-approved'>Approved</span>";
                                } else if($reg['registration_status']=="Rejected") {
                                    echo "<span class='badge badge-reject'>Rejected</span>";
                                } else {
                                    echo "<span class='badge badge-pending'>Pending</span>";
                                }
                                ?>
                            </td>
                            <td>

<div class="action-btns">

<a class="btn-view"
href="view_registration.php?id=<?php echo $reg['registration_id']; ?>"
title="View">
<i class="fas fa-eye"></i>
</a>

<?php
if($reg['registration_status']=="Pending")
{
?>

<a class="btn-approve"
href="approve_registration.php?id=<?php echo $reg['registration_id']; ?>"
onclick="return confirm('Approve this registration?')"
title="Approve">

<i class="fas fa-check"></i>

</a>

<a class="btn-reject"
href="reject_registration.php?id=<?php echo $reg['registration_id']; ?>"
onclick="return confirm('Reject this registration?')"
title="Reject">

<i class="fas fa-times"></i>

</a>

<?php
}
?>

</div>

</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
    function filterTable() {
        let input = document.getElementById("searchInput").value.toUpperCase();
        let table = document.getElementById("regTable");
        let tr = table.getElementsByTagName("tr");

        for(let i=1; i<tr.length; i++) {
            let show = false;
            let td = tr[i].getElementsByTagName("td");

            for(let j=0; j<td.length-1; j++) {
                if(td[j].innerText.toUpperCase().indexOf(input) > -1) {
                    show = true;
                }
            }
            tr[i].style.display = show ? "" : "none";
        }
    }
    </script>
</body>
</html>