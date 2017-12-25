<?php
include_once('../connection.php');
if(isset($_POST['signIn'])){
	try {
		$phone = $_POST['phone'];
		if($phone=='7059009356' || $phone=='7475665750'){
			$_SESSION['tst_number'] = $phone;
		}
		header('location:index.php');
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
}
?>