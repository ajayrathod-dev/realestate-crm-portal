<?php
session_start();
include('connection.php');
include('header.php');

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'verified_or_approved';

$where = '1=1';
switch ($filter) {
  case 'verified_or_approved':
    $where = "moderation_status IN ('verified','approved')";
    break;
  case 'pending':
    $where = "moderation_status = 'pending'";
    break;
  case 'rejected':
    $where = "moderation_status = 'rejected'";
    break;
  case 'all':
  default:
    $where = '1=1';
}

$sql = "SELECT property_id, main_photo, type, area_type, area_size, village, taluka, district, price_option, owner_name, owner_contact, moderation_status, created_at FROM properties WHERE $where ORDER BY id DESC";
$res = mysqli_query($link, $sql);
?>

<div class="page-wrapper">
  <div class="page-content">
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
          <div class="card-body p-4">
            <h4 class="mb-3">🟦 All Properties</h4>

            <div class="d-flex gap-2 mb-3">
              <a href="all_properties.php?filter=verified_or_approved" class="btn btn-outline-success">Approved_Properties</a>
              <a href="all_properties.php?filter=pending" class="btn btn-outline-warning">Pending_Properties</a>
              <a href="all_properties.php?filter=rejected" class="btn btn-outline-danger">Rejected_Properties</a>
              <a href="all_properties.php?filter=all" class="btn btn-outline-secondary">All_Properties</a>
            </div>

            <div class="table-responsive">
              <table id="example2" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Photo</th>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Area</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Owner Info</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($res && mysqli_num_rows($res) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($res)): ?>
                      <tr>
                        <td>
                          <?php if (!empty($row['main_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($row['main_photo']); ?>" alt="Img" style="width:50px;height:50px;object-fit:cover;">
                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['property_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                        <td><?php echo htmlspecialchars($row['area_type'] ?? '') . ': ' . htmlspecialchars($row['area_size'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['village'] ?? '') . ', ' . htmlspecialchars($row['taluka'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['price_option'] ?: $row['total_rupees_amount']); ?></td>
                        <td>
                           <?php echo htmlspecialchars($row['owner_name']); ?><br>
                           <small><?php echo htmlspecialchars($row['owner_contact']); ?></small>
                        </td>
                        <td>
                           <?php echo htmlspecialchars($row['moderation_status']); ?>
                        </td>
                        <td class="text-nowrap">
                          <?php if (strtolower($row['moderation_status']) === 'approved'): ?>
                            <span class="badge rounded-pill bg-success text-dark">Approved</span>
                          <?php elseif (strtolower($row['moderation_status']) === 'rejected'): ?>
                            <span class="badge rounded-pill bg-danger">Rejected</span>
                          <?php else: ?>
                            <form method="post" action="packed.php" class="d-inline">
                              <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($row['property_id']); ?>">
                              <select name="status" class="form-select form-select-sm d-inline-block w-auto me-2">
                                <option value=""><?php echo htmlspecialchars($row['moderation_status']); ?></option>
                                <option value="Approve">Approve</option>
                                <option value="Reject">Reject</option>
                              </select>
                              <input type="submit" name="submit" value="Submit" class="btn btn-sm btn-primary">
                            </form>
                          <?php endif; ?>
                          <a class="btn btn-sm btn-info ms-2" href="property_view.php?id=<?php echo urlencode($row['property_id']); ?>">All Info</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr><td colspan="9" class="text-center">No properties found.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
