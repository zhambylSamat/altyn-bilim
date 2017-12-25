<?php
include_once('../connection.php');
if(isset($_POST['signIn'])){
	try {
		$stmt = $conn->prepare("SELECT * FROM parent WHERE phone = :phone");
		$stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
		$phone = $_POST['phone'];
		$stmt->execute();
	   	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	   	
	   	if($stmt->rowCount()==1){
	   		$_SESSION['parent_num'] = $result['parent_num'];
	   		$_SESSION['parent_name'] = $result['name'];
	   		$_SESSION['parent_surname'] = $result['surname'];
	   		$_SESSION['parent_phone'] = $result['phone'];
	   	}	
	   	header('location:index.php');
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
}
?>