<?php

	$id = isset($_GET['id']) ? $_GET['id'] : false;

	if (! $id) {
		die('404 Oops!!');
	}

	// if (isset($_POST['btnDelete'])) {

		$id = $_POST['id'];

		$sql = "DELETE FROM tbl_user WHERE id = '$id'";
		$delete = $connect->query($sql);

		if($delete) {
		header("location: members.php");
	}
// 		$succes =<<<EOF
// 			<script>
// 			alert('Delete User Success');
// 			window.location = 'members.php';
// 			</script>
// EOF;
			// echo $succes;

	// } else if (isset($_POST['btnNo'])) {
	// 	header('location:members.php');
	// 	exit();
	// }
?>