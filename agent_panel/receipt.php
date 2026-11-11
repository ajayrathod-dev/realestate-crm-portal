<?php
require_once 'connection.php';
session_start();

// Match the app's auth and header inclusion pattern
if (isset($_COOKIE['uuid'])) {
    $u_uname = mysqli_real_escape_string($link, $_COOKIE['uuid']);
} else {
    $u_uname = '';
}
$sql4 = "SELECT * FROM ods WHERE uuid='$u_uname' AND status='verified'";
$result4 = mysqli_query($link, $sql4);
if ($result4 && $result4->num_rows > 0) {
    while ($row4 = $result4->fetch_assoc()) {
        $u_uname = mysqli_real_escape_string($link, $row4['user_id']);
        $br_id = mysqli_real_escape_string($link, $row4['user_id']);
    }
} else {
    header("location:logout.php");
    exit;
}

// Fetch existing receipts for log
$receipts = [];
if ($link) {
    $sql = "SELECT id, pay_to, property_id, payment_date, total, payment_mode, `by` FROM receipt ORDER BY id DESC LIMIT 200";
    $res = mysqli_query($link, $sql);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $receipts[] = $row;
        }
        mysqli_free_result($res);
    }
}

include('header.php');
?>

<div class="page-wrapper">
  <div class="page-content">
<div class="container-fluid py-2">
    <div class="d-flex align-items-center mb-3">
        <h3 class="mb-0">Receipt / Voucher</h3>
        <div class="ms-auto">
            <?php if (isset($_GET['success'])): ?>
                <span class="badge bg-success">Saved: #<?php echo htmlspecialchars($_GET['success']); ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="card-title">Enter Receipt Details</h6>
            <form action="receipt1.php" method="post" id="receiptForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label required">Amount Head</label>
                        <input type="text" name="amount_head" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Pay to</label>
                        <input type="text" name="pay_to" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" name="payment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Reg. / Property ID</label>
                        <input type="text" name="property_id" class="form-control">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>

                    <div class="col-12"><div class="form-section-title">Fees</div></div>
                    <div class="col-md-4">
                        <label class="form-label">Registration Fee</label>
                        <input type="number" step="0.01" name="registration_fee" class="form-control fee" placeholder="0.00">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Visit Fee</label>
                        <input type="number" step="0.01" name="visit_fee" class="form-control fee" placeholder="0.00">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Promotional Charges</label>
                        <input type="number" step="0.01" name="promotional_charges" class="form-control fee" placeholder="0.00">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Deal Done Token</label>
                        <input type="number" step="0.01" name="deal_done_token" class="form-control fee" placeholder="0.00">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Transportation</label>
                        <input type="number" step="0.01" name="transportation" class="form-control fee" placeholder="0.00">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">A/c</label>
                        <input type="text" name="account" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Mode</label>
                        <select name="payment_mode" class="form-select">
                            <option value="Cash">Cash</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total</label>
                        <input type="number" step="0.01" name="total" id="total" class="form-control" placeholder="0.00" readonly>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Amount in words</label>
                        <input type="text" name="amount_in_words" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Reg. No. (optional)</label>
                        <input type="text" name="receipt_no" class="form-control" placeholder="Auto">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Payment Approved by</label>
                        <input type="text" name="approved_by" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Received by</label>
                        <input type="text" name="received_by" class="form-control">
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Receipt</button>
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="card-title">Receipt Log</h6>
            <div class="table-responsive">
                <table id="example2" class="table table-sm table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>R. No.</th>
                            <th>Date</th>
                            <th>Pay to</th>
                            <th>Property ID</th>
                            <th class="text-end">Total</th>
                            <th>Mode</th>
                            <th>By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($receipts)): ?>
                        <tr><td colspan="8" class="text-center text-muted">No receipts yet</td></tr>
                    <?php else: foreach ($receipts as $r): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($r['id']); ?></td>
                            <td><?php echo htmlspecialchars($r['payment_date']); ?></td>
                            <td><?php echo htmlspecialchars($r['pay_to']); ?></td>
                            <td><?php echo htmlspecialchars($r['property_id']); ?></td>
                            <td class="text-end"><?php echo number_format((float)$r['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($r['payment_mode']); ?></td>
                            <td><?php echo htmlspecialchars($r['by']); ?></td>
                            <td>
                                <a class="btn btn-sm btn-outline-secondary" target="_blank" href="receipt_print.php?id=<?php echo urlencode($r['id']); ?>">Print Receipt</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
  </div>
</div>
<script>
// Auto-calc total on client for convenience (server will re-calc)
function calcTotal() {
    let sum = 0;
    document.querySelectorAll('.fee').forEach(function(inp){
        const val = parseFloat(inp.value);
        if (!isNaN(val)) sum += val;
    });
    document.getElementById('total').value = sum.toFixed(2);
    // Update amount in words automatically
    const words = amountToWords(sum);
    const wordsInput = document.querySelector('input[name="amount_in_words"]');
    if (wordsInput) wordsInput.value = words;
}
document.querySelectorAll('.fee').forEach(function(inp){
    inp.addEventListener('input', calcTotal);
});
calcTotal();

// Convert number to Indian currency words (Rupees and Paise)
function amountToWords(amount) {
    if (isNaN(amount)) return '';
    amount = parseFloat(amount);
    const rupees = Math.floor(amount);
    const paise = Math.round((amount - rupees) * 100);

    function twoDigits(n){
        const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        if (n < 20) return ones[n];
        const t = Math.floor(n / 10), o = n % 10;
        return tens[t] + (o ? ' ' + ones[o] : '');
    }
    function threeDigits(n){
        let s = '';
        if (n >= 100) { s += twoDigits(Math.floor(n/100)) + ' Hundred'; n = n % 100; if (n) s += ' '; }
        if (n) s += twoDigits(n);
        return s;
    }
    function indianGroupWords(n){
        if (n === 0) return 'Zero';
        const crore = Math.floor(n / 10000000); n %= 10000000;
        const lakh = Math.floor(n / 100000); n %= 100000;
        const thousand = Math.floor(n / 1000); n %= 1000;
        const hundred = n;
        const parts = [];
        if (crore) parts.push(threeDigits(crore) + ' Crore');
        if (lakh) parts.push(threeDigits(lakh) + ' Lakh');
        if (thousand) parts.push(threeDigits(thousand) + ' Thousand');
        if (hundred) parts.push(threeDigits(hundred));
        return parts.join(' ');
    }

    let out = 'Rupees ' + indianGroupWords(rupees);
    if (paise > 0) out += ' and ' + twoDigits(paise) + ' Paise';
    return out + ' only';
}
</script>
<?php include('footer.php'); ?>