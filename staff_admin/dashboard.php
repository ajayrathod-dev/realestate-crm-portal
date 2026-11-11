<?php
session_start();

include("connection.php");

$current_time = date("M d, Y H:i:s");



if (isset($_COOKIE['uuid'])) {

	$u_uname = mysqli_real_escape_string($link, $_COOKIE['uuid']);

} else {

	$u_uname = '';

}



 $sql4 = "SELECT * FROM ods WHERE uuid='$u_uname' AND status='verified'";

$result4 = mysqli_query($link, $sql4);

if ($result4->num_rows > 0) {

	while ($row4 = $result4->fetch_assoc()) {

		$u_uname = mysqli_real_escape_string($link, $row4['user_id']);

		$br_id = mysqli_real_escape_string($link, $row4['user_id']);

	}

} else {

// 	header("location:logout.php");

// 	exit;

}

// $sql44 = "SELECT * FROM admin ";
// $result44 = mysqli_query($link, $sql44);
// if ($result44->num_rows > 0) {
// 	while ($row44 = $result44->fetch_assoc()) {
// 		// $unit_rate = mysqli_real_escape_string($link, $row44['unit_rate']);
// 	}
// } 



include('header.php');



?>


<div class="page-wrapper">
    <div class="page-content">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
            
            <!-- Approved Properties -->
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Approved Properties</p>
                                <h4 class="my-1 text-success">
                                    <?php
                                    $res_auth = mysqli_query($link, "SELECT * FROM properties WHERE moderation_status = 'approved'");
                                    echo mysqli_num_rows($res_auth);
                                    ?>
                                </h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-check-circle'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           

            <!-- Total Customer -->
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Customers</p>
                                <h4 class="my-1 text-info">
                                    <?php
                                    // Assuming 'upload' table stores customer data as per previous dashboard logic ('Total Client')
                                    $res_cust = mysqli_query($link, "SELECT * FROM upload");
                                    echo mysqli_num_rows($res_cust);
                                    ?>
                                </h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-group'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<?php

include('footer.php');

?>