<?php 

	if (isset($_GET['id'])) {
		$ID = $_GET['id'];
	} else {
		$ID = "";
	}
		
	// get image file from menu table
	$sql_query = "SELECT channel_image FROM tbl_channel WHERE id = ?";
			
	$stmt = $connect->stmt_init();
	if ($stmt->prepare($sql_query)) {	
		// Bind your variables to replace the ?s
		$stmt->bind_param('s', $ID);
		// Execute query
		$stmt->execute();
		// store result 
		$stmt->store_result();
		$stmt->bind_result($channel_image);
		$stmt->fetch();
		$stmt->close();
	}
			
	// delete image file from directory
	$delete = unlink('upload/'."$channel_image");
	//$delete = unlink('upload/thumbs/'."$channel_image");
			
	// delete data from menu table
	$sql_query = "DELETE FROM tbl_channel WHERE id = ?";
			
	$stmt = $connect->stmt_init();
	if ($stmt->prepare($sql_query)) {	
		// Bind your variables to replace the ?s
		$stmt->bind_param('s', $ID);
		// Execute query
		$stmt->execute();
		// store result 
		$delete_result = $stmt->store_result();
		$stmt->close();
	}
				
	// if delete data success back to reservation page
	if($delete_result) {
		header("location: channel.php");
	}

?>