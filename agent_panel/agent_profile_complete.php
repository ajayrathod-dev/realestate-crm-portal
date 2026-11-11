<?php
session_start();
include("connection.php");

// Check if user has valid UUID cookie
if (!isset($_COOKIE['uuid'])) {
    header("location:login.php");
    exit;
}

$uuid = mysqli_real_escape_string($link, $_COOKIE['uuid']);

// Check OTP verification status
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
$current_name = $row['name'];
$approval_status = $row['approval_status'] ?? 'pending';

// If already approved, redirect to dashboard
if ($approval_status === 'approved') {
    header("location:dashboard.php");
    exit;
}

// If profile is already complete (name is not '-' or empty), redirect to pending page
if ($current_name !== '-' && $current_name !== '' && $current_name !== null) {
    header("location:agent_pending_approval.php");
    exit;
}

// Handle form submission
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($link, trim($_POST['name']));
    $address = mysqli_real_escape_string($link, trim($_POST['address']));
    
    if ($name === '' || $address === '') {
        $error = "Please fill in all fields.";
    } else {
        // Update user profile
        $update_sql = "UPDATE users_ap SET name='$name', address='$address', approval_status='pending' WHERE user_id='$user_id'";
        if (mysqli_query($link, $update_sql)) {
            header("location:agent_pending_approval.php?success=1");
            exit;
        } else {
            $error = "Failed to save profile. Please try again.";
        }
    }
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
    <title>Agent Panel - Complete Profile</title>
    <style>
        body {
            background: radial-gradient(circle at center, #e6cfa7 0%, #b87b3e 60%, #5a3e22 100%);
            min-height: 100vh;
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
                            <div class="card-body p-4">
                                <div class="mb-4 text-center">
                                    <img src="property.png" style="width:100px;">
                                </div>
                                
                                <div class="text-center mb-4">
                                    <h3>Complete Your Profile</h3>
                                    <p class="text-muted">Please fill in your details to continue</p>
                                </div>
                                
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                                <?php endif; ?>
                                
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" required 
                                               placeholder="Enter your full name"
                                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <textarea name="address" class="form-control" rows="3" required 
                                                  placeholder="Enter your full address"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Mobile Number</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user_id); ?>" readonly disabled>
                                        <small class="text-muted">This is your registered mobile number</small>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" name="submit" class="btn btn-primary btn-lg">
                                            <i class="bx bx-check-circle"></i> Submit Profile
                                        </button>
                                    </div>
                                </form>
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
