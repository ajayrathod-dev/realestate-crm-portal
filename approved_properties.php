<?php
session_start();
include('connection.php');
include('header.php');
$sql = "SELECT 
  property_id, type, status, moderation_status, owner_name, promotion_tag, created_at,
  main_photo, photos_json, area_type, area_size,
  rate_unit, rate_value, price_option, total_rupees_amount,
  village, taluka, district, landmark, google_map_link
FROM properties 
WHERE moderation_status = 'approved' 
ORDER BY id DESC";
$res = mysqli_query($link, $sql);
?>
<div class="page-wrapper">
  <div class="page-content">
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
          <div class="card-body p-4">
            <h4 class="mb-3"> Properties list ( user panel page )</h4>
            <div class="table-responsive">
              <table id="" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Photo</th>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Area</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Map</th>
                    <th>View</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($res && mysqli_num_rows($res) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($res)): ?>
                      <?php
                        $photo = $row['main_photo'];
                        if ((!$photo || $photo === '') && !empty($row['photos_json'])) {
                          $arr = json_decode($row['photos_json'], true);
                          if (is_array($arr) && count($arr) > 0) $photo = $arr[0];
                        }
                        $areaDisplay = '';
                        if (!empty($row['area_type'])) {
                          $areaDisplay = htmlspecialchars($row['area_type']);
                          if ($row['area_size'] !== null && $row['area_size'] !== '') {
                            $areaDisplay .= ': ' . htmlspecialchars($row['area_size']);
                          }
                        }
                        $locParts = [];
                        if (!empty($row['village'])) $locParts[] = htmlspecialchars($row['village']);
                        if (!empty($row['taluka'])) $locParts[] = htmlspecialchars($row['taluka']);
                        if (!empty($row['district'])) $locParts[] = htmlspecialchars($row['district']);
                        $locationDisplay = implode(', ', $locParts);
                        $priceDisplay = '';
                        if (!empty($row['total_rupees_amount'])) {
                          $priceDisplay = 'Total ₹ ' . htmlspecialchars($row['total_rupees_amount']);
                        } else {
                          $po = $row['price_option'];
                          $poLabel = $po ? ucfirst($po) : '';
                          if (!empty($row['rate_value']) && !empty($row['rate_unit'])) {
                            $priceDisplay = htmlspecialchars($row['rate_value']) . ' ' . htmlspecialchars($poLabel) . ' per ' . htmlspecialchars($row['rate_unit']);
                          }
                        }
                        $mapLink = !empty($row['google_map_link']) ? $row['google_map_link'] : '';
                      ?>
                      <tr>
                        <td>
                          <?php if (!empty($photo)): ?>
                            <img src="<?php echo htmlspecialchars($photo); ?>" alt="Photo" style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['property_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                        <td><?php echo $areaDisplay !== '' ? $areaDisplay : '-'; ?></td>
                        <td><?php echo $locationDisplay !== '' ? $locationDisplay : '-'; ?></td>
                        <td><?php echo $priceDisplay !== '' ? $priceDisplay : '-'; ?></td>
                        <td>
                          <?php if ($mapLink !== ''): ?>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo htmlspecialchars($mapLink); ?>" target="_blank">Map</a>
                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                        <td class="text-nowrap">
                          <a class="btn btn-sm btn-info" href="property_view.php?id=<?php echo urlencode($row['property_id']); ?>">View</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr><td colspan="8" class="text-center">No properties found.</td></tr>
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
