<?php
session_start();
include('connection.php');
include('header.php');
$created_by = isset($_SESSION['uname']) ? $_SESSION['uname'] : 'staff';
$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    if ($edit_id !== '') {
        $stmt = mysqli_prepare($link, "SELECT * FROM properties WHERE property_id = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $edit_id);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if ($res) {
                $edit_row = mysqli_fetch_assoc($res);
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<style>
    .unit-group { display: none; }
    .required::after { content: ' *'; color: #d00; }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlspecialchars($_GET['success']); ?>
                                <?php if (!empty($_GET['log'])): ?>
                                    <br><small class="text-muted">Log: <?php echo nl2br(htmlspecialchars($_GET['log'])); ?></small>
                                <?php endif; ?>
                            </div>
                        <?php elseif (isset($_GET['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_GET['error']); ?>
                                <?php if (!empty($_GET['log'])): ?>
                                    <br><small class="text-muted">Log: <?php echo nl2br(htmlspecialchars($_GET['log'])); ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <h4 class="mb-3"> STAFF PANEL > <?php echo $edit_row ? 'EDIT PROPERTY' : 'ADD PROPERTY'; ?></h4>

         <form action="property_add1.php" method="post" enctype="multipart/form-data" class="card card-body">
            <?php if ($edit_row): ?>
                <input type="hidden" name="is_update" value="1" />
                <input type="hidden" name="original_property_id" value="<?php echo htmlspecialchars($edit_row['property_id']); ?>" />
            <?php endif; ?>
        <!-- Step 1: Select Property Type -->
        <div class="mb-3">
            <label class="form-label required">Property Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">Select</option>
                <?php
                $types_list = ['Agriculture', 'Plot', 'Flat', 'Bungalow', 'Commercial', 'Residential Rent', 'Commercial Rent'];
                foreach ($types_list as $t) {
                    $sel = ($edit_row && $edit_row['type'] == $t) ? 'selected' : '';
                    echo "<option value=\"$t\" $sel>$t</option>";
                }
                ?>
            </select>
        </div>

        <!-- Step 2: Auto-Generate ID (Sector-wise) with manual override -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label required">ID Mode</label>
                <select name="id_mode" id="id_mode" class="form-select" required>
                    <option value="new" <?php echo (!$edit_row) ? 'selected' : ''; ?>>NEW (auto-generate)</option>
                    <option value="old" <?php echo ($edit_row) ? 'selected' : ''; ?>>OLD (manual)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label required">Property ID</label>
                <input type="text" name="property_id" id="property_id" class="form-control" placeholder="e.g., AG0001" readonly required value="<?php echo $edit_row['property_id'] ?? ''; ?>" />
            </div>
            
        </div>

        <!-- Step 3: Property Units (dynamic) -->
        <div id="units_block" class="mb-3">
            <div class="unit-group" data-type="Agriculture">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Area Type</label>
                        <select name="unit_1" class="form-select">
                            <option value="Acre" <?php echo ($edit_row['unit_1']??'') == 'Acre' ? 'selected' : ''; ?>>Acre</option>
                            <option value="Guntha" <?php echo ($edit_row['unit_1']??'') == 'Guntha' ? 'selected' : ''; ?>>Guntha</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Area Size</label>
                        <input type="number" step="0.01" name="value_1" class="form-control" value="<?php echo $edit_row['value_1'] ?? ''; ?>" />
                    </div>
                </div>
            </div>
            <div class="unit-group" data-type="Plot">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Area Type</label>
                        <select name="unit_1" class="form-select">
                            <option value="Guntha" <?php echo ($edit_row['unit_1']??'') == 'Guntha' ? 'selected' : ''; ?>>Guntha</option>
                            <option value="Sq Ft" <?php echo ($edit_row['unit_1']??'') == 'Sq Ft' ? 'selected' : ''; ?>>Sq Ft</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Area Size</label>
                        <input type="number" step="0.01" name="value_1" class="form-control" value="<?php echo $edit_row['value_1'] ?? ''; ?>" />
                    </div>
                </div>
            </div>
            <div class="unit-group" data-type="Flat">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">BHK</label>
                        <input type="number" step="1" min="0" name="value_1" class="form-control" placeholder="e.g., 2" value="<?php echo $edit_row['value_1'] ?? ''; ?>" />
                        <input type="hidden" name="unit_1" value="BHK" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sq Ft</label>
                        <input type="number" step="0.01" name="value_2" class="form-control" value="<?php echo $edit_row['value_2'] ?? ''; ?>" />
                        <input type="hidden" name="unit_2" value="Sq Ft" />
                    </div>
                </div>
            </div>
            <div class="unit-group" data-type="Bungalow">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">BHK</label>
                        <input type="number" step="1" min="0" name="value_1" class="form-control" placeholder="e.g., 3" value="<?php echo $edit_row['value_1'] ?? ''; ?>" />
                        <input type="hidden" name="unit_1" value="BHK" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sq Ft</label>
                        <input type="number" step="0.01" name="value_2" class="form-control" value="<?php echo $edit_row['value_2'] ?? ''; ?>" />
                        <input type="hidden" name="unit_2" value="Sq Ft" />
                    </div>
                </div>
            </div>
            <div class="unit-group" data-type="Commercial">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Area Type</label>
                        <select name="unit_1" class="form-select">
                            <option value="Sq Ft" <?php echo ($edit_row['unit_1']??'') == 'Sq Ft' ? 'selected' : ''; ?>>Sq Ft</option>
                            <option value="Guntha" <?php echo ($edit_row['unit_1']??'') == 'Guntha' ? 'selected' : ''; ?>>Guntha</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Area Size</label>
                        <input type="number" step="0.01" name="value_1" class="form-control" value="<?php echo $edit_row['value_1'] ?? ''; ?>" />
                    </div>
                </div>
            </div>
            <div class="unit-group" data-type="Residential Rent">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">BHK</label>
                        <input type="number" step="1" min="0" name="value_1" class="form-control" value="<?php echo $edit_row['value_1'] ?? ''; ?>" />
                        <input type="hidden" name="unit_1" value="BHK" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sq Ft</label>
                        <input type="number" step="0.01" name="value_2" class="form-control" value="<?php echo $edit_row['value_2'] ?? ''; ?>" />
                        <input type="hidden" name="unit_2" value="Sq Ft" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rooms</label>
                        <input type="number" step="1" min="0" name="value_3" class="form-control" value="<?php echo $edit_row['value_3'] ?? ''; ?>" />
                    </div>
                </div>
            </div>
            <div class="unit-group" data-type="Commercial Rent">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Area Type (Sq Ft / Shop / Office)</label>
                        <select name="unit_1" class="form-select">
                            <option value="Sq Ft" <?php echo ($edit_row['unit_1']??'') == 'Sq Ft' ? 'selected' : ''; ?>>Sq Ft</option>
                            <option value="Shop" <?php echo ($edit_row['unit_1']??'') == 'Shop' ? 'selected' : ''; ?>>Shop</option>
                            <option value="Office" <?php echo ($edit_row['unit_1']??'') == 'Office' ? 'selected' : ''; ?>>Office</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Area Size</label>
                        <input type="number" step="0.01" name="value_1" class="form-control" value="<?php echo $edit_row['value_1'] ?? ''; ?>" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Property Address -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">Village</label>
                <input type="text" name="village" class="form-control" value="<?php echo $edit_row['village'] ?? ''; ?>" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Taluka</label>
                <input type="text" name="taluka" class="form-control" value="<?php echo $edit_row['taluka'] ?? ''; ?>" />
            </div>
            <div class="col-md-4">
                <label class="form-label">District</label>
                <input type="text" name="district" class="form-control" value="<?php echo $edit_row['district'] ?? ''; ?>" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Landmark</label>
                <input type="text" name="landmark" class="form-control" value="<?php echo $edit_row['landmark'] ?? ''; ?>" />
            </div>
            <!-- Full Address Removed -->
            <!-- <div class="col-md-6">
                <label class="form-label">Full Address</label>
                <input type="text" name="full_address" class="form-control" />
            </div> -->
            <div class="col-md-12">
                <label class="form-label">Google Map Link</label>
                <input type="url" name="google_map_link" class="form-control" value="<?php echo $edit_row['google_map_link'] ?? ''; ?>" />
            </div>
        </div>

        <!-- Step 5: Rates Section -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">Rate Unit Type</label>
                <select name="rate_unit" id="rate_unit" class="form-select">
                    <option value="Sq Ft" <?php echo ($edit_row['rate_unit']??'') == 'Sq Ft' ? 'selected' : ''; ?>>Sq Ft</option>
                    <option value="Acre" <?php echo ($edit_row['rate_unit']??'') == 'Acre' ? 'selected' : ''; ?>>Acre</option>
                    <option value="Guntha" <?php echo ($edit_row['rate_unit']??'') == 'Guntha' ? 'selected' : ''; ?>>Guntha</option>
                    <!-- <option value="Total">Total (Rupees)</option> -->
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Rate Amount</label>
                <input type="number" step="0.01" name="rate_value" class="form-control" value="<?php echo $edit_row['rate_value'] ?? ''; ?>" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Price Option</label>
                <select name="price_option" class="form-select">
                    <option value="">Selectâ€¦</option>
                    <option value="thousand" <?php echo ($edit_row['price_option']??'') == 'thousand' ? 'selected' : ''; ?>>thousand</option>
                    <option value="lakh" <?php echo ($edit_row['price_option']??'') == 'lakh' ? 'selected' : ''; ?>>lakh</option>
                    
                    
                    <option value="crore" <?php echo ($edit_row['price_option']??'') == 'crore' ? 'selected' : ''; ?>>crore</option>
                   
                    <!-- <option value="Total Rupees">Total Rupees</option> -->
                </select>
            </div>
            <div class="col-md-12" id="totalRupeesBlock" style="display:none;">
                <label class="form-label">Total Rupees Amount</label>
                <input type="number" step="0.01" name="total_rupees_amount" class="form-control" placeholder="Enter total amount in rupees" value="<?php echo $edit_row['total_rupees_amount'] ?? ''; ?>" />
            </div>
        </div>

        <!-- Step 6: Owner Details -->
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label required">Owner Name</label>
                <input type="text" name="owner_name" class="form-control" required value="<?php echo $edit_row['owner_name'] ?? ''; ?>" />
            </div>
            <div class="col-md-3">
                <label class="form-label required">Owner Contact</label>
                <input type="number" name="owner_contact" id="owner_contact" class="form-control" required value="<?php echo $edit_row['owner_contact'] ?? ''; ?>" />
                <!-- <small id="contact_error" class="text-danger" style="display:none;">This number already exists!</small> -->
            </div>
            <div class="col-md-3">
                <label class="form-label">Alternate Contact</label>
                <input type="number" name="alt_contact" class="form-control" value="<?php echo $edit_row['alt_contact'] ?? ''; ?>" />
            </div>
           
        </div>

        <!-- Step 7: Promotion Auto-Tag REMOVED -->
        <!--
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Promotion Invoice Generated?</label>
                <select name="promotion_invoice_generated" class="form-select">
                    <option value="0">NO</option>
                    <option value="1">YES</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Promotion Date (if paid)</label>
                <input type="date" name="promotion_date" class="form-control" />
            </div>
        </div>
        -->

        <!-- Step 8: Media Upload -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Upload Photos (min 3 â€“ max 10)</label>
                <input type="file" name="photos[]" class="form-control" accept="image/*" multiple />
                <?php if ($edit_row && !empty($edit_row['photos_json'])): ?>
                    <div class="mt-2">
                        <small>Current Photos:</small>
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $photos = json_decode($edit_row['photos_json'], true);
                            if (is_array($photos)) {
                                foreach ($photos as $p) {
                                    echo '<img src="'.htmlspecialchars($p).'" style="width:50px;height:50px;object-fit:cover;border:1px solid #ccc;">';
                                }
                            }
                            ?>
                        </div>
                        <small class="text-muted">New uploads will be added to these.</small>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-3" style="display:none;">
                <label class="form-label">Main Photo Index (1–10)</label>
                <input type="number" name="main_photo_index" class="form-control" min="1" max="10" value="1" />
            </div>
            <div class="col-md-3">
                <label class="form-label">Video Link</label>
                <input type="url" name="video_link" class="form-control" value="<?php echo $edit_row['video_link'] ?? ''; ?>" />
            </div>
            <div class="col-md-12">
                <label class="form-label">Location Note </label>
                <textarea name="location_note" rows="2" class="form-control"><?php echo $edit_row['location_note'] ?? ''; ?></textarea>
            </div>
        </div>

        <!-- Step 9: Save Property -->
        <div class="d-flex gap-2">
            <center><button type="submit" name="action" value="draft" class="btn btn-secondary">Submit</button></center>
            
        </div>
        <input type="hidden" name="created_by" value="<?php echo htmlspecialchars($created_by); ?>" />
    </form>

    <!-- Logs: Recently added properties with status and actions -->
 
<?php include 'footer.php'; ?>

<script>
function prefixForType(type) {
    switch (type) {
        case 'Agriculture': return 'AGRI';
        case 'Plot': return 'PLT';
        case 'Flat': return 'FLT';
        case 'Bungalow': return 'BGL';
        case 'Commercial': return 'CMR';
        case 'Residential Rent': return 'RRP';
        case 'Commercial Rent': return 'RCML';
        default: return '';
    }
}

function toggleUnits(type) {
    document.querySelectorAll('.unit-group').forEach(function(el){ 
        el.style.display = 'none'; 
        el.querySelectorAll('input, select').forEach(function(i) { i.disabled = true; });
    });
    var target = document.querySelector('.unit-group[data-type="' + type + '"]');
    if (target) {
        target.style.display = 'block';
        target.querySelectorAll('input, select').forEach(function(i) { i.disabled = false; });
    }
}

var rateUnitEl = document.getElementById('rate_unit');
var RATE_OPTIONS_ORIG = rateUnitEl ? Array.from(rateUnitEl.options).map(function(o){ return o.value; }) : [];
function setRateUnitOptions(list) {
    if (!rateUnitEl) return;
    rateUnitEl.innerHTML = '';
    list.forEach(function(v){
        var opt = document.createElement('option');
        opt.value = v;
        opt.textContent = v;
        rateUnitEl.appendChild(opt);
    });
}
function updateRateUnitOptions(type) {
    if (type === 'Flat') {
        setRateUnitOptions(['Sq Ft']);
    } else {
        if (RATE_OPTIONS_ORIG.length) setRateUnitOptions(RATE_OPTIONS_ORIG);
    }
}

function requestNextId(type) {
    var prefix = prefixForType(type);
    if (!prefix) { document.getElementById('property_id').value = ''; return; }
    fetch('next_property_id.php?type=' + encodeURIComponent(type) + '&t=' + new Date().getTime())
      .then(function(r){ return r.text(); })
      .then(function(txt){ document.getElementById('property_id').value = txt; })
      .catch(function(){ document.getElementById('property_id').value = prefix + '1001'; });
}

document.getElementById('type').addEventListener('change', function(){
    toggleUnits(this.value);
    updateRateUnitOptions(this.value);
    if (document.getElementById('id_mode').value === 'new') requestNextId(this.value);
});

document.getElementById('id_mode').addEventListener('change', function(){
    var mode = this.value;
    var type = document.getElementById('type').value;
    var idInput = document.getElementById('property_id');
    idInput.readOnly = (mode === 'new');
    if (mode === 'new' && type) requestNextId(type);
});

document.getElementById('regen_id').addEventListener('click', function(){
    var type = document.getElementById('type').value;
    if (type) requestNextId(type);
});

(function(){
    var typeSel = document.getElementById('type');
    if (typeSel) {
        updateRateUnitOptions(typeSel.value);
        toggleUnits(typeSel.value);
    }
})();
// Toggle Total Rupees amount input based on price option
(function(){
  var priceSelect = document.querySelector('select[name="price_option"]');
  var block = document.getElementById('totalRupeesBlock');
  function toggleTotal() {
    if (!priceSelect) return;
    block.style.display = (priceSelect.value === 'Total Rupees') ? 'block' : 'none';
  }
  if (priceSelect) {
    priceSelect.addEventListener('change', toggleTotal);
    toggleTotal();
  }
})();

// Check for existing owner contact - REMOVED per request to allow multiple properties per phone number
/*
document.getElementById('owner_contact').addEventListener('input', function() {
    var contact = this.value;
    var errorMsg = document.getElementById('contact_error');
    var submitBtn = document.querySelector('button[type="submit"]');

    if (contact.length > 5) { // minimal check before querying
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'check_owner_contact.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText.trim() === 'exists') {
                    errorMsg.style.display = 'block';
                    submitBtn.disabled = true;
                } else {
                    errorMsg.style.display = 'none';
                    submitBtn.disabled = false;
                }
            }
        };
        xhr.send('owner_contact=' + encodeURIComponent(contact));
    } else {
        errorMsg.style.display = 'none';
        submitBtn.disabled = false;
    }
});
*/
</script>
 
