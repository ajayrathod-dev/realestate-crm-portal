<?php
require_once 'connection.php';
session_start();

if (!isset($_COOKIE['uuid']) || empty($_COOKIE['uuid'])) {
    header('Location: index.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('Invalid receipt id');
}

$sql = "SELECT * FROM receipt WHERE id=" . $id . " LIMIT 1";
$res = mysqli_query($link, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    die('Receipt not found');
}
$r = mysqli_fetch_assoc($res);
mysqli_free_result($res);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt #<?php echo htmlspecialchars($r['id']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .voucher {
            background: #fff;
            border: 2px solid #c78da6;
            padding: 16px;
        }
        .voucher h4 { margin: 0; font-weight: 700; }
        .voucher .header-row { border-bottom: 2px solid #c78da6; padding-bottom: 8px; margin-bottom: 10px; }
        .voucher .side-band { position: absolute; right: -50px; top: 0; bottom: 0; width: 48px; background: #c78da6; color: #fff; writing-mode: vertical-rl; text-orientation: mixed; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .voucher .section-title { font-weight: 600; margin-top: 8px; }
        .voucher table td { padding: 6px; vertical-align: middle; }
        .voucher .amounts td { border: 1px solid #c78da6; }
        .print-actions { margin-bottom: 12px; }
        @media print {
            .print-actions { display: none; }
            body { background: #fff; }
        }
    </style>
</head>
<body>
<div class="container py-3">
    <div class="print-actions d-flex gap-2">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
        <a class="btn btn-outline-secondary" href="receipt.php">Back</a>
    </div>

    <div class="position-relative">
        <div class="voucher">
            <div class="row header-row">
                <div class="col-9">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img src="logo.png" alt="Logo" style="height:48px" onerror="this.style.display='none'">
                        </div>
                        <div>
                            <h4>प्रॉपर्टी पत्रिका</h4>
                            <div style="font-size:12px">ऑफिस: रोड नं. ४, पार्वतीनाथ कॉलनी, भापानी रोड, सांगली 416 416. मोब.: 9529609408</div>
                        </div>
                    </div>
                </div>
                <div class="col-3 text-end">
                    <div style="font-weight:700">RECEIPT</div>
                    <div>R. No.: <strong><?php echo htmlspecialchars($r['id']); ?></strong></div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-7">
                    <div>Amount Head: <strong><?php echo htmlspecialchars($r['amount_head']); ?></strong></div>
                    <div>Pay to: <strong><?php echo htmlspecialchars($r['pay_to']); ?></strong></div>
                </div>
                <div class="col-md-5 text-end">
                    <div>Date: <strong><?php echo htmlspecialchars($r['payment_date']); ?></strong></div>
                </div>
            </div>

            <div class="mb-2">Description: <strong><?php echo htmlspecialchars($r['description']); ?></strong></div>

            <table class="table table-sm amounts">
                <tbody>
                    <tr>
                        <td style="width:60%">Reg. / Property ID</td>
                        <td style="width:40%" class="text-end"><?php echo htmlspecialchars($r['property_id']); ?></td>
                    </tr>
                    <tr>
                        <td>Registration Fee</td>
                        <td class="text-end"><?php echo number_format((float)$r['registration_fee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Visit Fee</td>
                        <td class="text-end"><?php echo number_format((float)$r['visit_fee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Promotional Charges</td>
                        <td class="text-end"><?php echo number_format((float)$r['promotional_charges'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Deal Done Token</td>
                        <td class="text-end"><?php echo number_format((float)$r['deal_done_token'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Transportation</td>
                        <td class="text-end"><?php echo number_format((float)$r['transportation'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>A/c</td>
                        <td class="text-end"><?php echo htmlspecialchars($r['account']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>CASH / ONLINE</strong></td>
                        <td class="text-end"><strong><?php echo htmlspecialchars($r['payment_mode']); ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>TOTAL</strong></td>
                        <td class="text-end"><strong><?php echo number_format((float)$r['total'], 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="row mb-2">
                <div class="col-12">Amount in words: <strong><?php echo htmlspecialchars($r['amount_in_words']); ?></strong></div>
            </div>
            <div class="row">
                <div class="col-md-6">Payment Approved by: <strong><?php echo htmlspecialchars($r['approved_by']); ?></strong></div>
                <div class="col-md-6">Received by: <strong><?php echo htmlspecialchars($r['received_by']); ?></strong></div>
            </div>
            <div class="mt-3" style="font-size:12px">This payment is considered non-refundable under policy.</div>
        </div>
        <div class="side-band">VOUCHER</div>
    </div>
</div>
</body>
</html>