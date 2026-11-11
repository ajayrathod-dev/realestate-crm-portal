<?php
session_start();
include("connection.php");

// Check if user has valid UUID cookie
if (!isset($_COOKIE['uuid'])) {
    header("location:login.php");
    exit;
}

$uuid = mysqli_real_escape_string($link, $_COOKIE['uuid']);

// Check OTP verification status and get user info
$sql = "SELECT o.user_id, u.name, u.address, u.approval_status 
        FROM otp_ap o 
        LEFT JOIN users_ap u ON o.user_id = u.user_id 
        WHERE o.uuid='$uuid' AND o.status='verified' 
        ORDER BY o.id DESC LIMIT 1";
$result = mysqli_query($link, $sql);

if ($result->num_rows == 0) {
    header("location:logout.php");
    exit;
}

$row = $result->fetch_assoc();
$user_id = $row['user_id'];
$name = $row['name'];
$approval_status = $row['approval_status'] ?? 'pending';

// If not yet completed profile, redirect to profile completion
if ($name === '-' || $name === '' || $name === null) {
    header("location:agent_profile_complete.php");
    exit;
}

// If already approved, redirect to dashboard
if ($approval_status === 'approved') {
    header("location:dashboard.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="lg.png" type="image/png" />
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <title>Agent Panel - Pending Approval</title>
    <style>
        body {
            background: radial-gradient(circle at center, #e6cfa7 0%, #b87b3e 60%, #5a3e22 100%);
            min-height: 100vh;
        }
        .pending-icon {
            font-size: 80px;
            color: #ffc107;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="card">
                            <div class="card-body p-4 text-center">
                                <div class="mb-4">
                                    <img src="property.png" style="width:100px;">
                                </div>
                                
                                <div class="pending-icon mb-3">
                                    <i class="bx bx-time-five"></i>
                                </div>
                                
                                <h3 class="text-warning mb-3">Approval Pending</h3>
                                
                                <?php if ($approval_status === 'rejected'): ?>
                                    <div class="alert alert-danger">
                                        <i class="bx bx-x-circle"></i> Your application has been rejected.
                                        <br>Please contact support for more information.
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-4">
                                        Thank you for completing your profile, <strong><?php echo htmlspecialchars($name); ?></strong>!
                                    </p>
                                    
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle"></i> 
                                        Your account is currently under review. 
                                        You will be able to access the dashboard once an admin approves your account.
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card bg-light mt-4">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Your Details</h6>
                                        <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                                        <p class="mb-1"><strong>Mobile:</strong> <?php echo htmlspecialchars($user_id); ?></p>
                                        <p class="mb-0"><strong>Status:</strong> 
                                            <span class="badge bg-<?php echo $approval_status === 'rejected' ? 'danger' : 'warning'; ?>">
                                                <?php echo ucfirst($approval_status); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="logout.php" class="btn btn-outline-secondary">
                                        <i class="bx bx-log-out"></i> Logout
                                    </a>
                                    <button onclick="location.reload();" class="btn btn-primary ms-2">
                                        <i class="bx bx-refresh"></i> Check Status
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
</body>

</html>
