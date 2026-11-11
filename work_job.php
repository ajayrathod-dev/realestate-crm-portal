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

// Fetch existing customer data if editing
$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = mysqli_prepare($link, "SELECT * FROM upload WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $edit_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $edit_row = mysqli_fetch_assoc($res);
    }
    mysqli_stmt_close($stmt);
}

// Helper to get address parts if editing
$addr_line = '';
$addr_street = '';
$addr_landmark = '';
$addr_city = '';
$addr_taluka = '';
$addr_district = '';

if ($edit_row && !empty($edit_row['address'])) {
    // Address was joined by ' | '
    $parts = explode(' | ', $edit_row['address']);
    // Best effort mapping
    $addr_line = $parts[0] ?? '';
    $addr_street = $parts[1] ?? '';
    $addr_landmark = $parts[2] ?? '';
    $addr_city = $parts[3] ?? '';
    $addr_taluka = $parts[4] ?? '';
    $addr_district = $parts[5] ?? '';
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
                        
                        <h4 class="mb-3 text-primary"><?php echo $edit_row ? 'Edit Customer' : 'Add New Customer'; ?></h4>
                        
                        <form class="row g-3" action="work_job1.php" method="post" enctype="multipart/form-data">
                            <?php if ($edit_row): ?>
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="original_id" value="<?php echo htmlspecialchars($edit_row['id']); ?>">
                            <?php endif; ?>

                             <h3>Personal information</h3>
                            <div class="col-12 mb-2">
                                <?php if (!$edit_row): ?>
                                    <div class="btn-group" role="group" aria-label="Customer Mode">
                                        <button type="button" class="btn btn-primary" id="btn_new_customer">Add New Customer</button>
                                        <button type="button" class="btn btn-outline-primary" id="btn_old_customer">Add Old Customer Entry</button>
                                    </div>
                                    <small class="text-muted d-block mt-1">Choose mode to set CLIENT ID: auto for new, manual for old.</small>
                                <?php else: ?>
                                    <div class="alert alert-info py-2">Editing Client ID: <strong><?php echo htmlspecialchars($edit_row['client_id']); ?></strong></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Client name :</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required value="<?php echo $edit_row['customer_name'] ?? ''; ?>">
                            </div>
                             <div class="col-md-6">
                                <label for="contact_no" class="form-label">Client contact number :</label>
                                <input type="tel" class="form-control" id="contact_no" name="contact_no" required value="<?php echo $edit_row['contact_no'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="customer_email" class="form-label">Client Gmail / Email :</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="example@gmail.com" value="<?php echo $edit_row['customer_email'] ?? ''; ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <div class="row g-2">
                                    <!-- <div class="col-md-6">
                                        <label for="address_line" class="form-label">Line</label>
                                        <input type="text" class="form-control" id="address_line" name="address_line" required value="<?php echo htmlspecialchars($addr_line); ?>">
                                    </div> -->
                                    <div class="col-md-6">
                                        <label for="address_street" class="form-label">Street</label>
                                        <input type="text" class="form-control" id="address_street" name="address_street" required value="<?php echo htmlspecialchars($addr_street); ?>">
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <label for="address_landmark" class="form-label">Landmark</label>
                                        <input type="text" class="form-control" id="address_landmark" name="address_landmark" required value="<?php echo htmlspecialchars($addr_landmark); ?>">
                                    </div> -->
                                    <div class="col-md-6">
                                        <label for="address_city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="address_city" name="address_city" required value="<?php echo htmlspecialchars($addr_city); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_district" class="form-label">District</label>
                                        <input type="text" class="form-control" id="address_district" name="address_district" required value="<?php echo htmlspecialchars($addr_district); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_taluka" class="form-label">Taluka</label>
                                        <input type="text" class="form-control" id="address_taluka" name="address_taluka" required value="<?php echo htmlspecialchars($addr_taluka); ?>">
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" required value="<?php echo $edit_row['birth_date'] ?? ''; ?>">
                            </div>

                            <div class="col-md-6"> 
                                <label for="business" class="form-label">Occupation:</label>
                                <input type="text" class="form-control" id="business" name="business" required value="<?php echo $edit_row['business'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6" id="client_id_wrapper" style="display:<?php echo $edit_row ? 'block' : 'none'; ?>;">
                                <label for="client_id" class="form-label">CLIENT ID</label>
                                <input type="text" class="form-control" id="client_id" name="client_id" placeholder="Will auto-generate for new customer" value="<?php echo $edit_row['client_id'] ?? ''; ?>" <?php echo $edit_row ? 'readonly' : ''; ?>>
                                <div id="client_id_error" class="text-danger mt-1" style="display:none;">ID Already Exists</div>
                            </div>

                            <br> <br> <br> <br> <br> 

                                <h3>Property information</h3>

                            <div class="col-md-6">
                                <label for="sale_rent" class="form-label"> select (Purchase/Rent)</label>
                                <select class="form-control" id="sale_rent" name="sale_rent" required>
                                    <option value="Purchase" <?php echo ($edit_row['sale_rent'] ?? '') == 'Purchase' ? 'selected' : ''; ?>>Purchase</option>
                                    <option value="Rent" <?php echo ($edit_row['sale_rent'] ?? '') == 'Rent' ? 'selected' : ''; ?>>Rent</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="area_required" class="form-label"> Select Property Type (Purchase/Rent)</label>
                               <select class="form-control" id="location_area" name="location_area" required>
                                    <option value="">Select Option</option>
                                    <?php
                                    $ptypes = ['Flat', 'Plot', 'Bungalow', 'Agriculture', 'Commercial'];
                                    $currPt = $edit_row['property_type'] ?? '';
                                    foreach ($ptypes as $p) {
                                        $sel = ($currPt == $p) ? 'selected' : '';
                                        echo "<option value=\"$p\" $sel>$p</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Location dropdown shown only when Purchase/Rent is selected and type is Flat/Plot/Commercial -->
                            <div class="col-md-6" id="city_location_wrapper" style="display:none;">
                                <label for="city_location" class="form-label">लोकेशन निवडा (Select Location)</label>
                                <select class="form-control" id="city_location" name="city_location">
                                    <option value="">Select Location</option>
                                    <?php
                                    $locs = [
                                        'Sangli' => 'Sangli (सांगली)', 
                                        'Miraj' => 'Miraj (मिरज)', 
                                        'Kupwad' => 'Kupwad (कुपवाड)'
                                    ];
                                    $currLoc = $edit_row['city_location'] ?? '';
                                    foreach ($locs as $val => $label) {
                                        $sel = ($currLoc == $val) ? 'selected' : '';
                                        echo "<option value=\"$val\" $sel>$label</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- City Area dropdown shown only when a city is selected -->
                            <div class="col-md-6" id="city_area_wrapper" style="display:none;">
                                <label for="city_area" class="form-label">एरिया निवडा (Select City Area)</label>
                                <select class="form-control" id="city_area" name="city_area">
                                    <option value="">Select Area</option>
                                    <?php if (!empty($edit_row['city_area'])): ?>
                                        <option value="<?php echo htmlspecialchars($edit_row['city_area']); ?>" selected><?php echo htmlspecialchars($edit_row['city_area']); ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Agriculture: District and Taluka (shown only when Area required = Agriculture) -->
                            <div class="col-md-6" id="agri_district_wrapper" style="display:none;">
                                <label for="agri_district" class="form-label">जिल्हा निवडा (Select District)</label>
                                <select class="form-control" id="agri_district" name="agri_district">
                                    <option value="">Select District</option>
                                    <?php 
                                    $adists = ['Sangli', 'Satara', 'Kolhapur', 'Solapur'];
                                    $currAD = $edit_row['agri_district'] ?? '';
                                    foreach ($adists as $d) {
                                        $sel = ($currAD == $d) ? 'selected' : '';
                                        echo "<option value=\"$d\" $sel>$d</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6" id="agri_taluka_wrapper" style="display:none;">
                                <label for="agri_taluka" class="form-label">तालुका निवडा (Select Taluka)</label>
                                <select class="form-control" id="agri_taluka" name="agri_taluka">
                                    <option value="">Select Taluka</option>
                                    <?php if (!empty($edit_row['agri_taluka'])): ?>
                                        <option value="<?php echo htmlspecialchars($edit_row['agri_taluka']); ?>" selected><?php echo htmlspecialchars($edit_row['agri_taluka']); ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>

                             <div class="col-md-6">
                                <label for="expected_rent" class="form-label">Budget (in Numbers)</label>
                                <input type="number" class="form-control" id="expected_rent" name="expected_rent" value="<?php echo $edit_row['expected_rent'] ?? ''; ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="deposit" class="form-label">Deposit (if Rent)</label>
                                <input type="number" class="form-control" id="deposit" name="deposit" value="<?php echo $edit_row['deposit'] ?? ''; ?>">
                            </div>

                            <!-- Agriculture: Select Proprety (Acar) -->
                            <div class="col-md-6" id="agri_property_size_wrapper" style="display:none;">
                                <label for="agri_property_size" class="form-label">Select Proprety (Acar)</label>
                                <select class="form-control" id="agri_property_size" name="agri_property_size">
                                    <option value="">Select Property Size</option>
                                    <?php
                                    $sizes = ['10 गुंठे ते 1 Acar', '1 Acar', '2 Acar', '3 Acar', '4 Acar', '5 Acar', '6 to 10 Acar', '10 to 50 Acar', '50 to 100 Acar', '100 to 200 Acar'];
                                    $currSize = $edit_row['agri_property_size'] ?? '';
                                    foreach ($sizes as $s) {
                                        $sel = ($currSize == $s) ? 'selected' : '';
                                        echo "<option value=\"$s\" $sel>$s</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="specification" class="form-label">Special Requirements</label>
                                <textarea class="form-control" id="specification" name="specification" rows="2" maxlength="250"><?php echo htmlspecialchars($edit_row['specification'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-12" align="center">
                                <button type="submit" class="btn btn-outline-primary px-5"><?php echo $edit_row ? 'Update Customer' : 'Submit'; ?></button>
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



                        document.getElementById('agri_district').addEventListener('change', function() {
                            const district = this.value;
                            const talukaSelect = document.getElementById('agri_taluka');
                            
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

                          // Initial values for edit mode
                          var initialPt = "<?php echo $edit_row['property_type'] ?? ''; ?>";

                          function populateLocationAreaOptions() {
                            var sr = saleRent.value;
                            var opts = (sr === 'Purchase')
                              ? ['Agriculture', 'Flat', 'Plot', 'Bungalow', 'Commercial']
                              : ['Flat', 'Plot', 'Bungalow', 'Commercial'];

                            var prev = locationArea.value || initialPt;
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
                            // locationArea.dispatchEvent(new Event('change'));
                            toggleCityLocation(); // Call manually to update visibility
                            toggleAgriFields();
                          }

                          // Update on change and initialize on load
                          saleRent.addEventListener('change', populateLocationAreaOptions);
                          locationArea.addEventListener('change', function() {
                              toggleCityLocation();
                              toggleAgriFields();
                          });
                          
                          // Initial call
                          // populateLocationAreaOptions(); // This might reset values if not careful
                          // Instead of full rebuild on load, just set visibility based on current values
                          toggleCityLocation();
                          toggleAgriFields();
                        });

                        function toggleCityLocation(){
                            var sr = $('#sale_rent').val();
                            var la = $('#location_area').val();
                            var showTypes = ['Flat','Plot','Commercial'];
                            if (sr && showTypes.indexOf(la) !== -1) {
                              $('#city_location_wrapper').show();
                            } else {
                              $('#city_location_wrapper').hide();
                              $('#city_area_wrapper').hide();
                            }
                        }
                        
                        function toggleAgriFields() {
                             var la = $('#location_area').val();
                             if (la === 'Agriculture') {
                                 $('#agri_district_wrapper').show();
                                 $('#agri_taluka_wrapper').show();
                                 $('#agri_property_size_wrapper').show();
                             } else {
                                 $('#agri_district_wrapper').hide();
                                 $('#agri_taluka_wrapper').hide();
                                 $('#agri_property_size_wrapper').hide();
                             }
                        }
                        </script>
                        
                    </div>
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
  // City -> Area lists
  var CITY_AREAS = {
    'Miraj': [
        'मिरज स्टेशन रोड', 'गांधी चौक', 'सिव्हिल हॉस्पिटल परिसर', 'वानलेसवाडी रोड', 'मिरज मेडिकल कॉलेज परिसर', 'रमा उद्यान', 'मिशन चौक', 
        'गुलाबराव पाटील कॉलेज परिसर', 'ईदगाह परिसर', 'विद्यानगर', 'सावली रोड', 'सुभाषनगर रोड', 'दत्त कॉलोनी', 'दिंडिवेस', 'नदीवेस', 
        'उदगाव वेस', 'सांगलीवेस', 'कमानवेस', 'विजापूर वेस', 'गणेश तलाव परिसर', 'ब्राह्मणपुरी', 'सांगली-मिरज रोड', 'लक्ष्मी मार्किट परिसर', 
        'शास्त्री चौक', 'किल्ला भाग', 'वखार भाग', 'समतानगर', 'माणिकनगर', 'सुंदरनगर', 'अल्फोंसा स्कूल परिसर'
    ],
    'Sangli': [
        'विश्रामबाग (सांगली)', 'विजयनगर परिसर', 'वानलेसवाडी', 'गव्हर्नमेंट कॉलोनी', 'स्फूर्ती चौक', '८० फुटी रोड', 'दत्तनगर', 
        'खरे क्लब हाऊस परिसर', 'धामणी रोड परिसर', 'कुंभार मळा', 'हसणे आश्रम रोड', 'वालचंद कॉलेज परिसर', '१०० फुटी रोड', 'नेमिनाथनगर', 
        'बापट मळा', 'गुलमोहर कॉलोनी', 'मंगलमूर्ती कॉलोनी', 'पुष्पराज चौक', 'राम मंदिर चौक परिसर', 'खणभाग', 'नळभाग', 'उत्तर शिवाजीनगर', 
        'बदाम चौक', 'SFC मॉल परिसर', 'माधवनगर रोड', 'कोल्हापूर रोड', 'आकाशवाणी परिसर', 'कॉलेज कॉर्नर परिसर', 'सिव्हिल हॉस्पिटल परिसर', 
        'गणपती पेठ', 'स्टेशन रोड (सांगली)', 'सांगली मार्केट यार्ड एरिया', 'हरिपूर रोड परिसर', 'पोदार स्कूल परिसर', 'तुळजाईनगर', 
        'लक्ष्मी मंदिर चौक परिसर', 'शिंदे मळा परिसर', 'चिन्मय पार्क', 'वखार भाग परिसर', 'आमराई रोड परिसर', 'डी मार्ट परिसर', 
        'पाकीजा मस्जिद परिसर', 'पालवी हॉटेल परिसर'
    ],
    'Kupwad': [
        'कुपवाड MIDC', 'सांगली–कुपवाड रोड', 'संजयनगर', 'अभयनगर', 'वसंतनगर', 'यशवंतनगर', 'सुतगिरनी चौक', 'बामनोली रोड', 
        'कुपवाड–सावळी रोड', 'कुपवाड - मिरज रोड', 'लठ्ठे कॉलेज परिसर', 'कुपवाड MIDC जवळील परिसर', 'औद्योगिक वसाहत परिसर'
    ]
  };

  $('#city_location').on('change', function(){
      var city = $(this).val();
      var areaSel = $('#city_area');
      areaSel.empty().append('<option value="">Select Area</option>');
      
      if (city && CITY_AREAS[city]) {
          CITY_AREAS[city].forEach(function(a){
              areaSel.append('<option value="'+a+'">'+a+'</option>');
          });
          $('#city_area_wrapper').show();
      } else {
          $('#city_area_wrapper').hide();
      }
  });

  // Mode buttons
  $('#btn_new_customer').on('click', function(){
      $(this).removeClass('btn-outline-primary').addClass('btn-primary');
      $('#btn_old_customer').removeClass('btn-primary').addClass('btn-outline-primary');
      $('#client_id_wrapper').hide();
      $('#client_id').val('');
  });
  $('#btn_old_customer').on('click', function(){
      $(this).removeClass('btn-outline-primary').addClass('btn-primary');
      $('#btn_new_customer').removeClass('btn-primary').addClass('btn-outline-primary');
      $('#client_id_wrapper').show();
  });
  
  // Initial check for edit mode
  <?php if($edit_row): ?>
    $('#client_id_wrapper').show();
  <?php endif; ?>
});
</script>
