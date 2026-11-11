<?php

session_start();

include("connection.php");

$current_time = date("M d, Y H:i:s");



if (isset($_COOKIE['uuid'])) {

    $u_uname = mysqli_real_escape_string($link, $_COOKIE['uuid']);

} else {

    $u_uname = '';

}



 $sql4 = "SELECT * FROM otp_ap WHERE uuid='$u_uname' AND status='verified'";

$result4 = mysqli_query($link, $sql4);

if ($result4->num_rows > 0) {

    while ($row4 = $result4->fetch_assoc()) {

        $u_uname = mysqli_real_escape_string($link, $row4['user_id']);

         $otp = mysqli_real_escape_string($link, $row4['otp']);



    }

} else {

    header("location:logout.php");

    exit;

}

 



$otps = $_POST['otp'];


if (isset($_POST["login"])) {
    

        $sql = "SELECT * FROM otp_ap WHERE otp='$otps' ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($link, $sql);
        if ($result->num_rows > 0) {
            while ($usr = $result->fetch_assoc()) {
                $verified_user_id = $usr['user_id'];
                
                // Check user profile and approval status
                $user_sql = "SELECT name, approval_status FROM users_ap WHERE user_id='$verified_user_id' LIMIT 1";
                $user_result = mysqli_query($link, $user_sql);
                
                if ($user_result && $user_result->num_rows > 0) {
                    $user_row = $user_result->fetch_assoc();
                    $user_name = $user_row['name'];
                    $approval_status = $user_row['approval_status'] ?? 'pending';
                    
                    // Check if profile is incomplete
                    if ($user_name === '-' || $user_name === '' || $user_name === null) {
                        echo "<script>window.location.href='agent_profile_complete.php'</script>";
                    }
                    // Check if not approved
                    else if ($approval_status !== 'approved') {
                        echo "<script>window.location.href='agent_pending_approval.php'</script>";
                    }
                    // Approved - go to dashboard
                    else {
                        echo "<script>window.location.href='dashboard.php?success=1'</script>";
                    }
                } else {
                    // User not found in users_ap, redirect to profile completion
                    echo "<script>window.location.href='agent_profile_complete.php'</script>";
                }
            
            }
        } else {


    

           echo "<script>window.location.href='otp.php?error=1'</script>";
        }

}




?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="lg.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />

	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<link href="assets/css/splash.css" rel="stylesheet">
	<title>Agent Panel - Verify OTP</title>



</head>

<body style="background: radial-gradient(circle at center, #e6cfa7 0%, #b87b3e 60%, #5a3e22 100%);">



	<!--wrapper--> 
	<div class="wrapper valign-wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="card">
							<div class="card-body">
								<div class="mb-4 text-center">
									<img src="property.png" style="width:100px;">
								</div>

									<div class="text-center">
										<h3 class="">Verify OTP</h3>
										
									</div>

									<div class="form-body">
										<form class="row g-3" method="post" action="">
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label"> OTP</label>
												<input type="number" name="otp" class="form-control" id="inputEmailAddress" required>
											</div>
										
											
											<div class="col-12">
												<div class="d-grid">
													<!-- 	<input type="hidden" name="login">
													<button type="submit" class="btn btn-primary" name=""><i class="bx bxs-lock-open"></i>Login</button> -->

													<input type="submit" class="btn btn-primary" name="login" Value="Verify OTP">
												</div>
											</div>
										
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->




				<!--start switcher-->
				<div class="switcher-wrapper">
					<div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
					</div>
					<div class="switcher-body">
						<div class="d-flex align-items-center">
							<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
							<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
						</div>
						<hr />
						<h6 class="mb-0">Theme Styles</h6>
						<hr />
						<div class="d-flex align-items-center justify-content-between">
							<script type="text/javascript">
								window.onload = function() {
									<?php
									$time = date("H");
									$timezone = date("e");
									if ($time < "7" || $time > "19") {
									?>
										document.getElementById("darkmode").click();
									<?php
									} else {
									?>
										document.getElementById("lightmode").click();
									<?php
									}
									?>




									<?php

									if ($_GET['error'] == 13 && $_GET['error'] != '') {
									?>
										Lobibox.notify('error', {
											pauseDelayOnHover: true,
											continueDelayOnInactiveTab: false,
											position: 'top right',
											icon: 'bx bx-x-circle',
											msg: 'Invalid User ID or Password.'
										});



									<?php
									}
									?>

								
								};
							</script>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode">
								<label class="form-check-label" for="lightmode">Light</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
								<label class="form-check-label" for="darkmode">Dark</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
								<label class="form-check-label" for="semidark">Semi Dark</label>
							</div>
						</div>
						<hr />
						<div class="form-check">
							<input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
							<label class="form-check-label" for="minimaltheme">Minimal Theme</label>
						</div>
						<hr />
						<h6 class="mb-0">Header Colors</h6>
						<hr />
						<div class="header-colors-indigators">
							<div class="row row-cols-auto g-3">
								<div class="col">
									<div class="indigator headercolor1" id="headercolor1"></div>
								</div>
								<div class="col">
									<div class="indigator headercolor2" id="headercolor2"></div>
								</div>
								<div class="col">
									<div class="indigator headercolor3" id="headercolor3"></div>
								</div>
								<div class="col">
									<div class="indigator headercolor4" id="headercolor4"></div>
								</div>
								<div class="col">
									<div class="indigator headercolor5" id="headercolor5"></div>
								</div>
								<div class="col">
									<div class="indigator headercolor6" id="headercolor6"></div>
								</div>
								<div class="col">
									<div class="indigator headercolor7" id="headercolor7"></div>
								</div>
								<div class="col">
									<div class="indigator headercolor8" id="headercolor8"></div>
								</div>
							</div>
						</div>
						<hr />
						<h6 class="mb-0">Sidebar Colors</h6>
						<hr />
						<div class="header-colors-indigators">
							<div class="row row-cols-auto g-3">
								<div class="col">
									<div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
								</div>
								<div class="col">
									<div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
								</div>
								<div class="col">
									<div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
								</div>
								<div class="col">
									<div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
								</div>
								<div class="col">
									<div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
								</div>
								<div class="col">
									<div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
								</div>
								<div class="col">
									<div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
								</div>
								<div class="col">
									<div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
								</div>
							</div>
						</div>
					</div>





				<!-- Bootstrap JS -->
				<script src="assets/js/bootstrap.bundle.min.js"></script>
				<!--plugins-->
				<script src="assets/js/jquery.min.js"></script>
				<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
				<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
				<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
				<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
				<script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
				<script src="assets/plugins/chartjs/js/Chart.min.js"></script>
				<script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
				<script src="assets/js/index.js"></script>
				<!--app JS-->
				<script src="assets/js/app.js"></script>
				<!--notification js -->
				<script src="assets/plugins/notifications/js/lobibox.min.js"></script>
				<script src="assets/plugins/notifications/js/notifications.min.js"></script>
				<script src="assets/plugins/notifications/js/notification-custom-script.js"></script>
				<!--app JS-->
				<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
				<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
				<!--app JS-->
				<!--end switcher-->

</body>

<script>
	$(document).ready(function() {
		$('#example').DataTable();
	});
</script>
<script>
	$(document).ready(function() {
		var table = $('#example2').DataTable({
			lengthChange: false,
			buttons: ['copy', 'excel', 'pdf', 'print']
		});

		table.buttons().container()
			.appendTo('#example2_wrapper .col-md-6:eq(0)');
	});
</script>
<!--Password show & hide js -->
<script>
	$(document).ready(function() {
		$("#show_hide_password a").on('click', function(event) {
			event.preventDefault();
			if ($('#show_hide_password input').attr("type") == "text") {
				$('#show_hide_password input').attr('type', 'password');
				$('#show_hide_password i').addClass("bx-hide");
				$('#show_hide_password i').removeClass("bx-show");
			} else if ($('#show_hide_password input').attr("type") == "password") {
				$('#show_hide_password input').attr('type', 'text');
				$('#show_hide_password i').removeClass("bx-hide");
				$('#show_hide_password i').addClass("bx-show");
			}
		});
	});
</script>
<!--app JS-->


</body>

</html>
