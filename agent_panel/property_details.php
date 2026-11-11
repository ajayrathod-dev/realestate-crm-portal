<?php
session_start();
include("connection.php");

// Auth check (same pattern as other pages)
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
        $br_id = mysqli_real_escape_string($link, $row4['user_id']);
    }
} else {
    header("location:logout.php");
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
                        <h5 class="mb-3">Property Details - Add New</h5>
                        <form class="row g-3" action="property_details1.php" method="post" enctype="multipart/form-data">
                            <div class="col-md-6">
                                <label for="property_id" class="form-label">Property ID</label>
                                <input type="text" class="form-control" id="property_id" name="property_id" required>
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" class="form-control" id="price" name="price" required>
                            </div>

                            <div class="col-md-12">
                                <label for="details" class="form-label">Property Details</label>
                                <textarea class="form-control" id="details" name="details" rows="2" required></textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="address" class="form-label">Property Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>

                            <div class="col-md-6">
                                <label for="owner_name" class="form-label">Owner's Name</label>
                                <input type="text" class="form-control" id="owner_name" name="owner_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="owner_contact" class="form-label">Owner's Contact No.</label>
                                <input type="tel" class="form-control" id="owner_contact" name="owner_contact" required>
                            </div>

                            <div class="col-md-6">
                                <label for="broker" class="form-label">Broker</label>
                                <input type="text" class="form-control" id="broker" name="broker">
                            </div>
                            <div class="col-md-6">
                                <label for="website" class="form-label">Web</label>
                                <input type="text" class="form-control" id="website" name="website">
                            </div>

                            <div class="col-md-6">
                                <label for="photo" class="form-label">Photo Upload</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label for="video_link" class="form-label">Upload Video Link</label>
                                <input type="text" class="form-control" id="video_link" name="video_link">
                            </div>

                            <div class="col-md-6">
                                <label for="promotion" class="form-label">Promotion</label>
                                <input type="text" class="form-control" id="promotion" name="promotion">
                            </div>
                            <div class="col-md-6">
                                <label for="size" class="form-label">Seized</label>
                                <input type="text" class="form-control" id="size" name="size">
                            </div>

                            <div class="col-12" align="center">
                                <button type="submit" class="btn btn-outline-primary px-5">Save Property</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="alert alert-dark border-0 bg-dark alert-dismissible fade show" style="height:60px;">
                <div class="text-white"><h5 class="text-white">Property Details Log</h5></div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Property ID</th>
                                <th>Price</th>
                                <th>Owner</th>
                                <th>Contact</th>
                                <th>Broker</th>
                                <th>Web</th>
                                <th>Video</th>
                                <th>Promotion</th>
                                <th>Seized</th>
                                <th>By</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr = 1;
                            $sql1 = "SELECT * FROM property_details ORDER BY id DESC";
                            $result1 = mysqli_query($link, $sql1);
                            if ($result1 && $result1->num_rows > 0) {
                                while ($row = mysqli_fetch_assoc($result1)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $sr; ?></td>
                                        <td><?php echo htmlspecialchars($row['property_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                                        <td><?php echo htmlspecialchars($row['owner_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['owner_contact']); ?></td>
                                        <td><?php echo htmlspecialchars($row['broker']); ?></td>
                                        <td><?php echo htmlspecialchars($row['website']); ?></td>
                                        <td><?php echo htmlspecialchars($row['video_link']); ?></td>
                                        <td><?php echo htmlspecialchars($row['promotion']); ?></td>
                                        <td><?php echo htmlspecialchars($row['size']); ?></td>
                                        <td><?php echo htmlspecialchars($row['by']); ?></td>
                                        <td><?php 
                                            $datetime = $row['date'];
                                            echo date("d-m-Y h:i A", strtotime($datetime));
                                        ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="property_details_edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                                            <a class="btn btn-sm btn-danger" href="property_details_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this record?');">Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $sr++;
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

<?php
include('footer.php');
?>