<?php
session_start();
include 'connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = mysqli_query($link, "SELECT * FROM property_advarticement0 WHERE id=$id");
$row = $res ? mysqli_fetch_assoc($res) : null;
if (!$row) {
    header('Location: property_advarticement0.php?msg=Record not found');
    exit;
}
?>
<?php include 'header.php'; ?>

<div class="page-wrapper">
  <div class="page-content">
    <div class="row">
      <div class="col-12 col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Edit Property Advarticement 0</h5>
          </div>
          <div class="card-body">
            <form method="post" action="property_advarticement0_edit1.php">
              <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>" />
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Proparty ID</label>
                  <input type="text" name="property_id" class="form-control" value="<?php echo htmlspecialchars($row['property_id']); ?>" />
                </div>
                <div class="col-md-8">
                  <label class="form-label">Contact</label>
                  <textarea name="content" class="form-control" rows="3"><?php echo htmlspecialchars($row['content']); ?></textarea>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Owner Name</label>
                  <input type="text" name="owner_name" class="form-control" value="<?php echo htmlspecialchars($row['owner_name']); ?>" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Paid Vedio Amount</label>
                  <input type="text" name="paid_video_amount" class="form-control" value="<?php echo htmlspecialchars($row['paid_video_amount']); ?>" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Transportaiton</label>
                  <input type="text" name="transportation" class="form-control" value="<?php echo htmlspecialchars($row['transportation']); ?>" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">PRomotion Closing Exicutive Name</label>
                  <input type="text" name="promotion_closing_executive_name" class="form-control" value="<?php echo htmlspecialchars($row['promotion_closing_executive_name']); ?>" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Closing Date</label>
                  <input type="date" name="closing_date" class="form-control" value="<?php echo htmlspecialchars($row['closing_date']); ?>" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Work order video (URL)</label>
                  <input type="text" name="work_order_video" class="form-control" value="<?php echo htmlspecialchars($row['work_order_video']); ?>" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Regular office video (URL)</label>
                  <input type="text" name="regular_office_video" class="form-control" value="<?php echo htmlspecialchars($row['regular_office_video']); ?>" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Video Post Date</label>
                  <input type="date" name="video_post_date" class="form-control" value="<?php echo htmlspecialchars($row['video_post_date']); ?>" />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Remark</label>
                  <textarea name="remark" class="form-control" rows="2"><?php echo htmlspecialchars($row['remark']); ?></textarea>
                </div>
              </div>

              <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="property_advarticement0.php" class="btn btn-secondary">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>