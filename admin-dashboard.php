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

$totalRegistrations = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM registration"));

$pendingRegistrations = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM registration
WHERE registration_status='Pending'"));

$approvedRegistrations = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM registration
WHERE registration_status='Approved'"));

$rejectedRegistrations = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM registration
WHERE registration_status='Rejected'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IIUC Admin Dashboard</title>
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

        .nav li {
            list-style: none;
        }
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
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 35px;
        }
        .stat-card {
            background: white;
            padding: 28px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 22px;
            transition: all 0.4s ease;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }
        .number { font-size: 46px; font-weight: 700; color: #0f172a; }
        .title { font-size: 16.5px; font-weight: 600; color: #1e2937; }
        .subtitle { font-size: 13.8px; color: #64748b; margin-top: 2px; }

        .action-card {
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
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
            <li class="active">
                <a href="admin-dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
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
            <h2>Admin Dashboard</h2>

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
                    <div class="icon-box">📊</div>
                    <div>
                        <p style="font-size:16px; font-weight:600; opacity:0.95;">ADMINISTRATION PANEL</p>
                        <h1 style="font-size:29px; font-weight:700; margin:4px 0;">Admin Dashboard</h1>
                        <p style="font-size:16px; opacity:0.9;">International Islamic University Chittagong — Spring 2026</p>
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



                <div class="stat-card"> 
                    <div class="stat-icon" style="background:#e0f2fe; color:#0ea5e9;">📋</div>

                    <div>
                    <div class="number"><?php echo $totalRegistrations['total']; ?></div>

                          <div class="title">Total Registrations</div>

                          <div class="subtitle">Submitted registrations</div>
                   </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background:#fef3c7; color:#d97706;">⏳</div>
                    <div>
                        <div class="number"><?php echo $pendingRegistrations['total']; ?></div>
                        <div class="title">Pending Registrations</div>
                        <div class="subtitle">Awaiting review</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background:#d1fae5; color:#10b981;">✅</div>
                    <div>
                        <div class="number"><?php echo $approvedRegistrations['total']; ?></div>
                        <div class="title">Approved Registrations</div>
                        <div class="subtitle">Successfully approved</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background:#fee2e2; color:#ef4444;">🚫</div>
                    <div>
                        <div class="number"><?php echo $rejectedRegistrations['total']; ?></div>
                        <div class="title">Rejected Registrations</div>
                        <div class="subtitle">No rejections</div>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(390px,1fr)); gap:24px;">
                
                <!-- Manage Registrations Card -->
                <a href="manage-registration.php" class="stat-card action-card">
                    <div class="stat-icon" style="background:#f0f9ff; color:#1e40af; font-size:36px;">📋</div>
                    <div>
                        <h3 style="font-size:20px; margin-bottom:8px;">Manage Registrations</h3>
                        <p style="color:#64748b; line-height:1.5;">Review, approve, or reject student course registrations</p>
                    </div>
                    <div style="margin-left:auto; font-size:36px; color:#94a3b8;">→</div>
                </a>

            </div>

        </div>
    </div>
</body>
</html>