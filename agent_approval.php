<?php
session_start();
include('connection.php');

// Ensure approval_status column exists
$checkCol = mysqli_query($link, "SHOW COLUMNS FROM users_ap LIKE 'approval_status'");
if (mysqli_num_rows($checkCol) == 0) {
    mysqli_query($link, "ALTER TABLE users_ap ADD COLUMN approval_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
}

// Handle approve/reject actions
if (isset($_POST['action']) && isset($_POST['user_id'])) {
    $user_id = mysqli_real_escape_string($link, $_POST['user_id']);
    $action = $_POST['action'];
    
    if ($action === 'approve') {
        $sql = "UPDATE users_ap SET approval_status='approved' WHERE user_id='$user_id'";
        mysqli_query($link, $sql);
        header("Location: agent_approval.php?success=Agent approved successfully");
        exit;
    } else if ($action === 'reject') {
        $sql = "UPDATE users_ap SET approval_status='rejected' WHERE user_id='$user_id'";
        mysqli_query($link, $sql);
        header("Location: agent_approval.php?success=Agent rejected");
        exit;
    } else if ($action === 'pending') {
        $sql = "UPDATE users_ap SET approval_status='pending' WHERE user_id='$user_id'";
        mysqli_query($link, $sql);
        header("Location: agent_approval.php?success=Agent status set to pending");
        exit;
    }
}
include('header.php');

// Get counts for dashboard summary if needed, or just display all
// We will query all users who have filled their name (name != '-')
?>

<style>
    .unit-group { display: none; }
    .required::after { content: ' *'; color: #d00; }
</style>
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlspecialchars($_GET['success']); ?>
                            </div>
                        <?php elseif (isset($_GET['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_GET['error']); ?>
                            </div>
                        <?php endif; ?>

    <!-- Header -->
    <div class="col-lg-12">
        <div class="alert alert-dark border-0 bg-dark alert-dismissible fade show" style="height:60px;">
            <div class="text-white"><h5 class="text-white">Agent Approvals & Management</h5></div>
        </div>
    </div>

    <!-- Unified Table -->
    <div class="card mt-4">
        <div class="card-header">All Agents</div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Mobile Number</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Registered On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Select all agents who have a name set (profile completed)
                        $sql = "SELECT * FROM users_ap WHERE name != '-' AND name IS NOT NULL AND name != '' ORDER BY created_at DESC";
                        $result = mysqli_query($link, $sql);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['address'] ?? '-') . '</td>';
                                echo '<td>' . date('d M Y H:i', strtotime($row['created_at'])) . '</td>';
                                
                                // Status Column
                                echo '<td>';
                                $status = strtolower($row['approval_status'] ?? 'pending');
                                if ($status === 'approved') {
                                    echo '<span class="badge rounded-pill bg-success text-dark">Approved</span>';
                                } else if ($status === 'rejected') {
                                    echo '<span class="badge rounded-pill bg-danger">Rejected</span>';
                                } else {
                                    echo '<span class="badge rounded-pill bg-warning text-dark">Pending</span>';
                                }
                                echo '</td>';

                                // Actions Column - Dropdown
                                echo '<td>';
                                echo '<form method="post" class="d-inline">';
                                echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($row['user_id']) . '">';
                                echo '<select name="action" class="form-select form-select-sm">';
                                
                                $statuses = ['pending' => 'Pending', 'approve' => 'Approved', 'reject' => 'Rejected'];
                                foreach ($statuses as $val => $label) {
                                    $selected = '';
                                    // Map DB status to value
                                    $db_status_map = ['pending' => 'pending', 'approved' => 'approve', 'rejected' => 'reject'];
                                    
                                    if ($db_status_map[$status] === $val) {
                                        $selected = 'selected';
                                    }
                                    echo '<option value="' . $val . '" ' . $selected . '>' . $label . '</option>';
                                }
                                
                                echo '</select>';
                                echo '<button type="submit" class="btn btn-primary btn-sm ms-2">Submit</button>';
                                echo '</form>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
