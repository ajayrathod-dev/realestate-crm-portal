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

$sql44 = "SELECT * FROM admin ";

$result44 = mysqli_query($link, $sql44);

if ($result44->num_rows > 0) {

	while ($row44 = $result44->fetch_assoc()) {

		$unit_rate = mysqli_real_escape_string($link, $row44['unit_rate'] ?? '');


	}

} 



include('header.php');



?>


<div class="page-wrapper">
    <div class="page-content">
        	<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                   	    
               
				    
                   <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Total Staff</p>
									<h4 class="my-1 text-info"><?php

											$result2 = mysqli_query($link,"SELECT * FROM admin");

							         	   echo $rows = mysqli_num_rows($result2);

								


                                      ?>
									</h4>
									
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-cart'></i>
								</div>
							</div>
						</div> 
					 </div>
				   </div>
				     <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Total Client</p>
									<h4 class="my-1 text-info"><?php

											$result2 = mysqli_query($link,"SELECT * FROM upload");

							         	   echo $rows = mysqli_num_rows($result2);

								


                                      ?>
									</h4>
									
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-cart'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>
				   <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Total Subscriptions</p>
									<h4 class="my-1 text-info"><?php

											$result2 = mysqli_query($link,"SELECT * FROM activate_status");

							         	   echo $rows = mysqli_num_rows($result2);

								


                                      ?>
									</h4>
									
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-cart'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>
				   <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Total Admin</p>
									<h4 class="my-1 text-info"><?php

											$result2 = mysqli_query($link,"SELECT * FROM admin");

							         	   echo $rows = mysqli_num_rows($result2);

								


                                      ?>
									</h4>
									
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-cart'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>

				   <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Total Property</p>
									<h4 class="my-1 text-info"><?php

											$result2 = mysqli_query($link,"SELECT * FROM properties");

							         	   echo $rows = mysqli_num_rows($result2);


                                      ?>
									</h4>
									
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-home'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>

				   <!-- Agent Cards -->
				   <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-primary">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Total Agents</p>
									<h4 class="my-1 text-primary"><?php
											$result_agents = mysqli_query($link, "SELECT * FROM users_ap");
											echo mysqli_num_rows($result_agents);
                                      ?>
									</h4>
								</div>
								<div class="widgets-icons-2 rounded-circle bg-primary text-white ms-auto"><i class='bx bxs-group'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>

				   <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-success">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Approved Agents</p>
									<h4 class="my-1 text-success"><?php
											$result_approved = mysqli_query($link, "SELECT * FROM users_ap WHERE approval_status='approved'");
											echo mysqli_num_rows($result_approved);
                                      ?>
									</h4>
								</div>
								<div class="widgets-icons-2 rounded-circle bg-success text-white ms-auto"><i class='bx bxs-check-circle'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>

				   <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-warning">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Pending Agents</p>
									<h4 class="my-1 text-warning"><?php
											$result_pending = mysqli_query($link, "SELECT * FROM users_ap WHERE approval_status='pending' OR approval_status IS NULL");
											echo mysqli_num_rows($result_pending);
                                      ?>
									</h4>
								</div>
								<div class="widgets-icons-2 rounded-circle bg-warning text-dark ms-auto"><i class='bx bxs-time'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>
				
				   
				  
          </div>

          <!-- Growth Chart -->
          <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Monthly Growth Analytics (Last 6 Months)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:450px; width:100%">
                            <canvas id="growthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          
        

 

<!-- Ensure to include Chart.js -->
<?php
// Prepare data for the chart
$months = [];
$prop_counts = [];
$cust_counts = [];
$agent_counts = [];

for ($i = 5; $i >= 0; $i--) {
    $month_key = date('Y-m', strtotime("-$i months")); // Format for DB query
    $month_label = date('M Y', strtotime("-$i months")); // Format for Chart Label
    $months[] = $month_label;

    // 1. Properties Count
    $sql_prop = "SELECT COUNT(*) as count FROM properties WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month_key'";
    $res_prop = mysqli_query($link, $sql_prop);
    $prop_counts[] = ($res_prop) ? mysqli_fetch_assoc($res_prop)['count'] : 0;

    // 2. Customers (Upload) Count
    $sql_cust = "SELECT COUNT(*) as count FROM upload WHERE DATE_FORMAT(date, '%Y-%m') = '$month_key'";
    $res_cust = mysqli_query($link, $sql_cust);
    $cust_counts[] = ($res_cust) ? mysqli_fetch_assoc($res_cust)['count'] : 0;

    // 3. Agents Count
    $sql_agent = "SELECT COUNT(*) as count FROM users_ap WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month_key'";
    $res_agent = mysqli_query($link, $sql_agent);
    $agent_counts[] = ($res_agent) ? mysqli_fetch_assoc($res_agent)['count'] : 0;
}
?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/plugins/chartjs/js/Chart.min.js"></script>
<script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>

<?php
// --- FETCH DATA FOR APEXCHARTS ---
// Helper to safely fetch data only if column exists
function get_chart_data($link, $table, $column, $where = "", $limit = "") {
    $labels = [];
    $data = [];
    
    // Check if column exists
    $col_check = mysqli_query($link, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if (mysqli_num_rows($col_check) == 0) {
        return ['labels' => [], 'data' => []];
    }

    $sql = "SELECT `$column`, COUNT(*) as count FROM `$table` $where GROUP BY `$column` ORDER BY count DESC $limit";
    $res = mysqli_query($link, $sql);
    
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $lbl = $row[$column] ? $row[$column] : 'Unknown/Pending';
            $labels[] = $lbl;
            $data[] = $row['count'];
        }
    }
    return ['labels' => $labels, 'data' => $data];
}

// 1. Staff Performance
$staff = get_chart_data($link, 'upload', 'by', "", "LIMIT 10");
$staff_labels = $staff['labels'];
$staff_data = $staff['data'];

// 2. Property Type
$type = get_chart_data($link, 'upload', 'property_type', "WHERE property_type != ''");
$type_labels = $type['labels'];
$type_data = $type['data'];

// 3. Sale vs Rent
$sale = get_chart_data($link, 'upload', 'sale_rent', "WHERE sale_rent != ''");
$sale_labels = $sale['labels'];
$sale_data = $sale['data'];

// 4. Status
$status = get_chart_data($link, 'upload', 'status');
$status_labels = $status['labels'];
$status_data = $status['data'];

// 5. City
$city = get_chart_data($link, 'upload', 'city_location', "WHERE city_location != ''", "LIMIT 10");
$city_labels = $city['labels'];
$city_data = $city['data'];
?>

<script>
$(document).ready(function() {
    // --- 1. Staff Performance (Bar Chart) ---
    var optionsStaff = {
        series: [{
            name: 'Entries Added',
            data: <?php echo json_encode($staff_data); ?>
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: <?php echo json_encode($staff_labels); ?>,
        },
        colors: ['#0d6efd']
    };
    var chartStaff = new ApexCharts(document.querySelector("#chartStaff"), optionsStaff);
    chartStaff.render();

    // --- 2. Property Type (Pie Chart) ---
    var optionsType = {
        series: <?php echo json_encode($type_data); ?>,
        chart: {
            width: 380,
            type: 'pie',
        },
        labels: <?php echo json_encode($type_labels); ?>,
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    var chartType = new ApexCharts(document.querySelector("#chartType"), optionsType);
    chartType.render();

    // --- 3. Sale vs Rent (Donut Chart) ---
    var optionsSale = {
        series: <?php echo json_encode($sale_data); ?>,
        chart: {
            width: 380,
            type: 'donut',
        },
        labels: <?php echo json_encode($sale_labels); ?>,
        colors: ['#198754', '#ffc107', '#0dcaf0'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    var chartSaleRent = new ApexCharts(document.querySelector("#chartSaleRent"), optionsSale);
    chartSaleRent.render();

    // --- 4. Status (RadialBar or Donut) ---
    var optionsStatus = {
        series: <?php echo json_encode($status_data); ?>,
        chart: {
            width: 380,
            type: 'donut',
        },
        labels: <?php echo json_encode($status_labels); ?>,
        colors: ['#0d6efd', '#dc3545', '#ffc107'],
        plotOptions: {
          pie: {
            donut: {
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total'
                }
              }
            }
          }
        }
    };
    var chartStatus = new ApexCharts(document.querySelector("#chartStatus"), optionsStatus);
    chartStatus.render();

    // --- 5. City Distribution (Column Chart) ---
    var optionsCity = {
        series: [{
            name: 'Properties',
            data: <?php echo json_encode($city_data); ?>
        }],
        chart: {
            height: 350,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val;
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
        },
        xaxis: {
            categories: <?php echo json_encode($city_labels); ?>,
            position: 'top',
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            crosshairs: {
                fill: {
                    type: 'gradient',
                    gradient: {
                        colorFrom: '#D8E3F0',
                        colorTo: '#BED1E6',
                        stops: [0, 100],
                        opacityFrom: 0.4,
                        opacityTo: 0.5,
                    }
                }
            },
            tooltip: {
                enabled: true,
            }
        },
        yaxis: {
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            labels: {
                show: false,
                formatter: function (val) {
                    return val;
                }
            }
        },
        title: {
            text: 'Top Locations',
            floating: true,
            offsetY: 330,
            align: 'center',
            style: {
                color: '#444'
            }
        }
    };
    var chartCity = new ApexCharts(document.querySelector("#chartCity"), optionsCity);
    chartCity.render();
});

    // Existing Chart.js code...
    var ctx = document.getElementById('growthChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Properties Added',
                data: <?php echo json_encode($prop_counts); ?>,
                borderColor: '#0d6efd', // Primary Blue
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                pointBackgroundColor: '#0d6efd',
                tension: 0.4,
                fill: true
            }, {
                label: 'Customers Added',
                data: <?php echo json_encode($cust_counts); ?>,
                borderColor: '#198754', // Success Green
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                pointBackgroundColor: '#198754',
                tension: 0.4,
                fill: true
            }, {
                label: 'Agents Registered',
                data: <?php echo json_encode($agent_counts); ?>,
                borderColor: '#ffc107', // Warning Yellow
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                pointBackgroundColor: '#ffc107',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f0f0f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>



<?php

include('footer.php');

?>