<?php
session_start();
include("connection.php");

// Auth
if (isset($_COOKIE['uuid'])) {
    $u_uname = mysqli_real_escape_string($link, $_COOKIE['uuid']);
} else {
    $u_uname = '';
}
$sql4 = "SELECT * FROM ods WHERE uuid='$u_uname' AND status='verified'";
$result4 = mysqli_query($link, $sql4);
if ($result4 && $result4->num_rows > 0) {
    while ($row4 = $result4->fetch_assoc()) {
        $u_uname = mysqli_real_escape_string($link, $row4['user_id']);
    }
} else {
    header("location:logout.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$row = null;
if ($id > 0) {
    $res = mysqli_query($link, "SELECT * FROM property_details WHERE id=$id");
    if ($res && $res->num_rows > 0) {
        $row = mysqli_fetch_assoc($res);
    } else {
        header("Location: property_details.php?error=Not%20found");
        exit;
    }
} else {
    header("Location: property_details.php?error=Invalid%20ID");
    exit;
}

include('header.php');
?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <h5 class="mb-3">Edit Property Details</h5>
                        <form class="row g-3" action="property_details_edit1.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="existing_photo" value="<?php echo htmlspecialchars($row['photo_path']); ?>">

                            <div class="col-md-6">
                                <label for="property_id" class="form-label">Property ID</label>
                                <input type="text" class="form-control" id="property_id" name="property_id" value="<?php echo htmlspecialchars($row['property_id']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label for="details" class="form-label">Property Details</label>
                                <textarea class="form-control" id="details" name="details" rows="2" required><?php echo htmlspecialchars($row['details']); ?></textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="address" class="form-label">Property Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="owner_name" class="form-label">Owner's Name</label>
                                <input type="text" class="form-control" id="owner_name" name="owner_name" value="<?php echo htmlspecialchars($row['owner_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="owner_contact" class="form-label">Owner's Contact No.</label>
                                <input type="tel" class="form-control" id="owner_contact" name="owner_contact" value="<?php echo htmlspecialchars($row['owner_contact']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="broker" class="form-label">Broker</label>
                                <input type="text" class="form-control" id="broker" name="broker" value="<?php echo htmlspecialchars($row['broker']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control" id="website" name="website" value="<?php echo htmlspecialchars($row['website']); ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="photo" class="form-label">Photo Upload (optional)</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                <?php if (!empty($row['photo_path'])) { ?>
                                    <small>Current: <a href="<?php echo htmlspecialchars($row['photo_path']); ?>" target="_blank">View</a></small>
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <label for="video_link" class="form-label">Upload Video Link</label>
                                <input type="text" class="form-control" id="video_link" name="video_link" value="<?php echo htmlspecialchars($row['video_link']); ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="promotion" class="form-label">Promotion</label>
                                <input type="text" class="form-control" id="promotion" name="promotion" value="<?php echo htmlspecialchars($row['promotion']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="size" class="form-label">Size</label>
                                <input type="text" class="form-control" id="size" name="size" value="<?php echo htmlspecialchars($row['size']); ?>">
                            </div>

                            <div class="col-12" align="center">
                                <button type="submit" class="btn btn-outline-primary px-5">Update Property</button>
                                <a href="property_details.php" class="btn btn-secondary px-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>