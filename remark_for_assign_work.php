<?php

include('header.php');

		date_default_timezone_set('Asia/Kolkata'); // Set to your timezone
$currentDateTime = date("Y-m-d H:i:s"); // Format: 2025-06-09 11:45:00

$id=$_GET['id'];


                              
                                            $sql452 = "SELECT * FROM work_assign WHERE id='$id'";
                                            $result452 = mysqli_query($link, $sql452);
                                            if ($result452->num_rows > 0) {
 
                                          	while ($row452 = $result452->fetch_assoc()) {
                                     		
                                     		 $work_registered_by=$row452['work_registered_by'];
                                     		 $work_assigned_to= $row452['work_assigned_to'];
                                     		 $work_description= $row452['work_description'];
                                     		 $expected_date_of_completion= $row452['expected_date_of_completion'];   
                                     		 $about_work= $row452['about_work'];   
                                     		 $status= $row452['status'];   
                                           }
                                         }
                                         
                                          $sql4520 = "SELECT * FROM admin WHERE user_id='$work_registered_by'";
                                            $result4520 = mysqli_query($link, $sql4520);
                                            if ($result4520->num_rows > 0) {
 
                                          	while ($row4520 = $result4520->fetch_assoc()) {
                                     		
                                     		 $emp_name=$row4520['emp_name'];
                                     	    
                                           }
                                         }
                              
                                          $sql45203 = "SELECT * FROM admin WHERE user_id='$work_assigned_to'";
                                            $result45203 = mysqli_query($link, $sql45203);
                                            if ($result45203->num_rows > 0) {
 
                                          	while ($row45203 = $result45203->fetch_assoc()) {
                                     		
                                     		 $emp_name1=$row45203['emp_name'];
                                     	    
                                           }
                                         }
                              
                              
                              
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

      <div class="card">
         <div class="card-body">
                        
                              <form class="row g-3" action="remark_for_assign_work1.php" method="post" enctype="multipart/form-data">
     <div class="col-md-12">
    <div class="mb-2">
        <strong>Work Registered By:</strong> <?php echo htmlspecialchars($emp_name); ?>
    </div>
    <div class="mb-2">
        <strong>Work Assigned To:</strong> <?php echo htmlspecialchars($emp_name1); ?>
    </div>
      <div class="mb-2">
        <strong>Work Description:</strong> <?php echo htmlspecialchars($work_description); ?>
    </div>
    <div class="mb-2">
        <strong>Work About:</strong> <?php echo htmlspecialchars($about_work); ?>
    </div>
       <div class="mb-2">
        <strong>Work Status:</strong>  <?php
                                   if($status==0)
                                   {
                                      
                                        echo " <span class='badge bg-warning'>Pending</span>" ;  
                                      }
                                     else if($status==1)
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
    </div>
    <div class="mb-2">
        <strong>Expected Date of Completion:</strong> <?php echo htmlspecialchars($expected_date_of_completion); ?>
    </div>
</div>
                                 
                                   <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">Remark</label>
                                     <input type="hidden" class="form-control" id="inputFirstName" name="id" value="<?php echo $id;?>" required readonly>
                                     
                                                <input type="text" class="form-control" id="inputFirstName" name="remark" required>
                                  </div>
                                   <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">दिनांक</label>
                                     <input type="datetime-local" class="form-control" id="datetimeInput" value="<?php echo $currentDateTime; ?>" name="det" required step="1">
                                  </div>
                               
                              
                                  <div class="col-12" align="center">
                                    <button type="submit" class="btn btn-outline-primary px-5">जतन करा </button>
                                  </div>
                                </form>
                        
                         
                        </div>
                      </div>
                  

      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="example2" class="table table-striped table-bordered">
                   <thead>
                   <tr>
                      <th>Sr.No</th> 
                     <th>Remark</th>
                     <th>By</th>
                     <th>Date</th>
                     
                  
                   </tr>
                   </thead>
                 <tbody>
                   <?php 
                   $sr=1;
                    $sql451 = "SELECT * FROM remark_for_my_work WHERE assign_work_id='$id' order by id desc limit 500";
                    $result451 = mysqli_query($link, $sql451);
                    if ($result451->num_rows > 0) {
                    while ($row451 = $result451->fetch_assoc()) {
                   
                         ?>
                          <tr>
                              <td><?php echo $sr;  ?></td>
                              
                               <td><?php echo $row451['remark']; ?></td>
                             
                              
                             
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