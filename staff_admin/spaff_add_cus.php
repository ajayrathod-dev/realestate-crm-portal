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
