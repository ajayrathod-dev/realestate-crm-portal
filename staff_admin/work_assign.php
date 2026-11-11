<?php

include('header.php');


date_default_timezone_set('Asia/Kolkata'); // Set to your timezone
$currentDateTime = date("Y-m-d H:i:s"); // Format: 2025-06-09 11:45:00
	

?>

<style>
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  .switch input {
    display: none;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked+.slider {
    background-color: #2196F3;
  }

  input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>
<script>
  function myfunction(str, str1) {
    

    alert("DO YOU WANT TO CHANGE STAUTS");

    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("prog_show").innerHTML = xmlhttp.responseText;
        location.reload();

      }
    }
    xmlhttp.open("GET", "show_program.php?uid=" + str + "&status=" + str1, true);
    xmlhttp.send();
  }
</script>
<div class="page-wrapper">
   <div class="page-content">

                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">+ Assign Work/Request</button>
                     <div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Assign Work/Request</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <form class="row g-3" action="work_assign1.php" method="post" enctype="multipart/form-data">
                                   <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">Work/Request Registered By </label>
                                     <input type="number" class="form-control" id="inputFirstName" name="work_reg_by" value="<?php echo $u_uname;?>" required readonly>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">Work/Request Assigned to</label>
                                     <select  class="form-control select2" id="workAssignTo" name="work_assign_to" required>
                                         <option>--select--</option>
                                         <?php 
                                            $sql45 = "SELECT * FROM admin WHERE status=0";
                                            $result45 = mysqli_query($link, $sql45);
                                            if ($result45->num_rows > 0) {
 
                                          	while ($row45 = $result45->fetch_assoc()) {
                                     		?>
                                     		
                                     		<option value="<?php echo $row45['user_id']; ?>"><?php echo $row45['emp_name']; ?> (<?php echo $row45['designation']; ?>)</option>
                                      <?php	
                                           }
                                         }
                                         
                                         
                                         ?>
                                        
                                     </select>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">Work/Request Description</label>
                                     <textarea class="form-control" id="inputFirstName" name="work_description" required></textarea>
                                     
                                  </div>
                                 
                                  <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">Expected Date Of Completion/Action</label>
                                     <input type="date" class="form-control" id="inputFirstName" name="edc" required>
                                  </div>
                                  
                                  <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">Work/Request In Number</label>
                                     <input type="number" class="form-control" id="inputFirstName" name="win" required>
                                  </div>
                                     <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">Work/Request About</label>
                                     <select class="form-control" id="inputFirstName" name="work_about" required>
                                         <option>Account Opening</option>
                                         <option>Deposit</option>
                                         <option>Loan Disbursement</option>
                                         <option>Loan Recovery</option>
                                         <option>Leave Application</option>
                                         <option>Resignation</option>
                                         <option>Advance Or Loan Against Pay</option>
                                         <option>Shares Or Amount Or Return</option>
                                         <option>Goods Repair Or Purchase Request</option>
                                         <option>IT Software Related Work</option>
                                         <option>Amount Pickup And Drop</option>
                                         <option>Other</option>
                                     </select>         
                                     
                                  </div>
                                    <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">दिनांक</label>
                                     <input type="datetime-local" class="form-control" id="datetimeInput" value="<?php echo $currentDateTime; ?>" name="det" required step="1">
                                  </div>
                               
                              
                                  <div class="col-12" align="center">
                                    <button type="submit" class="btn btn-outline-primary px-5" onclick="return confirm('Do you want to submit?')">जतन करा </button>
                                  </div>
                                </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          
                          </div>
                        </div>
                      </div>
                    </div>
                    

      <div class="card mt-2">
         <div class="card-body">
            <div class="table-responsive">
               <table id="example2" class="table table-striped table-bordered">
                   <thead>
                   <tr>
                     <th>Sr.No</th> 
                     <th>Work/Request In Number</th>
                     <th>Work/Request Registered By</th>
                     <th>Work/Request Assigned to</th>
                     <th>Work/Request Description</th> 
                     <th>Expected Date Of Completion/Action</th>
                      <th>Work/Request About</th>
                     <th>By</th>
                     <th>Date</th>
                     <th>Latest Remark</th>
                     <th>Work/Request Status</th>
                     <th>Action</th>
                     
                  
                   </tr>
                   </thead>
                 <tbody>
                   <?php 
                   $sr=1;
                    $sql451 = "SELECT * FROM work_assign WHERE `by`='$u_uname' AND status=0 order by id desc limit 500";
                    $result451 = mysqli_query($link, $sql451);
                    if ($result451->num_rows > 0) {
                    while ($row451 = $result451->fetch_assoc()) {
                         $work_registered_by=$row451['work_registered_by'];
                          $work_assigned_to =$row451['work_assigned_to'];
                            $ids =$row451['id'];
                        
                    ?>
                          <tr>
                              <td><?php echo $sr;  ?></td>
                                <td><?php echo $row451['work_in_no']; ?></td>
                               <td><?php 
                              
                                    $sql452 = "SELECT * FROM admin WHERE user_id='$work_registered_by'";
                                            $result452 = mysqli_query($link, $sql452);
                                            if ($result452->num_rows > 0) {
 
                                          	while ($row452 = $result452->fetch_assoc()) {
                                     		
                                     		 echo $row452['emp_name']."(".$row452['designation'].")";                       
                                           }
                                         }
                              
                              
                              
                              
                              ?></td>
                               <td><?php
                              
                                    $sql4521 = "SELECT * FROM admin WHERE user_id='$work_assigned_to'";
                                            $result4521 = mysqli_query($link, $sql4521);
                                            if ($result4521->num_rows > 0) {
 
                                          	while ($row4521 = $result4521->fetch_assoc()) {
                                     		
                                     			 echo $row4521['emp_name']."(".$row4521['designation'].")";                                          
                                           }
                                         }
                              
                              
                              
                              
                              ?></td>
                               <td><?php echo $row451['work_description']; ?></td>
                               <td><?php echo $row451['expected_date_of_completion']; ?></td>
                                  <td><?php echo $row451['about_work']; ?></td>
                              
                             
                              <td><?php
                              $byid = $row451['by'];
                                    $sql45 = "SELECT * FROM admin WHERE user_id='$byid'";
                                            $result45 = mysqli_query($link, $sql45);
                                            if ($result45->num_rows > 0) {
 
                                          	while ($row45 = $result45->fetch_assoc()) {
                                     		
                                     		 echo $row45['emp_name'];                        
                                           }
                                         }
                              
                              
                              
                              
                              ?></td>
                              <td><?php 
$datetime = $row451['date']; // 2:30 PM
$formatted = date("d-m-Y h:i A", strtotime($datetime));
echo $formatted;
                               ?></td>
                               <td>
                               <?php
                                $sql4512 = "SELECT * FROM remark_for_my_work WHERE assign_work_id='$ids' ORDER BY id DESC LIMIT 1";
                                $result4512 = mysqli_query($link, $sql4512);
                                if ($result4512->num_rows > 0) {
                                 while ($row4512 = $result4512->fetch_assoc())
                                 {
                                    echo $row4512['remark'];
                                 }
                                
                                 }
                                
                               
                               
                               ?>
                               </td>
                               
                              
                          
                             <td>
                                  <?php
                                   if($row451['status']==0)
                                   {
                                      
                                        echo " <span class='badge bg-warning'>Pending</span>" ;  
                                      }
                                     else if($row451['status']==1)
                                   {
                                         echo " <span class='badge bg-success'>Completed/Approved </span>" ;      
                                   }
                                   else
                                   {
                                       ?>
                                    <span class="badge bg-danger">Cancel/Reject</span>
                                    <?php   
                                   }
                                   
                                  
                                   
                                   ?>
                               
                              
                             </td>
                            
                               
                               <td><a href="remark_for_assign_work.php?id=<?php echo $row451['id']; ?>" class="btn btn-outline-primary px-5">View Remark</a></td>

                              
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
</div>
<?php
   include('footer.php');
?>