<?php
include '../zcommon/common.inc';

$browser = $_SERVER['HTTP_USER_AGENT'];
RecordCommand("DELETE IMAGE$browser");
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

	$id = $_POST['ID'];
	$userid = $_POST['USERID'];

	if ($userid == "9000")
		return;
	if (strlen($userid) < 3)
		return;
	$datecode = $_POST['DATECODE'];
	$image = $_POST['IMAGE'];
	RecordCommand("DELETE $userid $datecode $image");

	$base_dir = "$BASE_DIR/work/";
	$delete_dir = "$BASE_DIR/work/XDELETED/";

	$con = mysqli_connect('localhost',"cminson","ireland","mydb");
	$query = "delete from opstrack where image='$image';";
	mysqli_query($con, $query);
	RecordCommand("DELETE $query");

    $date = $datecode."C";
    $file = "$base_dir$date/$image";
	$deleteFile = "$delete_dir/$userid".":"."$image";
	$command = "mv $file $deleteFile";
    exec("$command 2>&1", $l, $ConvertResultCode);
	RecordCommand("DELETE $command");
	mysqli_close($con);
}

?>
