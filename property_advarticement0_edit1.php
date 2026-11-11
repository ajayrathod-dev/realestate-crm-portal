<?php
session_start();
include 'connection.php';

function esc($link, $val) {
    return mysqli_real_escape_string($link, $val ?? '');
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$property_id = esc($link, $_POST['property_id'] ?? '');
$content = esc($link, $_POST['content'] ?? '');
$owner_name = esc($link, $_POST['owner_name'] ?? '');
$paid_video_amount = esc($link, $_POST['paid_video_amount'] ?? '');
$transportation = esc($link, $_POST['transportation'] ?? '');
$promotion_exec = esc($link, $_POST['promotion_closing_executive_name'] ?? '');
$closing_date = esc($link, $_POST['closing_date'] ?? '');
$work_order_video = esc($link, $_POST['work_order_video'] ?? '');
$regular_office_video = esc($link, $_POST['regular_office_video'] ?? '');
$video_post_date = esc($link, $_POST['video_post_date'] ?? '');
$remark = esc($link, $_POST['remark'] ?? '');

$sql = "UPDATE property_advarticement0 SET
    property_id='$property_id',
    content='$content',
    owner_name='$owner_name',
    paid_video_amount='$paid_video_amount',
    transportation='$transportation',
    promotion_closing_executive_name='$promotion_exec',
    closing_date=NULLIF('$closing_date',''),
    work_order_video='$work_order_video',
    regular_office_video='$regular_office_video',
    video_post_date=NULLIF('$video_post_date',''),
    remark='$remark'
  WHERE id=$id";

if (mysqli_query($link, $sql)) {
    header('Location: property_advarticement0.php?msg=Updated');
    exit;
} else {
    $err = urlencode('Error: '.mysqli_error($link));
    header('Location: property_advarticement0_edit.php?id='.$id.'&msg='.$err);
    exit;
}
?>