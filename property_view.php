<?php
session_start();
include('connection.php');
include('header.php');

$pid = isset($_GET['id']) ? $_GET['id'] : '';
$row = null;
if ($pid) {
  $stmt = mysqli_prepare($link, "SELECT * FROM properties WHERE property_id = ? LIMIT 1");
  mysqli_stmt_bind_param($stmt, 's', $pid);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = $result ? mysqli_fetch_assoc($result) : null;
  mysqli_stmt_close($stmt);
}

$photos = [];
if ($row && !empty($row['photos_json'])) {
  $decoded = json_decode($row['photos_json'], true);
  if (is_array($decoded)) $photos = $decoded;
}
?>

<div class="page-wrapper">
  <div class="page-content">
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h4 class="mb-0">🟦 Property Details</h4>
              <?php if ($row): ?>
                  <a href="property_add.php?edit_id=<?php echo urlencode($row['property_id']); ?>" class="btn btn-warning"><i class="bx bx-edit"></i> Edit Property</a>
              <?php endif; ?>
            </div>

            <?php if (!$row): ?>
              <div class="alert alert-danger">Property not found for ID: <?php echo htmlspecialchars($pid); ?></div>
            <?php else: ?>
              <div class="row g-3">
                <div class="col-md-3">
                  <div class="p-3 border rounded">
                    <div><strong>ID:</strong> <?php echo htmlspecialchars($row['property_id']); ?></div>
                    <div><strong>Type:</strong> <?php echo htmlspecialchars($row['type']); ?></div>
                    <div><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></div>
                    <div><strong>Moderation:</strong> <?php echo htmlspecialchars($row['moderation_status']); ?></div>
                    <div><strong>Created:</strong> <?php echo htmlspecialchars($row['created_at']); ?></div>
                    <div><strong>Created By:</strong> <?php echo htmlspecialchars($row['created_by']); ?></div>
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="p-3 border rounded mb-3">
                    <h6 class="mb-2">Units & Area</h6>
                    <div>
                      <strong>Area Type:</strong> <?php echo htmlspecialchars(!empty($row['area_type']) ? $row['area_type'] : $row['unit_1']); ?>
                      <strong class="ms-2">Area Size:</strong> <?php echo htmlspecialchars(!empty($row['area_size']) ? $row['area_size'] : $row['value_1']); ?>
                      <?php if (!empty($row['unit_2'])): ?>
                        <strong class="ms-3">Unit 2:</strong> <?php echo htmlspecialchars($row['unit_2']); ?>
                        <strong class="ms-2">Value 2:</strong> <?php echo htmlspecialchars($row['value_2']); ?>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="p-3 border rounded mb-3">
                    <h6 class="mb-2">Address</h6>
                    <div><strong>Village:</strong> <?php echo htmlspecialchars($row['village']); ?></div>
                    <div><strong>Taluka:</strong> <?php echo htmlspecialchars($row['taluka']); ?></div>
                    <div><strong>District:</strong> <?php echo htmlspecialchars($row['district']); ?></div>
                    <div><strong>Landmark:</strong> <?php echo htmlspecialchars($row['landmark']); ?></div>
                    <div><strong>Full Address:</strong> <?php echo nl2br(htmlspecialchars($row['full_address'])); ?></div>
                    <?php if (!empty($row['google_map_link'])): ?>
                      <div class="mt-2"><a class="btn btn-sm btn-outline-info" target="_blank" href="<?php echo htmlspecialchars($row['google_map_link']); ?>">Open Google Map</a></div>
                    <?php endif; ?>
                  </div>
                  <div class="p-3 border rounded mb-3">
                    <h6 class="mb-2">Rates</h6>
                    <div><strong>Rate Unit:</strong> <?php echo htmlspecialchars($row['rate_unit']); ?></div>
                    <div><strong>Rate Value:</strong> <?php echo htmlspecialchars($row['rate_value']); ?></div>
                    <div><strong>Price Option:</strong> <?php echo htmlspecialchars($row['price_option']); ?></div>
                    <?php 
                    $total_amt = 0;
                    $r_val = floatval($row['rate_value'] ?? 0);
                    $p_opt = strtolower(trim($row['price_option'] ?? ''));
                    
                    if ($p_opt === 'thousand') {
                        $total_amt = $r_val * 1000;
                    } elseif ($p_opt === 'lakh') {
                        $total_amt = $r_val * 100000;
                    } elseif ($p_opt === 'crore') {
                        $total_amt = $r_val * 10000000;
                    } elseif (!empty($row['total_rupees_amount'])) {
                        $total_amt = floatval($row['total_rupees_amount']);
                    }
                    
                    if ($total_amt > 0): 
                    ?>
                      <div><strong>Total Rupees Amount:</strong> <?php echo number_format($total_amt); ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="p-3 border rounded mb-3">
                    <h6 class="mb-2">Owner Details</h6>
                    <div><strong>Name:</strong> <?php echo htmlspecialchars($row['owner_name']); ?></div>
                    <div><strong>Contact:</strong> <?php echo htmlspecialchars($row['owner_contact']); ?></div>
                    <div><strong>Alternate:</strong> <?php echo htmlspecialchars($row['alt_contact']); ?></div>
                    <div><strong>Direct Owner:</strong> <?php echo !empty($row['is_direct_owner']) ? 'YES' : 'NO'; ?></div>
                  </div>
                  <!-- <div class="p-3 border rounded mb-3">
                    <h6 class="mb-2">Promotion</h6>
                    <div><strong>Invoice Generated:</strong> <?php echo !empty($row['promotion_invoice_generated']) ? 'YES' : 'NO'; ?></div>
                    <div><strong>Tag:</strong> <?php echo htmlspecialchars($row['promotion_tag']); ?></div>
                    <div><strong>Date:</strong> <?php echo htmlspecialchars($row['promotion_date']); ?></div>
                  </div> -->
                  <div class="p-3 border rounded mb-3">
                    <h6 class="mb-2">Media</h6>
                    <?php if (!empty($photos)): ?>
                      <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($photos as $idx => $p): ?>
                          <div class="border p-1">
                            <img src="<?php echo htmlspecialchars($p); ?>" alt="photo <?php echo $idx+1; ?>" style="width: 120px; height: auto;" />
                          </div>
                        <?php endforeach; ?>
                      </div>
                    <?php else: ?>
                      <div class="text-muted">No photos uploaded.</div>
                    <?php endif; ?>
                    <?php if (!empty($row['video_link'])): ?>
                      <div class="mt-2"><a class="btn btn-sm btn-outline-secondary" target="_blank" href="<?php echo htmlspecialchars($row['video_link']); ?>">Open Video Link</a></div>
                    <?php endif; ?>
                    <?php if (!empty($row['location_note'])): ?>
                      <div class="mt-2"><strong>Location Note:</strong><br><?php echo nl2br(htmlspecialchars($row['location_note'])); ?></div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
