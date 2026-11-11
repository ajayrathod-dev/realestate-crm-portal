<?php
if (!defined('UCDAS_WAF_LOADED')) {
    $wafFile = __DIR__ . '/../codecrave_portfolio/security/UCDAS_WAF.php';
    if (!file_exists($wafFile)) {
        $wafFile = 'c:/xampp/htdocs/best_projects/codecrave_portfolio/security/UCDAS_WAF.php';
    }
    if (file_exists($wafFile)) {
        @include_once $wafFile;
    }
}

if (session_status() === PHP_SESSION_NONE) { session_start(); }
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
                            <th>Status</th>
                            <th>Moderation</th>
                            <th>Created By</th>
                            <th>Owner Name</th>
                            <th>Owner Contact</th>
                            <th>Alt Contact</th>
                            <th>Direct Owner?</th>
                            <th>Village</th>
                            <th>Taluka</th>
                            <th>District</th>
                            <th>Landmark</th>
                            <th>Address</th>
                            <th>Map</th>
                            <th>Area Type</th>
                            <th>Area Size</th>
                            <!--<th>Unit 2</th>-->
                            <!--<th>Value 2</th>-->
                            <th>Rate Unit</th>
                            <th>Rate Value</th>
                            <th>Price Option</th>
                            <!--<th>Total Amount</th>-->
                            <th>Promo Tag</th>
                            <th>Promo Date</th>
                            <th>Invoice Gen?</th>
                            <th>Main Photo</th>
                          
                            <th>Video Link</th>
                            <th>Loc Note</th>
                            <th>Is Old?</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($link, "SELECT * FROM properties ORDER BY id DESC LIMIT 20");
                        if ($res && mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['property_id'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['type'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['status'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['moderation_status'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['created_by'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['owner_name'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['owner_contact'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['alt_contact'] ?? '') . '</td>';
                                echo '<td>' . (!empty($row['is_direct_owner']) ? 'Yes' : 'No') . '</td>';
                                echo '<td>' . htmlspecialchars($row['village'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['taluka'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['district'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['landmark'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['full_address'] ?? '') . '</td>';
                                echo '<td>' . (!empty($row['google_map_link']) ? '<a href="' . htmlspecialchars($row['google_map_link']) . '" target="_blank">Map</a>' : '-') . '</td>';
                                echo '<td>' . htmlspecialchars($row['area_type'] ?? $row['unit_1']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['area_size'] ?? $row['value_1']) . '</td>';
                                // echo '<td>' . htmlspecialchars($row['unit_2']) . '</td>';
                                // echo '<td>' . htmlspecialchars($row['value_2']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['rate_unit'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['rate_value'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['price_option'] ?? '') . '</td>';
                                // echo '<td>' . htmlspecialchars($row['total_rupees_amount']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['promotion_tag'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($row['promotion_date'] ?? '') . '</td>';
                                echo '<td>' . (!empty($row['promotion_invoice_generated']) ? 'Yes' : 'No') . '</td>';
                                
                                $photoHtml = '-';
                                if (!empty($row['main_photo'])) {
                                    $photoHtml = '<img src="' . htmlspecialchars($row['main_photo']) . '" alt="Main" style="width:50px;height:50px;object-fit:cover;">';
                                }
                                echo '<td>' . $photoHtml . '</td>';
                                
                              
                                echo '<td>' . (!empty($row['video_link']) ? '<a href="' . htmlspecialchars($row['video_link']) . '" target="_blank">Video</a>' : '-') . '</td>';
                                echo '<td>' . htmlspecialchars($row['location_note'] ?? '') . '</td>';
                                echo '<td>' . (!empty($row['is_old_property']) ? 'Yes' : 'No') . '</td>';
                                echo '<td>' . htmlspecialchars($row['created_at'] ?? '') . '</td>';
                                echo '<td class="text-nowrap">';
                                // if already approved or rejected, show badge only
                                if (strtolower($row['moderation_status']) === 'approved') {
                                    echo '<span class="badge rounded-pill bg-success text-dark">Approved</span>';
                                } else if (strtolower($row['moderation_status']) === 'rejected') {
                                    echo '<span class="badge rounded-pill bg-danger">Rejected</span>';
                                } else {
                                    // show Approve/Reject form (POST)
                                    echo '<form method="post" action="packed.php" class="d-inline">'
                                        . '<input type="hidden" name="property_id" value="' . htmlspecialchars($row['property_id']) . '">'
                                        . '<select name="status" class="form-select form-select-sm d-inline-block w-auto me-2">'
                                        . '<option value="">' . htmlspecialchars($row['moderation_status']) . '</option>'
                                        . '<option value="Approve">Approve</option>'
                                        . '<option value="Reject">Reject</option>'
                                        . '</select>'
                                        . '<input type="submit" name="submit" value="Submit" class="btn btn-sm btn-primary">'
                                        . '</form>';
                                }
                                // All Info link (same as All Properties page)
                                // echo ' <a class="btn btn-sm btn-info ms-2" href="property_view.php?id=' . urlencode($row['property_id']) . '">All Info</a>';
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

document.getElementById('type').addEventListener('change', function(){
    toggleUnits(this.value);
    if (document.getElementById('id_mode').value === 'new') requestNextId(this.value);
});

document.getElementById('id_mode').addEventListener('change', function(){
    var mode = this.value;
    var type = document.getElementById('type').value;
    var idInput = document.getElementById('property_id');
    idInput.readOnly = (mode === 'new');
    if (mode === 'new' && type) requestNextId(type);
});

document.getElementById('regen_id').addEventListener('click', function(){
    var type = document.getElementById('type').value;
    if (type) requestNextId(type);
});
</script>
 
