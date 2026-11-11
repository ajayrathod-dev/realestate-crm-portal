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

            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">+ Add Survey Requirements</button>
            <button type="button" class="btn btn-success mb-2 ms-2" data-bs-toggle="modal" data-bs-target="#constructionModal">+ Add Construction</button>
            <div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Survey Requirements</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" action="add_pro1.php" method="post" enctype="multipart/form-data">
  <div class="col-md-6">
  <label class="form-label">Project Code</label>                              
<select class="form-control" name="project_code" id="project_code" required>
    <option value="">-- Select Project --</option>
    <?php
    $sql2 = "SELECT * FROM project_details ORDER BY id DESC";
    $result2 = mysqli_query($link, $sql2);
    if ($result2 && mysqli_num_rows($result2) > 0) {
        while ($row2 = mysqli_fetch_assoc($result2)) {
            ?>
                <option value="<?php echo $row2['project_code'];?>"> <?php echo $row2['project_code'];?> (<?php  echo $row2['project_name'];?>)</option>
                <?php
        }
    }
    ?>
</select>
</div>
<div class="col-md-6">
  <label class="form-label">Part No</label>

  <select class="form-control" name="part_no" id="part_no" required>
    <option value="">-- Select Part --</option>
</select>
</div>

<!-- NEW: Structure Type Dropdown -->
<div class="col-md-6">
  <label class="form-label">Structure Type <span class="text-danger">*</span></label>
  <select class="form-control" name="structure_type" required>
    <option value="">-- Select Structure --</option>
    <option value="MJB">MJB</option>
    <option value="MNB">MNB</option>
    <option value="SLAB">SLAB</option>
    <option value="Box">Box</option>
    <option value="HPC">HPC</option>
    <option value="ROB">ROB</option>
  </select>
</div>

<!-- NEW: Scope Type Dropdown -->
<div class="col-md-6">
  <label class="form-label">Scope Type <span class="text-danger">*</span></label>
  <select class="form-control" name="scope_type" required>
    <option value="">-- Select Scope --</option>
    <option value="Reconstruction">Reconstruction</option>
    <option value="Widening">Widening</option>
    <option value="Repair">Repair</option>
    <option value="Retain">Retain</option>
    <option value="NEW">NEW</option>
  </select>
</div>

<div class="col-md-6">
  <label class="form-label">Chainage</label>
  <input type="text" class="form-control" name="chainage">
</div>
<div class="col-md-6">
  <label class="form-label">Existing</label>
  <input type="text" class="form-control" name="existing">
</div>
<div class="col-md-6">
  <label class="form-label">Proposed</label>
  <input type="text" class="form-control" name="proposed">
</div>
<div class="col-md-6">
  <label class="form-label">No's</label>
  <input type="text" class="form-control" name="nos">
</div>
<div class="col-md-6">
  <label class="form-label">Span</label>
  <input type="text" class="form-control" name="span">
</div>
<div class="col-md-6">
  <label class="form-label">Length</label>
  <input type="text"  class="form-control" name="length">
</div>
<div class="col-md-6">
  <label class="form-label">Remarks</label>
  <input type="text" class="form-control" name="remarks">
</div>
<div class="col-md-6">
  <label class="form-label">Drawing</label>
  <input type="text" class="form-control" name="drawing">
</div>
<div class="col-md-6">
  <label class="form-label">Issues</label>
  <input type="text" class="form-control" name="issues">
</div>
<div class="col-md-6">
  <label class="form-label">Foundation</label>
  <input type="text" class="form-control" name="foundation">
</div>
<div class="col-md-6">
  <label class="form-label">Sub Structure</label>
  <input type="text" class="form-control" name="sub_structure">
</div>
<div class="col-md-6">
  <label class="form-label">Superstructure</label>
  <input type="text" class="form-control" name="superstructure">
</div>
<div class="col-md-6">
  <label class="form-label">Complete</label>
  <input type="text" class="form-control" name="complete">
</div>

 <div class="col-12" align="center">
                                    <button type="submit" class="btn btn-outline-primary px-5">Submit</button>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Construction Modal -->
            <div class="modal fade" id="constructionModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="constructionModalTitle">Add Construction Progress</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="constructionForm" action="construction_handler.php" method="post">
                                <input type="hidden" name="action" id="constructionAction" value="add">
                                <input type="hidden" name="id" id="constructionId" value="">
                                
                                <!-- Project Code -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Project Code <span class="text-danger">*</span></label>
                                        <select class="form-select" name="project_code" id="constr_project_code" required>
                                            <option value="">-- Select Project --</option>
                                            <?php
                                            $sql_pd = "SELECT * FROM project_details ORDER BY project_code";
                                            $result_pd = mysqli_query($link, $sql_pd);
                                            while ($row_pd = mysqli_fetch_assoc($result_pd)) {
                                                echo '<option value="'.htmlspecialchars($row_pd['project_code']).'">'.htmlspecialchars($row_pd['project_code']).' - '.htmlspecialchars($row_pd['project_name']).'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Scope Section -->
                                <div class="card mb-3">
                                    <div class="card-header bg-warning text-dark"><strong>Scope (In Km)</strong></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label">PQC</label>
                                                <input type="number" step="0.001" class="form-control form-control-sm" name="pqc" id="constr_pqc" value="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Overlay</label>
                                                <input type="number" step="0.001" class="form-control form-control-sm" name="overlay" id="constr_overlay" value="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Total</label>
                                                <input type="number" step="0.001" class="form-control form-control-sm" name="total" id="constr_total" value="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">DLP Length</label>
                                                <input type="number" step="0.01" class="form-control form-control-sm" name="dlp_length" id="constr_dlp_length" value="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Forest Length</label>
                                                <input type="number" step="0.01" class="form-control form-control-sm" name="forest_length" id="constr_forest_length" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Drawing Status Section -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white"><strong>Drawing Status (Scope / Reviewed by MSIDC / Balance)</strong></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label">MJB</label>
                                                <input type="text" class="form-control form-control-sm" name="mjb" id="constr_mjb" placeholder="e.g. 11/11/0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">MNB</label>
                                                <input type="text" class="form-control form-control-sm" name="mnb" id="constr_mnb" placeholder="e.g. 7/7/0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Box/Slab Culvert</label>
                                                <input type="text" class="form-control form-control-sm" name="box_slab_culvert" id="constr_box_slab_culvert" placeholder="e.g. 33/33">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">HPC</label>
                                                <input type="text" class="form-control form-control-sm" name="hpc" id="constr_hpc" placeholder="e.g. 5/5">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Major Junction</label>
                                                <input type="text" class="form-control form-control-sm" name="major_junction" id="constr_major_junction" placeholder="e.g. 20/20">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Minor Junction</label>
                                                <input type="text" class="form-control form-control-sm" name="minor_junction" id="constr_minor_junction" placeholder="e.g. -">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-2">
                                                <label class="form-label">P&P</label>
                                                <input type="text" class="form-control form-control-sm" name="p_and_p" id="constr_p_and_p" placeholder="e.g. 34.137/34.137">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Misc Drawing / Plan Section -->
                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white"><strong>Misc Drawing / Plan</strong></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Drainage Plan</label>
                                                <select class="form-select form-select-sm" name="drainage_plan" id="constr_drainage_plan">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Submitted">Submitted</option>
                                                    <option value="Done">Done</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Safety Plan</label>
                                                <select class="form-select form-select-sm" name="safety_plan" id="constr_safety_plan">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Submitted">Submitted</option>
                                                    <option value="Done">Done</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Road Marking & Signage Plan</label>
                                                <select class="form-select form-select-sm" name="road_marking_signage_plan" id="constr_road_marking_signage_plan">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Submitted">Submitted</option>
                                                    <option value="Submitted to MSIDC">Submitted to MSIDC</option>
                                                    <option value="Done">Done</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Junction Plan</label>
                                                <select class="form-select form-select-sm" name="junction_plan" id="constr_junction_plan">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Submitted">Submitted</option>
                                                    <option value="Done">Done</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Others Section -->
                                <div class="card mb-3">
                                    <div class="card-header bg-secondary text-white"><strong>Others</strong></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label">Drone Video Recording (3rd)</label>
                                                <select class="form-select form-select-sm" name="drone_video_recording" id="constr_drone_video_recording">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Done">Done</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">SCADA Execution</label>
                                                <select class="form-select form-select-sm" name="scada_execution" id="constr_scada_execution">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Done">Done</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Work Program</label>
                                                <select class="form-select form-select-sm" name="work_program" id="constr_work_program">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Submitted">Submitted</option>
                                                    <option value="Done">Done</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Schedule-H Softcopy</label>
                                                <select class="form-select form-select-sm" name="schedule_h_softcopy" id="constr_schedule_h_softcopy">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Submitted">Submitted</option>
                                                    <option value="Done">Done</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Royalty Status</label>
                                                <select class="form-select form-select-sm" name="royalty_status" id="constr_royalty_status">
                                                    <option value="Pending">Pending</option>
                                                    <option value="Submitted">Submitted</option>
                                                    <option value="Done">Done</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success px-5">Save Construction Progress</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
  <div class="card-body">
    
    <!-- Filter Options -->
    <div class="row mb-3">
      <div class="col-md-3">
        <label class="form-label small fw-bold">Filter by Project Code</label>
        <select class="form-select form-select-sm" id="filterProjectCode" onchange="filterTable()">
          <option value="">All Projects</option>
          <?php
          $sql_projects = "SELECT DISTINCT project_code FROM project_progress1 ORDER BY project_code";
          $result_projects = mysqli_query($link, $sql_projects);
          while ($proj = mysqli_fetch_assoc($result_projects)) {
              echo '<option value="'.htmlspecialchars($proj['project_code']).'">'.htmlspecialchars($proj['project_code']).'</option>';
          }
          ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-bold">Filter by Part No</label>
        <select class="form-select form-select-sm" id="filterPartNo" onchange="filterTable()">
          <option value="">All Parts</option>
          <?php
          $sql_parts = "SELECT DISTINCT section FROM project_progress1 ORDER BY section";
          $result_parts = mysqli_query($link, $sql_parts);
          while ($part = mysqli_fetch_assoc($result_parts)) {
              if (!empty($part['section'])) {
                  echo '<option value="'.htmlspecialchars($part['section']).'">'.htmlspecialchars($part['section']).'</option>';
              }
          }
          ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-bold">Filter by Chainage</label>
        <input type="text" class="form-control form-control-sm" id="filterChainage" placeholder="Search chainage..." onkeyup="filterTable()">
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">Clear Filters</button>
      </div>
    </div>
    
    <div class="table-responsive">
      <table id="example2" class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Id</th>
            <th>Project Code</th>
            <th>Project Name</th>
            <th>Structure</th>
            <th>Scope</th>
            <th>Part No</th>
            <th>Chainage</th>
            <th>Existing</th>
            <th>Proposed</th>
            <th>No's</th>
            <th>Span</th>
            <th>Length (m)</th>
            <th>Remarks</th>
            <th>Drawing</th>
            <th>Issues</th>
            <th>Foundation</th>
            <th>Sub Structure</th>
            <th>Superstructure</th>
            <th>Complete</th>
            <th>By</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody id="logTableBody">
          <?php
          $sql = "SELECT pp.*, pd.project_name 
                  FROM project_progress1 pp 
                  LEFT JOIN project_details pd ON pp.project_code = pd.project_code 
                  ORDER BY pp.id DESC";
          $result = mysqli_query($link, $sql);
          $i = 1;
          while ($row = mysqli_fetch_assoc($result)) {
              ?>
              <tr data-project="<?php echo htmlspecialchars($row['project_code']); ?>" 
                  data-part="<?php echo htmlspecialchars($row['section']); ?>"
                  data-chainage="<?php echo htmlspecialchars($row['chainage']); ?>">
                <td><?php echo $i; ?></td>
                <td><strong><?php echo htmlspecialchars($row['project_code']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['project_name'] ?? '-'); ?></td>
                <td><span class="badge bg-primary"><?php echo htmlspecialchars($row['structure_type'] ?? '-'); ?></span></td>
                <td><span class="badge bg-success"><?php echo htmlspecialchars($row['scope_type'] ?? '-'); ?></span></td>
                <td><?php echo htmlspecialchars($row['section']); ?></td>
                <td><?php echo htmlspecialchars($row['chainage']); ?></td>
                <td><?php echo htmlspecialchars($row['existing']); ?></td>
                <td><?php echo htmlspecialchars($row['proposed']); ?></td>
                <td><?php echo htmlspecialchars($row['nos']); ?></td>
                <td><?php echo htmlspecialchars($row['span']); ?></td>
                <td><?php echo htmlspecialchars($row['length']); ?></td>
                <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                <td><?php echo htmlspecialchars($row['drawing']); ?></td>
                <td><?php echo htmlspecialchars($row['issues']); ?></td>
                <td><?php echo htmlspecialchars($row['foundation']); ?></td>
                <td><?php echo htmlspecialchars($row['sub_structure']); ?></td>
                <td><?php echo htmlspecialchars($row['superstructure']); ?></td>
                <td><?php echo htmlspecialchars($row['complete']); ?></td>
                <td><?php echo htmlspecialchars($row['by']); ?></td>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
              </tr>
              <?php
              $i++;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Construction Progress Log -->
<div class="card mt-4">
  <div class="card-header bg-success text-white">
    <h6 class="mb-0"><i class="bx bx-building-house"></i> Construction Progress Log</h6>
  </div>
  <div class="card-body">
    
    <!-- Filter Options -->
    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label small fw-bold">Filter by Project Code</label>
        <select class="form-select form-select-sm" id="filterConstructionProject" onchange="filterConstructionTable()">
          <option value="">All Projects</option>
          <?php
          $sql_cp = "SELECT DISTINCT project_code FROM construction_progress ORDER BY project_code";
          $result_cp = mysqli_query($link, $sql_cp);
          while ($cp = mysqli_fetch_assoc($result_cp)) {
              echo '<option value="'.htmlspecialchars($cp['project_code']).'">'.htmlspecialchars($cp['project_code']).'</option>';
          }
          ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label small fw-bold">Filter by Status</label>
        <select class="form-select form-select-sm" id="filterConstructionStatus" onchange="filterConstructionTable()">
          <option value="">All Status</option>
          <option value="Pending">Pending</option>
          <option value="Submitted">Submitted</option>
          <option value="Done">Done</option>
        </select>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearConstructionFilters()">Clear Filters</button>
      </div>
    </div>
    
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-sm" id="constructionTable">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Project</th>
            <th colspan="5" class="text-center bg-warning text-dark">Scope (In Km)</th>
            <th colspan="7" class="text-center bg-info">Drawing Status</th>
            <th colspan="4" class="text-center bg-primary">Misc Drawing/Plan</th>
            <th colspan="5" class="text-center bg-secondary">Others</th>
            <th>Actions</th>
          </tr>
          <tr>
            <th></th>
            <th></th>
            <th>PQC</th>
            <th>Overlay</th>
            <th>Total</th>
            <th>DLP</th>
            <th>Forest</th>
            <th>MJB</th>
            <th>MNB</th>
            <th>Box/Slab</th>
            <th>HPC</th>
            <th>Major Jn</th>
            <th>Minor Jn</th>
            <th>P&P</th>
            <th>Drainage</th>
            <th>Safety</th>
            <th>Road M&S</th>
            <th>Junction</th>
            <th>Drone</th>
            <th>SCADA</th>
            <th>Work Prog</th>
            <th>Sch-H</th>
            <th>Royalty</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="constructionTableBody">
          <?php
          $sql_construction = "SELECT cp.*, pd.project_name 
                               FROM construction_progress cp 
                               LEFT JOIN project_details pd ON cp.project_code = pd.project_code 
                               ORDER BY cp.id DESC";
          $result_construction = mysqli_query($link, $sql_construction);
          
          if ($result_construction && mysqli_num_rows($result_construction) > 0) {
              while ($row = mysqli_fetch_assoc($result_construction)) {
                  $statusBadge = function($status) {
                      switch($status) {
                          case 'Done': return '<span class="badge bg-success">Done</span>';
                          case 'Submitted': return '<span class="badge bg-warning text-dark">Submitted</span>';
                          case 'Submitted to MSIDC': return '<span class="badge bg-info">MSIDC</span>';
                          default: return '<span class="badge bg-danger">Pending</span>';
                      }
                  };
                  ?>
                  <tr data-project="<?php echo htmlspecialchars($row['project_code']); ?>" data-id="<?php echo $row['id']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['project_code']); ?></strong></td>
                    <td class="bg-warning-subtle"><?php echo number_format($row['pqc'], 2); ?></td>
                    <td class="bg-warning-subtle"><?php echo number_format($row['overlay'], 2); ?></td>
                    <td class="bg-warning-subtle"><?php echo number_format($row['total'], 2); ?></td>
                    <td class="bg-warning-subtle"><?php echo number_format($row['dlp_length'], 2); ?></td>
                    <td class="bg-warning-subtle"><?php echo number_format($row['forest_length'], 2); ?></td>
                    <td class="bg-info-subtle"><?php echo htmlspecialchars($row['mjb']); ?></td>
                    <td class="bg-info-subtle"><?php echo htmlspecialchars($row['mnb']); ?></td>
                    <td class="bg-info-subtle"><?php echo htmlspecialchars($row['box_slab_culvert']); ?></td>
                    <td class="bg-info-subtle"><?php echo htmlspecialchars($row['hpc']); ?></td>
                    <td class="bg-info-subtle"><?php echo htmlspecialchars($row['major_junction']); ?></td>
                    <td class="bg-info-subtle"><?php echo htmlspecialchars($row['minor_junction']); ?></td>
                    <td class="bg-info-subtle"><?php echo htmlspecialchars($row['p_and_p']); ?></td>
                    <td class="bg-primary-subtle"><?php echo $statusBadge($row['drainage_plan']); ?></td>
                    <td class="bg-primary-subtle"><?php echo $statusBadge($row['safety_plan']); ?></td>
                    <td class="bg-primary-subtle"><?php echo $statusBadge($row['road_marking_signage_plan']); ?></td>
                    <td class="bg-primary-subtle"><?php echo $statusBadge($row['junction_plan']); ?></td>
                    <td class="bg-secondary-subtle"><?php echo $statusBadge($row['drone_video_recording']); ?></td>
                    <td class="bg-secondary-subtle"><?php echo $statusBadge($row['scada_execution']); ?></td>
                    <td class="bg-secondary-subtle"><?php echo $statusBadge($row['work_program']); ?></td>
                    <td class="bg-secondary-subtle"><?php echo $statusBadge($row['schedule_h_softcopy']); ?></td>
                    <td class="bg-secondary-subtle"><?php echo $statusBadge($row['royalty_status']); ?></td>
                    <td>
                      <button class="btn btn-sm btn-outline-primary" onclick="editConstruction(<?php echo $row['id']; ?>)" title="Edit">
                        <i class="bx bx-edit"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-danger" onclick="deleteConstruction(<?php echo $row['id']; ?>)" title="Delete">
                        <i class="bx bx-trash"></i>
                      </button>
                    </td>
                  </tr>
                  <?php
              }
          } else {
              echo '<tr><td colspan="24" class="text-center text-muted">No construction progress records found. Click "Add Construction" to add new records.</td></tr>';
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
<script>
$('#project_code').change(function() {
    var projectCode = $(this).val();

    if (projectCode !== "") {
        $.ajax({
            url: 'get_part_count.php',
            type: 'POST',
            data: { project_code: projectCode },
            success: function(data) {
                $('#part_no').html(data);
            }
        });
    } else {
        $('#part_no').html('<option value="">-- Select Part --</option>');
    }
});

// Filter Table Function
function filterTable() {
    var filterProject = document.getElementById('filterProjectCode').value.toLowerCase();
    var filterPart = document.getElementById('filterPartNo').value.toLowerCase();
    var filterChainage = document.getElementById('filterChainage').value.toLowerCase();
    
    var rows = document.querySelectorAll('#logTableBody tr');
    
    rows.forEach(function(row) {
        var projectCode = (row.getAttribute('data-project') || '').toLowerCase();
        var partNo = (row.getAttribute('data-part') || '').toLowerCase();
        var chainage = (row.getAttribute('data-chainage') || '').toLowerCase();
        
        var showRow = true;
        
        if (filterProject && projectCode !== filterProject) {
            showRow = false;
        }
        if (filterPart && partNo !== filterPart) {
            showRow = false;
        }
        if (filterChainage && chainage.indexOf(filterChainage) === -1) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

// Clear Filters Function
function clearFilters() {
    document.getElementById('filterProjectCode').value = '';
    document.getElementById('filterPartNo').value = '';
    document.getElementById('filterChainage').value = '';
    filterTable();
}

// =====================
// Construction Progress Functions
// =====================

// Edit Construction
function editConstruction(id) {
    // Fetch data via AJAX
    fetch('construction_handler.php?action=get&id=' + id)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                var data = result.data;
                
                // Set form action to edit
                document.getElementById('constructionAction').value = 'edit';
                document.getElementById('constructionId').value = id;
                document.getElementById('constructionModalTitle').innerText = 'Edit Construction Progress';
                
                // Populate form fields
                document.getElementById('constr_project_code').value = data.project_code || '';
                document.getElementById('constr_pqc').value = data.pqc || 0;
                document.getElementById('constr_overlay').value = data.overlay || 0;
                document.getElementById('constr_total').value = data.total || 0;
                document.getElementById('constr_dlp_length').value = data.dlp_length || 0;
                document.getElementById('constr_forest_length').value = data.forest_length || 0;
                
                // Drawing Status
                document.getElementById('constr_mjb').value = data.mjb || '-';
                document.getElementById('constr_mnb').value = data.mnb || '-';
                document.getElementById('constr_box_slab_culvert').value = data.box_slab_culvert || '-';
                document.getElementById('constr_hpc').value = data.hpc || '-';
                document.getElementById('constr_major_junction').value = data.major_junction || '-';
                document.getElementById('constr_minor_junction').value = data.minor_junction || '-';
                document.getElementById('constr_p_and_p').value = data.p_and_p || '-';
                
                // Misc Drawing/Plan
                document.getElementById('constr_drainage_plan').value = data.drainage_plan || 'Pending';
                document.getElementById('constr_safety_plan').value = data.safety_plan || 'Pending';
                document.getElementById('constr_road_marking_signage_plan').value = data.road_marking_signage_plan || 'Pending';
                document.getElementById('constr_junction_plan').value = data.junction_plan || 'Pending';
                
                // Others
                document.getElementById('constr_drone_video_recording').value = data.drone_video_recording || 'Pending';
                document.getElementById('constr_scada_execution').value = data.scada_execution || 'Pending';
                document.getElementById('constr_work_program').value = data.work_program || 'Pending';
                document.getElementById('constr_schedule_h_softcopy').value = data.schedule_h_softcopy || 'Pending';
                document.getElementById('constr_royalty_status').value = data.royalty_status || 'Pending';
                
                // Open modal
                var modal = new bootstrap.Modal(document.getElementById('constructionModal'));
                modal.show();
            } else {
                alert('Error loading data: ' + result.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
}

// Delete Construction
function deleteConstruction(id) {
    if (confirm('Are you sure you want to delete this construction progress record?')) {
        fetch('construction_handler.php?action=delete&id=' + id)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Record deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
    }
}

// Filter Construction Table
function filterConstructionTable() {
    var filterProject = document.getElementById('filterConstructionProject').value.toLowerCase();
    var filterStatus = document.getElementById('filterConstructionStatus').value.toLowerCase();
    
    var rows = document.querySelectorAll('#constructionTableBody tr');
    
    rows.forEach(function(row) {
        var projectCode = (row.getAttribute('data-project') || '').toLowerCase();
        var rowText = row.innerText.toLowerCase();
        
        var showRow = true;
        
        if (filterProject && projectCode !== filterProject) {
            showRow = false;
        }
        if (filterStatus && rowText.indexOf(filterStatus) === -1) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

// Clear Construction Filters
function clearConstructionFilters() {
    document.getElementById('filterConstructionProject').value = '';
    document.getElementById('filterConstructionStatus').value = '';
    filterConstructionTable();
}

// Reset construction form when modal is closed
document.getElementById('constructionModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('constructionAction').value = 'add';
    document.getElementById('constructionId').value = '';
    document.getElementById('constructionModalTitle').innerText = 'Add Construction Progress';
    document.getElementById('constructionForm').reset();
});
</script>

