<?php
session_start();
include 'connection.php';

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<?php include 'header.php'; ?>

<div class="page-wrapper">
  <div class="page-content">
    <div class="row">
      <div class="col-12 col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Property Advarticement 0</h5>
          </div>
          <div class="card-body">
            <?php if ($msg) { echo '<div class="alert alert-info">'.htmlspecialchars($msg).'</div>'; } ?>
            <form method="post" action="property_advarticement0_1.php">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Proparty ID</label>
                  <input type="text" name="property_id" class="form-control" />
                </div>
                <div class="col-md-8">
                  <label class="form-label">Contact</label>
                  <textarea name="content" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Owner Name</label>
                  <input type="text" name="owner_name" class="form-control" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Paid Vedio Amount</label>
                  <input type="text" name="paid_video_amount" class="form-control" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Transportaiton</label>
                  <input type="text" name="transportation" class="form-control" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">PRomotion Closing Exicutive Name</label>
                  <input type="text" name="promotion_closing_executive_name" class="form-control" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Closing Date</label>
                  <input type="date" name="closing_date" class="form-control" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Work order video (URL)</label>
                  <input type="text" name="work_order_video" class="form-control" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Regular office video (URL)</label>
                  <input type="text" name="regular_office_video" class="form-control" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Video Post Date</label>
                  <input type="date" name="video_post_date" class="form-control" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Remark</label>
                  <textarea name="remark" class="form-control" rows="2"></textarea>
                </div>
              </div>

              <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="mb-0">Logs</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="example2" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Property ID</th>
                    <th>Contact</th>
                    <th>Owner Name</th>
                    <th>Paid Vedio Amount</th>
                    <th>Transportaiton</th>
                    <th>Closing Executive</th>
                    <th>Closing Date</th>
                    <th>Work order video</th>
                    <th>Regular office video</th>
                    <th>Video post date</th>
                    <th>Remark</th>
                    <th>By</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $res = mysqli_query($link, "SELECT * FROM property_advarticement0 ORDER BY id DESC");
                  if ($res) {
                    while ($row = mysqli_fetch_assoc($res)) {
                      echo '<tr>';
                      echo '<td>'.htmlspecialchars($row['id']).'</td>';
                      echo '<td>'.htmlspecialchars($row['property_id']).'</td>';
                      echo '<td>'.nl2br(htmlspecialchars($row['content'])).'</td>';
                      echo '<td>'.htmlspecialchars($row['owner_name']).'</td>';
                      echo '<td>'.htmlspecialchars($row['paid_video_amount']).'</td>';
                      echo '<td>'.htmlspecialchars($row['transportation']).'</td>';
                      echo '<td>'.htmlspecialchars($row['promotion_closing_executive_name']).'</td>';
                      echo '<td>'.htmlspecialchars($row['closing_date']).'</td>';
                      echo '<td>'.htmlspecialchars($row['work_order_video']).'</td>';
                      echo '<td>'.htmlspecialchars($row['regular_office_video']).'</td>';
                      echo '<td>'.htmlspecialchars($row['video_post_date']).'</td>';
                      echo '<td>'.nl2br(htmlspecialchars($row['remark'])).'</td>';
                      echo '<td>'.htmlspecialchars($row['by']).'</td>';
                      echo '<td>'.htmlspecialchars($row['date']).'</td>';
                      echo '<td>';
                      echo '<a class="btn btn-sm btn-outline-primary me-1" href="property_advarticement0_edit.php?id='.(int)$row['id'].'">Edit</a>';
                      echo '<a class="btn btn-sm btn-outline-danger" href="property_advarticement0_delete.php?id='.(int)$row['id'].'" onclick="return confirm(\'Delete this entry?\')">Delete</a>';
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

<?php include 'footer.php'; ?>