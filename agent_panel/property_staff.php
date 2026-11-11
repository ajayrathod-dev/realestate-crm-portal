<?php
session_start();
include('connection.php');
include('header.php');
$created_by = isset($_SESSION['uname']) ? $_SESSION['uname'] : 'staff';
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
                                <?php if (!empty($_GET['log'])): ?>
                                    <br><small class="text-muted">Log: <?php echo nl2br(htmlspecialchars($_GET['log'])); ?></small>
                                <?php endif; ?>
                            </div>
                        <?php elseif (isset($_GET['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_GET['error']); ?>
                                <?php if (!empty($_GET['log'])): ?>
                                    <br><small class="text-muted">Log: <?php echo nl2br(htmlspecialchars($_GET['log'])); ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
    <!-- Logs: Recently added properties with status and actions -->
    <div class="col-lg-12">
        <div class="alert alert-dark border-0 bg-dark alert-dismissible fade show" style="height:60px;">
            <div class="text-white"><h5 class="text-white">Staff_Added_Properties</h5></div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Recent Properties</div>
        <div class="card-body">
          
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Actions</th>
                            <th>Added By</th>
                            <th>Owner Name</th>
                            <th>Owner Contact</th>
                            <th>Map</th>
                            <th>Price</th>
                            <th>Photos</th>
                            <th>Video</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($link, "SELECT * FROM properties ORDER BY id DESC LIMIT 20");
                        if ($res && mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['property_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['type']) . '</td>';
                                
                                // Actions
                                echo '<td>';
                                if (strtolower($row['moderation_status']) === 'approved') {
                                    echo '<span class="badge rounded-pill bg-success text-dark">Approved</span>';
                                } else if (strtolower($row['moderation_status']) === 'rejected') {
                                    echo '<span class="badge rounded-pill bg-danger">Rejected</span>';
                                    if (!empty($row['rejection_reason'])) {
                                        echo '<br><small class="text-danger">' . htmlspecialchars($row['rejection_reason']) . '</small>';
                                    }
                                } else {
                                    // Approve/Reject form
                                    echo '<form method="post" action="packed.php" class="d-inline action-form" id="form_' . htmlspecialchars($row['property_id']) . '">'
                                        . '<input type="hidden" name="property_id" value="' . htmlspecialchars($row['property_id']) . '">'
                                        . '<select name="status" class="form-select form-select-sm d-inline-block w-auto me-2 action-select" onchange="toggleRejectReason(this)">'
                                        . '<option value="">' . htmlspecialchars($row['moderation_status']) . '</option>'
                                        . '<option value="Approve">Approve</option>'
                                        . '<option value="Reject">Reject</option>'
                                        . '</select>'
                                        . '<input type="text" name="rejection_reason" class="form-control form-control-sm d-none mt-1 rejection-reason" placeholder="Reason for rejection">'
                                        . '<input type="submit" name="submit" value="Submit" class="btn btn-sm btn-primary mt-1">'
                                        . '</form>';
                                }
                                echo '</td>';

                                // Added By - Show name from admin or users_ap table
                                echo '<td>';
                                $created_by_id = $row['created_by'];
                                $display_name = $created_by_id; // Default to ID if not found
                                
                                // First try to find in admin table (staff_admin)
                                $stmt_admin = mysqli_prepare($link, "SELECT emp_name FROM admin WHERE user_id = ? LIMIT 1");
                                if ($stmt_admin) {
                                    mysqli_stmt_bind_param($stmt_admin, 's', $created_by_id);
                                    mysqli_stmt_execute($stmt_admin);
                                    $result_admin = mysqli_stmt_get_result($stmt_admin);
                                    if ($row_admin = mysqli_fetch_assoc($result_admin)) {
                                        $display_name = $row_admin['emp_name'] . ' (Staff)';
                                    }
                                    mysqli_stmt_close($stmt_admin);
                                }
                                
                                // If not found in admin, try users_ap table (agent_panel)
                                if ($display_name === $created_by_id) {
                                    $stmt_agent = mysqli_prepare($link, "SELECT name FROM users_ap WHERE user_id = ? LIMIT 1");
                                    if ($stmt_agent) {
                                        mysqli_stmt_bind_param($stmt_agent, 's', $created_by_id);
                                        mysqli_stmt_execute($stmt_agent);
                                        $result_agent = mysqli_stmt_get_result($stmt_agent);
                                        if ($row_agent = mysqli_fetch_assoc($result_agent)) {
                                            $agent_name = $row_agent['name'];
                                            // If name is just '-' or empty, show the mobile number
                                            if ($agent_name === '-' || empty($agent_name)) {
                                                $display_name = $created_by_id . ' (Agent)';
                                            } else {
                                                $display_name = $agent_name . ' (Agent)';
                                            }
                                        }
                                        mysqli_stmt_close($stmt_agent);
                                    }
                                }
                                
                                echo htmlspecialchars($display_name);
                                echo '</td>';
                                echo '<td>' . htmlspecialchars($row['owner_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['owner_contact']) . ' <a href="tel:' . htmlspecialchars($row['owner_contact']) . '" class="btn btn-sm btn-success ms-1"><i class="bx bx-phone"></i> Call</a></td>';
                                // Map
                                echo '<td>' . ($row['google_map_link'] ? '<a href="' . htmlspecialchars($row['google_map_link']) . '" target="_blank" class="btn btn-sm btn-info">Map</a>' : '-') . '</td>';

                                // Price
                                echo '<td>';
                                if ($row['price_option'] === 'Total Rupees') {
                                    echo htmlspecialchars($row['total_rupees_amount']);
                                } else {
                                    echo htmlspecialchars($row['rate_value'] . ' ' . $row['price_option'] . ' / ' . $row['rate_unit']);
                                }
                                echo '</td>';
                                // Photos with Share
                                echo '<td>';
                                if (!empty($row['main_photo'])) {
                                    echo '<img src="' . htmlspecialchars($row['main_photo']) . '" alt="Main" style="width:50px;height:50px;object-fit:cover;">';
                                    // Simple share button - copies link or opens native share if supported
                                    $shareUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/property_view.php?id=" . $row['property_id'];
                                    echo '<br><button type="button" class="btn btn-sm btn-warning mt-1" onclick="shareProperty(\'' . htmlspecialchars($shareUrl) . '\', \'' . htmlspecialchars($row['main_photo']) . '\')">Share</button>';
                                } else {
                                    echo '-';
                                }
                                echo '</td>';
                                
                                // Video with Share
                                echo '<td>';
                                if ($row['video_link']) {
                                    echo '<a href="' . htmlspecialchars($row['video_link']) . '" target="_blank">Video</a>';
                                     echo '<br><button type="button" class="btn btn-sm btn-warning mt-1" onclick="shareLink(\'' . htmlspecialchars($row['video_link']) . '\')">Share</button>';
                                } else {
                                    echo '-';
                                }
                                echo '</td>';

                                $ms = isset($row['moderation_status']) ? strtolower($row['moderation_status']) : '';
                                if ($ms === 'approved' || $ms === 'rejected') {
                                    echo '<td>' . htmlspecialchars($row['moderation_status']) . '</td>';
                                } else {
                                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                                }
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

<script>
function prefixForType(type) {
    switch (type) {
        case 'Agriculture': return 'AGRI';
        case 'Plot': return 'PLT';
        case 'Flat': return 'FLT';
        case 'Bungalow': return 'BGL';
        case 'Commercial': return 'CMR';
        case 'Residential Rent': return 'RRP';
        case 'Commercial Rent': return 'RCML';
        default: return '';
    }
}

function toggleUnits(type) {
    document.querySelectorAll('.unit-group').forEach(function(el){ el.style.display = 'none'; });
    var target = document.querySelector('.unit-group[data-type="' + type + '"]');
    if (target) target.style.display = 'block';
}

function requestNextId(type) {
    var prefix = prefixForType(type);
    if (!prefix) { document.getElementById('property_id').value = ''; return; }
    fetch('next_property_id.php?type=' + encodeURIComponent(type) + '&t=' + new Date().getTime())
      .then(function(r){ return r.text(); })
      .then(function(txt){ document.getElementById('property_id').value = txt; })
      .catch(function(){ document.getElementById('property_id').value = prefix + '1001'; });
}

// Staff table action: toggle rejection reason input
function toggleRejectReason(select) {
    var form = select.closest('form');
    var reasonInput = form.querySelector('.rejection-reason');
    if (select.value === 'Reject') {
        reasonInput.classList.remove('d-none');
        reasonInput.required = true;
    } else {
        reasonInput.classList.add('d-none');
        reasonInput.required = false;
        reasonInput.value = '';
    }
}

// Share functions
function shareProperty(url, photoUrl) {
    if (navigator.share) {
        navigator.share({
            title: 'Property Check',
            text: 'Check out this property',
            url: url
        }).catch(console.error);
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(function() {
            alert('Link copied to clipboard!');
        }, function(err) {
            alert('Could not copy link: ' + err);
        });
    }
}

function shareLink(url) {
    if (navigator.share) {
        navigator.share({
            title: 'Property Video',
            text: 'Watch this property video',
            url: url
        }).catch(console.error);
    } else {
        navigator.clipboard.writeText(url).then(function() {
            alert('Link copied to clipboard!');
        }, function(err) {
            alert('Could not copy link: ' + err);
        });
    }
}

// Only add listeners if elements exist (property_add.php context)
var typeEl = document.getElementById('type');
if (typeEl) {
    typeEl.addEventListener('change', function(){
        toggleUnits(this.value);
        if (document.getElementById('id_mode').value === 'new') requestNextId(this.value);
    });
}

var idModeEl = document.getElementById('id_mode');
if (idModeEl) {
    idModeEl.addEventListener('change', function(){
        var mode = this.value;
        var type = document.getElementById('type').value;
        var idInput = document.getElementById('property_id');
        idInput.readOnly = (mode === 'new');
        if (mode === 'new' && type) requestNextId(type);
    });
}

var regenIdEl = document.getElementById('regen_id');
if (regenIdEl) {
    regenIdEl.addEventListener('click', function(){
        var type = document.getElementById('type').value;
        if (type) requestNextId(type);
    });
}
</script>
 
