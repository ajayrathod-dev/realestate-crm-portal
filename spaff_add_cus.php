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
    header("location:logout.php");
    exit;
}

include('header.php');
?>

<style>
    .blink_me {
        animation: blinker 1s linear infinite;
        color: white;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    .rainbow {
        text-align: center;
        text-decoration: none;
        font-size: 14px;
        font-family: monospace;
        letter-spacing: 0px;
    }

    .rainbow_text_animated {
        background: linear-gradient(to right, #6666ff, #0099ff, #00ff00, #ff3399, #6666ff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: rainbow_animation 6s ease-in-out infinite;
        background-size: 400% 100%;
    }

    @keyframes rainbow_animation {
        0%, 100% {
            background-position: 0 0;
        }

        50% {
            background-position: 100% 0;
        }
    }
</style>

<div class="page-wrapper">
    <div class="page-content">


        <div class="col-lg-12">
            <div class="alert alert-dark border-0 bg-dark alert-dismissible fade show" style="height:60px;">
                <div class="text-white"><h5 class="text-white">Property History</h5></div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                           
                            <tr>
                                
                                <th>Client ID</th>
                                <th>Customer Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Property Type</th>
                                <th>Sale/Rent</th>
                                <th>Expected Rent</th>
                                <th>Location Area</th>
                                <th>City Location</th>
                                <th>City Area</th>
                                <th>Agri District</th>
                                <th>Agri Taluka</th>
                                <th>Agri Property Size</th>
                                <th>Land Property Type</th>
                                <th>Specification</th>
                                <th>Status</th>
                                <th>By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr = 1;
                            $sql1 = "SELECT * FROM upload ORDER BY id DESC";
                            $result1 = mysqli_query($link, $sql1);
                            if ($result1->num_rows > 0) {
                                while ($usr1 = mysqli_fetch_array($result1)) {
                            ?>
                                <tr>
                                    
                                    <td><?php echo $usr1['client_id']; ?></td>
                                    <td><?php echo $usr1['customer_name']; ?></td>
                                    
                                    <td><?php echo $usr1['address']; ?></td>
                                    <td><?php echo $usr1['contact_no']; ?></td>
                                  
                                    <td><?php echo $usr1['property_type']; ?></td>
                                    <td><?php echo $usr1['sale_rent']; ?></td>
                                    <td><?php echo $usr1['expected_rent']; ?></td>
                                  
                                    <td><?php echo $usr1['location_area']; ?></td>
                                    <td><?php echo $usr1['city_location']; ?></td>
                                    <td><?php echo $usr1['city_area']; ?></td>
                                    <td><?php echo $usr1['agri_district']; ?></td>
                                    <td><?php echo $usr1['agri_taluka']; ?></td>
                                    <td><?php echo $usr1['agri_property_size']; ?></td>
                                    <td><?php echo $usr1['land_property_type']; ?></td>
                                    <td><?php echo $usr1['specification']; ?></td>
                                    <td>
                                        <?php $st=$usr1['status'];
                               
                                   if($st=='Pending')
                                   {
                                       ?>
                                       <form action="user_status.php" method="post">
                                         <select name="lev">
                                             <option>Pending</option>
                                             <option>Reject</option>
                                             <option>Active</option>
                                         </select>
                                         <input type="hidden" value="<?php echo $usr1['id'];  ?>" name="id"/>
                                         <input type="submit" value="Submit" />
                                       <?php
                                   }
                                    else if($st=='Reject')
                                   {
                                       ?>
                                       <span class='badge bg-danger'><?php echo $st; ?></span>
                                       <?php
                                   }
                                   else
                                   {
                                       ?>
                                       <span class='badge bg-success'><?php echo $st; ?></span>
                                       <?php
                                   }
                                 
                              
                                ?>
                                    </td>
                                    <td><?php echo $usr1['by']; ?></td>
                                    <td><?php echo $usr1['date']; ?></td>
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
