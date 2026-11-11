<?php
require_once 'connection.php';
session_start();

if (!isset($_COOKIE['uuid']) || empty($_COOKIE['uuid'])) {
    header('Location: index.php');
    exit;
}

function nf($v) {
    if ($v === null || $v === '') return 0.0;
    $v = preg_replace('/[^0-9.\-]/', '', (string)$v);
    if ($v === '' || $v === null) return 0.0;
    return (float)$v;
}

// Gather inputs
$amount_head = mysqli_real_escape_string($link, $_POST['amount_head'] ?? '');
$pay_to = mysqli_real_escape_string($link, $_POST['pay_to'] ?? '');
$payment_date = mysqli_real_escape_string($link, $_POST['payment_date'] ?? date('Y-m-d'));
$property_id = mysqli_real_escape_string($link, $_POST['property_id'] ?? '');
$description = mysqli_real_escape_string($link, $_POST['description'] ?? '');

$registration_fee = nf($_POST['registration_fee'] ?? 0);
$visit_fee = nf($_POST['visit_fee'] ?? 0);
$promotional_charges = nf($_POST['promotional_charges'] ?? 0);
$deal_done_token = nf($_POST['deal_done_token'] ?? 0);
$transportation = nf($_POST['transportation'] ?? 0);

$account = mysqli_real_escape_string($link, $_POST['account'] ?? '');
$payment_mode = mysqli_real_escape_string($link, $_POST['payment_mode'] ?? 'Cash');
$amount_in_words = mysqli_real_escape_string($link, $_POST['amount_in_words'] ?? '');
$approved_by = mysqli_real_escape_string($link, $_POST['approved_by'] ?? '');
$received_by = mysqli_real_escape_string($link, $_POST['received_by'] ?? '');

$by = mysqli_real_escape_string($link, $_COOKIE['uuid'] ?? '');

$total = $registration_fee + $visit_fee + $promotional_charges + $deal_done_token + $transportation;

// Server-side fallback: if amount_in_words is empty, compute from total
if (trim($amount_in_words) === '') {
    $amount_in_words = mysqli_real_escape_string($link, amount_to_words_indian($total));
}

function amount_to_words_indian($amount) {
    $rupees = (int)floor((float)$amount);
    $paise = (int)round(((float)$amount - $rupees) * 100);
    $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    $two = function($n) use ($ones, $tens) {
        if ($n < 20) return $ones[$n];
        $t = (int)floor($n / 10); $o = $n % 10;
        return $tens[$t] . ($o ? ' ' . $ones[$o] : '');
    };
    $three = function($n) use ($two) {
        $s = '';
        if ($n >= 100) { $s .= $two((int)floor($n/100)) . ' Hundred'; $n = $n % 100; if ($n) $s .= ' '; }
        if ($n) $s .= $two($n);
        return $s;
    };
    $indian = function($n) use ($three) {
        if ($n === 0) return 'Zero';
        $crore = (int)floor($n / 10000000); $n %= 10000000;
        $lakh = (int)floor($n / 100000); $n %= 100000;
        $thousand = (int)floor($n / 1000); $n %= 1000;
        $hundred = $n;
        $parts = [];
        if ($crore) $parts[] = $three($crore) . ' Crore';
        if ($lakh) $parts[] = $three($lakh) . ' Lakh';
        if ($thousand) $parts[] = $three($thousand) . ' Thousand';
        if ($hundred) $parts[] = $three($hundred);
        return implode(' ', $parts);
    };
    $out = 'Rupees ' . $indian($rupees);
    if ($paise > 0) $out .= ' and ' . $two($paise) . ' Paise';
    return $out . ' only';
}

// Insert
$sql = sprintf(
    "INSERT INTO receipt (amount_head, pay_to, payment_date, property_id, description, registration_fee, visit_fee, promotional_charges, deal_done_token, transportation, account, payment_mode, total, amount_in_words, approved_by, received_by, `by`) VALUES ('%s','%s','%s','%s','%s', %.2f, %.2f, %.2f, %.2f, %.2f, '%s','%s', %.2f, '%s','%s','%s','%s')",
    $amount_head,
    $pay_to,
    $payment_date,
    $property_id,
    $description,
    $registration_fee,
    $visit_fee,
    $promotional_charges,
    $deal_done_token,
    $transportation,
    $account,
    $payment_mode,
    $total,
    $amount_in_words,
    $approved_by,
    $received_by,
    $by
);

if (!mysqli_query($link, $sql)) {
    die('Error saving receipt: ' . mysqli_error($link));
}

$insert_id = mysqli_insert_id($link);

header('Location: receipt.php?success=' . urlencode($insert_id));
exit;
?>