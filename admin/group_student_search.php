<?php
	$res = array(); 
	include('../connection.php');
	if(!isset($_GET['search']) || $_GET['search']==''){
		try {
			$stmt = $conn->prepare("SELECT * FROM student s WHERE s.student_num NOT IN (SELECT student_num FROM group_student gs where gs.group_info_num = :group_info_num) order by surname asc");
			$stmt->bindParam(':group_info_num', $_SESSION['tmp_group_info_num'], PDO::PARAM_STR);
		    $stmt->execute();
		    $res = $stmt->fetchAll();
		    $_SESSION['res'] = $res;
		} catch (PDOException $e) {
			echo "Error ".$e->getMessage()." !!!";
		}
	}
	else{
		$q = $_GET['search'];
		foreach ($_SESSION['res'] as $val) {
			if (strpos(mb_strtolower($val['name']), mb_strtolower($q)) !== false 
				|| strpos(mb_strtolower($val['surname']), mb_strtolower($q)) !== false 
				|| strpos((mb_strtolower($val['surname'])."_".mb_strtolower($val['name'])), mb_strtolower($q)) !== false 
				|| strpos((mb_strtolower($val['name'])."_".mb_strtolower($val['surname'])), mb_strtolower($q)) !== false) {
				array_push($res, $val);
			}
		}
	}
?>
<?php 
	$count = 1;
	foreach ($res as $value) {
?>
<option value='<?php echo $value['student_num'];?>'><?php echo ($count++)."). ".$value['surname']." ".$value['name'];?></option>
<?php } ?>