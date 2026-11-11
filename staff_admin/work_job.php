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
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlspecialchars($_GET['success']); ?>
                                <?php if (!empty($_GET['log'])): ?>
                                    <br><small class="text-muted">Log: <?php echo htmlspecialchars($_GET['log']); ?></small>
                                <?php endif; ?>
                            </div>
                        <?php elseif (isset($_GET['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_GET['error']); ?>
                            </div>
                        <?php endif; ?>
                        <form class="row g-3" action="work_job1.php" method="post" enctype="multipart/form-data">
                             <h3>Personal information</h3>
                            <div class="col-12 mb-2">
                                <div class="btn-group" role="group" aria-label="Customer Mode">
                                    <button type="button" class="btn btn-primary" id="btn_new_customer">Add New Customer</button>
                                    <button type="button" class="btn btn-outline-primary" id="btn_old_customer">Add Old Customer Entry</button>
                                </div>
                                <small class="text-muted d-block mt-1">Choose mode to set CLIENT ID: auto for new, manual for old.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Client name :</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                             <div class="col-md-6">
                                <label for="contact_no" class="form-label">Client contact number :</label>
                                <input type="tel" class="form-control" id="contact_no" name="contact_no" required>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_email" class="form-label">Client Gmail / Email :</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="example@gmail.com">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label for="address_line" class="form-label">Line</label>
                                        <input type="text" class="form-control" id="address_line" name="address_line" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_street" class="form-label">Street</label>
                                        <input type="text" class="form-control" id="address_street" name="address_street" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_landmark" class="form-label">Landmark</label>
                                        <input type="text" class="form-control" id="address_landmark" name="address_landmark" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="address_city" name="address_city" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_district" class="form-label">District</label>
                                        <select class="form-select" id="address_district" name="address_district" required>
                                            <option value="">Select District</option>
                                            <option value="Sangli">Sangli</option>
                                            <option value="Satara">Satara</option>
                                            <option value="Kolhapur">Kolhapur</option>
                                            <option value="Solapur">Solapur</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_taluka" class="form-label">Taluka</label>
                                        <select class="form-select" id="address_taluka" name="address_taluka" required>
                                            <option value="">Select Taluka</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                            </div>

                            <div class="col-md-6"> 
                                <label for="business" class="form-label">Occupation:</label>
                                <input type="text" class="form-control" id="business" name="business" required>
                            </div>
                            <div class="col-md-6" id="client_id_wrapper" style="display:none;">
                                <label for="client_id" class="form-label">CLIENT ID</label>
                                <input type="text" class="form-control" id="client_id" name="client_id" placeholder="Will auto-generate for new customer">
                                <div id="client_id_error" class="text-danger mt-1" style="display:none;">ID Already Exists</div>
                            </div>

                            <br> <br> <br> <br> <br> 

                                <h3>Property information</h3>

                            <div class="col-md-6">
                                <label for="sale_rent" class="form-label"> select (Purchase/Rent)</label>
                                <select class="form-control" id="sale_rent" name="sale_rent" required>
                                    <option value="Purchase">Purchase</option>
                                    <option value="Rent">Rent</option>
                                </select>
                            </div>
                            
                        




                           


                            <div class="col-md-6">
                                <label for="area_required" class="form-label"> Select Property Type (Purchase/Rent)</label>
                               <select class="form-control" id="location_area" name="location_area" required>
                                    <option value="">Select Option</option>
                                    <option value="Flat">Flat</option>
                                    <option value="Plot">Plot</option>
                                    <option value="Bungalow">Bungalow</option>
                                    <option value="Agriculture">Agriculture</option>
                                    <option value="Commercial">Commercial</option>
                                </select>
                            </div>

                            <!-- Location dropdown shown only when Purchase/Rent is selected and type is Flat/Plot/Commercial -->
                            <div class="col-md-6" id="city_location_wrapper" style="display:none;">
                                <label for="city_location" class="form-label">लोकेशन निवडा (Select Location)</label>
                                <input type="text" class="form-control" id="city_location" name="city_location" placeholder="Enter Location">
                            </div>

                            <!-- City Area dropdown shown only when a city is selected -->
                            <div class="col-md-6" id="city_area_wrapper" style="display:none;">
                                <label for="city_area" class="form-label">एरिया निवडा (Select City Area)</label>
                                <select class="form-control" id="city_area" name="city_area">
                                    <option value="">Select Area</option>
                                </select>
                            </div>

                            <!-- Agriculture: District and Taluka (shown only when Area required = Agriculture) -->
                            <div class="col-md-6" id="agri_district_wrapper" style="display:none;">
                                <label for="agri_district" class="form-label">जिल्हा निवडा (Select District)</label>
                                <input type="text" class="form-control" id="agri_district" name="agri_district" placeholder="Enter District">
                            </div>
                            <div class="col-md-6" id="agri_taluka_wrapper" style="display:none;">
                                <label for="agri_taluka" class="form-label">तालुका निवडा (Select Taluka)</label>
                                <select class="form-control" id="agri_taluka" name="agri_taluka">
                                    <option value="">Select Taluka</option>
                                </select>
                            </div>

                             <div class="col-md-6">
                                <label for="expected_rent" class="form-label">Budget (in Numbers)</label>
                                <input type="number" class="form-control" id="expected_rent" name="expected_rent">
                            </div>

                             

                            <!-- Agriculture: Select Proprety (Acar) -->
                            <div class="col-md-6" id="agri_property_size_wrapper" style="display:none;">
                                <label for="agri_property_size" class="form-label">Select Proprety (Acar)</label>
                                <select class="form-control" id="agri_property_size" name="agri_property_size">
                                    <option value="">Select Property Size</option>
                                    <option value="10 गुंठे ते 1 Acar">10 गुंठे ते 1 Acar</option>
                                    <option value="1 Acar">1 Acar</option>
                                    <option value="2 Acar">2 Acar</option>
                                    <option value="3 Acar">3 Acar</option>
                                    <option value="4 Acar">4 Acar</option>
                                    <option value="5 Acar">5 Acar</option>
                                    <option value="6 to 10 Acar">6 to 10 Acar</option>
                                    <option value="10 to 50 Acar">10 to 50 Acar</option>
                                    <option value="50 to 100 Acar">50 to 100 Acar</option>
                                    <option value="100 to 200 Acar">100 to 500 Acar</option>
                                    
                                </select>
                            </div>

                          
                            
                            
                           
                           


                            <div class="col-md-12">
                                <label for="specification" class="form-label">Special Requirements</label>
                                <textarea class="form-control" id="specification" name="specification" rows="2" maxlength="250"></textarea>
                            </div>

                            <div class="col-12" align="center">
                                <button type="submit" class="btn btn-outline-primary px-5">Submit</button>
                            </div>
                        </form>
                        <script>
                        // District to Taluka mapping
                        const talukasByDistrict = {
                            "Sangli": ["Shirala", "Walwa", "Palus", "Tasgaon", "Khanapur (Vita)", "Atpadi", "Kavathe Mahankal", "Miraj", "Jat", "Kadegaon"],
                            "Satara": ["Satara", "Karad", "Wai", "Mahabaleshwar", "Phaltan", "Man (Maan)", "Khatav", "Koregaon", "Patan", "Jaoli", "Khandala"],
                            "Kolhapur": ["Karvir", "Hatkanangle", "Shirol", "Kagal", "Panhala", "Gadhinglaj", "Radhanagari", "Chandgad", "Shahuwadi", "Bhudargad", "Ajra", "Bavda"],
                            "Solapur": ["Solapur North", "Solapur South", "Akkalkot", "Barshi", "Mangalwedha / Mangalvedhe", "Pandharpur", "Sangola / Sangole", "Malshiras", "Mohol", "Madha", "Karmala"]
                        };

                        document.getElementById('address_district').addEventListener('change', function() {
                            const district = this.value;
                            const talukaSelect = document.getElementById('address_taluka');
                            
                            // Clear existing options
                            talukaSelect.innerHTML = '<option value="">Select Taluka</option>';
                            
                            if (district && talukasByDistrict[district]) {
                                talukasByDistrict[district].forEach(function(taluka) {
                                    const option = document.createElement('option');
                                    option.value = taluka;
                                    option.textContent = taluka;
                                    talukaSelect.appendChild(option);
                                });
                            }
                        });

                        // Dynamically adjust Property Type options based on Purchase/Rent
                        document.addEventListener('DOMContentLoaded', function() {
                          var saleRent = document.getElementById('sale_rent');
                          var locationArea = document.getElementById('location_area');
                          if (!saleRent || !locationArea) return;

                          function populateLocationAreaOptions() {
                            var sr = saleRent.value;
                            var opts = (sr === 'Purchase')
                              ? ['Agriculture', 'Flat', 'Plot', 'Bungalow', 'Commercial']
                              : ['Flat', 'Plot', 'Bungalow', 'Commercial'];

                            var prev = locationArea.value;
                            // Rebuild options
                            locationArea.innerHTML = '';
                            var defaultOpt = document.createElement('option');
                            defaultOpt.value = '';
                            defaultOpt.textContent = 'Select Option';
                            locationArea.appendChild(defaultOpt);
                            opts.forEach(function(v) {
                              var o = document.createElement('option');
                              o.value = v;
                              o.textContent = v;
                              locationArea.appendChild(o);
                            });

                            // Restore selection if still valid; otherwise reset
                            if (opts.indexOf(prev) !== -1) {
                              locationArea.value = prev;
                            } else {
                              locationArea.value = '';
                            }

                            // Trigger change so any dependent UI updates run
                            locationArea.dispatchEvent(new Event('change'));
                          }

                          // Update on change and initialize on load
                          saleRent.addEventListener('change', populateLocationAreaOptions);
                          populateLocationAreaOptions();
                        });
                        </script>
                        
                    </div>
                </div>
            </div>
        </div>

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
                                             <option>Approve</option>
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
<script>
// Show the sub category dropdown after choosing Purchase/Rent
// Also show the Location dropdown when type is Flat/Plot/Commercial
$(function(){
  function toggleSubCategory(){
    var val = $('#sale_rent').val();
    if (val) {
      $('#location_area').closest('.col-md-6').show();
    } else {
      $('#location_area').closest('.col-md-6').hide();
    }
  }

  function toggleCityLocation(){
    var sr = $('#sale_rent').val();
    var la = $('#location_area').val();
    var showTypes = ['Flat','Plot','Commercial'];
    if (sr && showTypes.indexOf(la) !== -1) {
      $('#city_location_wrapper').show();
    } else {
      $('#city_location_wrapper').hide();
      $('#city_location').val('');
      $('#city_area_wrapper').hide();
      $('#city_area').val('');
    }
  }

  // City -> Area lists
  var CITY_AREAS = {
    'Sangli': [
            'Vishrambag',
            'Govt. Colony',
            'Sphurti Chowk',
            'Vijaynagar',
            'Palvi Hotel area',
            '100 ft. Road',
            'Bapat Mala',
            'Neminath Nagar',
            'Sangli D Mart',
            'Laxmi Mandir',
            'Podar School area',
            'Gavbaugh',
            'Ganpati Peth',
            'Sangli Stand'
        ],
        'Miraj': [
            'Prabhatpuri',
            'Shyam Udyan',
            'Shivajinagar',
            'Nandes / Nandives',
            'Udyan Bus',
            'Laxmi Market',
            'Bhau Rao Chowk',
            'O2 Park',
            'Vesa',
            'Pujari Chowk',
            'Dargah Road',
            'Sindives',
            'Subhashnagar Road'
        ],
        'Kupwad': [
            'Prabhatpuri',
            'Shyam Udyan',
            'Shivajinagar',
            'Nandes / Nandives',
            'Udyan Bus',
            'Laxmi Market'
        ]
  };

  function updateCityAreaOptions(city){
    var $area = $('#city_area');
    $area.empty();
    $area.append(new Option('Select Area',''));
    var arr = CITY_AREAS[city] || [];
    arr.forEach(function(label){
      $area.append(new Option(label, label));
    });
  }

  function toggleCityArea(){
    var sr = $('#sale_rent').val();
    var la = $('#location_area').val();
    var city = $('#city_location').val();
    var showTypes = ['Flat','Plot','Commercial'];
    if (sr && showTypes.indexOf(la) !== -1 && city){
      updateCityAreaOptions(city);
      $('#city_area_wrapper').show();
    } else {
      $('#city_area_wrapper').hide();
      $('#city_area').val('');
    }
  }

  // Agriculture: District -> Taluka lists
  var AGRI_TALUKAS = {
    "Sangli": ["Shirala", "Walwa", "Palus", "Tasgaon", "Khanapur (Vita)", "Atpadi", "Kavathe Mahankal", "Miraj", "Jat", "Kadegaon"],
    "Satara": ["Satara", "Karad", "Wai", "Mahabaleshwar", "Phaltan", "Man (Maan)", "Khatav", "Koregaon", "Patan", "Jaoli", "Khandala"],
    "Kolhapur": ["Karvir", "Hatkanangle", "Shirol", "Kagal", "Panhala", "Gadhinglaj", "Radhanagari", "Chandgad", "Shahuwadi", "Bhudargad", "Ajra", "Bavda"],
    "Solapur": ["Solapur North", "Solapur South", "Akkalkot", "Barshi", "Mangalwedha / Mangalvedhe", "Pandharpur", "Sangola / Sangole", "Malshiras", "Mohol", "Madha", "Karmala"]
  };

  function updateAgriTalukaOptions(district){
    var $tal = $('#agri_taluka');
    $tal.empty();
    $tal.append(new Option('Select Taluka',''));
    var arr = AGRI_TALUKAS[district] || [];
    arr.forEach(function(label){
      $tal.append(new Option(label, label));
    });
  }

  function toggleAgriSelectors(){
    var la = $('#location_area').val();
    if (la === 'Agriculture'){
      // Hide city-based selectors
      $('#city_location_wrapper').hide();
      $('#city_area_wrapper').hide();
      $('#city_location').val('');
      $('#city_area').val('');

      // Show Agriculture district
      $('#agri_district_wrapper').show();
      // Show property size for Agriculture
      $('#agri_property_size_wrapper').show();
    } else {
      $('#agri_district_wrapper').hide();
      $('#agri_taluka_wrapper').hide();
      $('#agri_property_size_wrapper').hide();
      $('#agri_district').val('');
      $('#agri_taluka').val('');
      $('#agri_property_size').val('');
    }
  }

  function toggleAgriTaluka(){
    var la = $('#location_area').val();
    var district = $('#agri_district').val();
    if (la === 'Agriculture' && district){
      updateAgriTalukaOptions(district);
      $('#agri_taluka_wrapper').show();
    } else {
      $('#agri_taluka_wrapper').hide();
      $('#agri_taluka').val('');
    }
  }

  // initialize on load
  toggleSubCategory();
  toggleCityLocation();
  toggleCityArea();
  toggleAgriSelectors();
  toggleAgriTaluka();

  // Property Type options vary by Purchase/Rent
  var PT_PURCHASE = ['Agriculture','Flat','Plot','Bungalow','Commercial'];
  var PT_RENT = ['Flat','Plot','Bungalow','Commercial'];
  function updatePropertyTypeOptions(){
    var sr = $('#sale_rent').val();
    var list = (sr === 'Rent') ? PT_RENT : PT_PURCHASE;
    var $pt = $('#property_type');
    $pt.empty();
    $pt.append(new Option('Select Property Type',''));
    list.forEach(function(label){
      $pt.append(new Option(label, label));
    });
  }
  updatePropertyTypeOptions();

  // update on change
  $('#sale_rent, #location_area').on('change', function(){
    toggleSubCategory();
    toggleCityLocation();
    toggleCityArea();
    toggleAgriSelectors();
    toggleAgriTaluka();
    updatePropertyTypeOptions();
  });
  $('#city_location').on('change', function(){
    toggleCityArea();
  });
  $('#agri_district').on('change', function(){
    toggleAgriTaluka();
  });
});
</script>
<script>
$(function(){
  var customerMode = null; // 'new' or 'old'
  var $clientWrap = $('#client_id_wrapper');
  var $clientInput = $('#client_id');
  var $clientErr = $('#client_id_error');
  var $submitBtn = $('button[type=submit]');

  function setMode(mode){
    customerMode = mode;
    $clientWrap.show();
    $clientErr.hide();
    if (mode === 'new'){
      $clientInput.prop('readonly', true).val('');
      $('#btn_new_customer').removeClass('btn-outline-primary').addClass('btn-primary');
      $('#btn_old_customer').removeClass('btn-primary').addClass('btn-outline-primary');
      maybeGenerateId();
    } else if (mode === 'old'){
      $clientInput.prop('readonly', false).val('');
      $('#btn_old_customer').removeClass('btn-outline-primary').addClass('btn-primary');
      $('#btn_new_customer').removeClass('btn-primary').addClass('btn-outline-primary');
    }
  }

  function maybeGenerateId(){
    var sr = $('#sale_rent').val();
    if (customerMode !== 'new') return;
    if (!sr){
      $clientInput.val('');
      return;
    }
    $.getJSON('next_client_id.php', { type: sr })
      .done(function(res){
        if (res && res.id){
          $clientInput.val(res.id);
        }
      })
      .fail(function(){
        // ignore errors for now
      });
  }

  function checkExistingId(){
    var idVal = ($clientInput.val()||'').trim();
    if (customerMode !== 'old' || !idVal) {
      $clientErr.hide();
      $submitBtn.prop('disabled', false);
      return;
    }
    $.getJSON('check_client_id.php', { id: idVal })
      .done(function(res){
        var exists = !!(res && res.exists);
        if (exists){
          $clientErr.show();
          $submitBtn.prop('disabled', true);
        } else {
          $clientErr.hide();
          $submitBtn.prop('disabled', false);
        }
      })
      .fail(function(){
        // on failure, do not block submit
        $clientErr.hide();
        $submitBtn.prop('disabled', false);
      });
  }

  // Bindings
  $('#btn_new_customer').on('click', function(){ setMode('new'); });
  $('#btn_old_customer').on('click', function(){ setMode('old'); });
  $('#sale_rent').on('change', function(){ maybeGenerateId(); });
  $clientInput.on('blur change keyup', function(){ checkExistingId(); });
});
</script>
