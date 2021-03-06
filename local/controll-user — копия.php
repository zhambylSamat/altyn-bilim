<?php 
include_once '../connection.php';
if(isset($_POST['signIn'])){
	// $_SESSION['tmp_username'] = $_POST['username']; 
	// $_SESSION['tmp_password'] = $_POST['password'];
	// header('location:check_mac.php');
	try {
		if($_SESSION[md5(md5('mac'))]){
			$stmt = $conn->prepare("SELECT * FROM student WHERE username = :username AND password = :password");
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			$stmt->bindParam(':password', $password, PDO::PARAM_STR);
			$username = $_POST['username'];
			$password = md5($_POST['password']); 
			// echo $password;
		    $stmt->execute();
		   	$result = $stmt->fetchAll();
		   	$result_count = $stmt->rowCount();
		    $count = 0;
		    if($result_count==0){
		    	header('location:signin.php');
		    }
		    foreach($result as $readrow){
		    	if(isset($readrow['student_num'])){
				    $_SESSION['student_name'] = $readrow['name'];
				    $_SESSION['student_surname'] = $readrow['surname'];
		    		if($readrow['password_type']=='default'){
		    			$_SESSION['default_student_num'] = $readrow['student_num'];
		    			header('location:reset.php');
		    		}
		    		else{
		    			$_SESSION['student_num'] = $readrow['student_num'];
			    		// $client  = @$_SERVER['HTTP_CLIENT_IP'];
					    // $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
					    // $remote  = $_SERVER['REMOTE_ADDR'];
					    // $is = '';
					    // if(filter_var($client, FILTER_VALIDATE_IP))
					    // {
					    //     $ip = $client;
					    // }
					    // elseif(filter_var($forward, FILTER_VALIDATE_IP))
					    // {
					    //     $ip = $forward;
					    // }
					    // else
					    // {
					    //     $ip = $remote;
					    // }
					    // $stmt = $conn->prepare("SELECT count(*) FROM user_connection_tmp WHERE ip = :ip AND student_num = :student_num");

					    // $stmt->bindParam(':student_num', $readrow['student_num'], PDO::PARAM_STR);
					    // $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
					    // $stmt->execute();
					    // $result_id = $stmt->fetchColumn();
			        	// if($result_id!=0){

						    // $_SESSION['ip_address'] = $ip;
				    		header('location:index.php');
				    	// }
				    	// else{
			    			// header('location:signin.php');
			    		// }
				    }
			    }
			    else{
		    		header('location:signin.php');
		    	}
		    } 
		}
		else if(!isset($_SESSION[md5(md5("mac"))])){
			header('location:signin.php?access=false');
		}
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
}
else if(isset($_GET[md5('resetPassword')])){
	// include_once('../connection.php');
	$data['success'] = false;
	$data['error'] = '';
	if($_POST['new-password']==''){
		$data['error'] .= 'Введите пароль! ';
	}
	else if($_POST['new-password']!=$_POST['confirm-password']){
		$data['error'] .= 'Пароли не соврадают! ';
	}
	else if(strlen($_POST['new-password'])<5){
		$data['error'] .= "Важно: Ваш пароль должен содержать не менее 5 символов! ";
	}
	else{
		try {
			$stmt = $conn->prepare("UPDATE student SET password = :password, password_type = 'notDefault' WHERE student_num = :student_num");
	   
		    $stmt->bindParam(':student_num', $_SESSION['default_student_num'], PDO::PARAM_STR);
		    $stmt->bindParam(':password', $password, PDO::PARAM_INT);
		    $password = md5($_POST['new-password']); 
		    $_SESSION['student_num'] = $_SESSION['default_student_num'];
		    $stmt->execute();
		    $data['success'] = true;
		} catch (PDOException $e) {
			$data['success'] = false;
			$data['error'] .= "Error : ".$e->getMessage()." !!!";
		}
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('test_result'))]) && isset($_SESSION['test_num']) && isset($_SESSION['test_data'])){
	$data = array();
	try {
		$data_json = json_decode($_POST['json'],true);
		$test_data = json_decode($_SESSION['test_data'],true);
		$true_answers = 0;
		$true_answer = 0;
		$wrong_answer = 0;
		$data['success'] = false;
		foreach ($test_data as $test_key => $test_value) {
			foreach ($test_value['answer'] as $answer_key => $answer_value) {
				if($answer_value['torf']=='1'){
					$true_answers ++;
				}
				if(isset($data_json[$test_key]) && in_array($answer_key,$data_json[$test_key]) && $answer_value['torf']=='1'){
					$true_answer ++;
				}
				else if(isset($data_json[$test_key]) && in_array($answer_key,$data_json[$test_key]) && $answer_value['torf']=='0'){
					$wrong_answer++;
				}
			}
		}
		$stmt = $conn->prepare("INSERT INTO test_result (test_result_num, student_num, test_num, submit_date, result) VALUES(:test_result_num, :student_num, :test_num, :submit_date, :result)");
	    $stmt->bindParam(':test_result_num', $test_result_num, PDO::PARAM_STR);
	    $stmt->bindParam(':student_num', $_SESSION['student_num'], PDO::PARAM_STR);
	    $stmt->bindParam(':test_num', $_SESSION['test_num'], PDO::PARAM_STR);
	    $stmt->bindParam(':submit_date', $submit_date, PDO::PARAM_STR);
	    $stmt->bindParam(':result', $result, PDO::PARAM_STR);

	    $submit_date = date("Y-m-d H:i:s");
	    $test_result_num = str_replace('.','',uniqid('TR', true));
	    $returned = 'none';
	    if($true_answer-$wrong_answer<=0){
			$result = 0;
			$returned = 'none';
		}
		else{
	    	$result = round(((($true_answer-$wrong_answer)/$true_answers)*100),2);
	    	if($result >= 80) {
	    		$returned = nextLevel();
	    	}
		}
	       
	    $stmt->execute();
	    if($returned=='none') $data['success'] = true;
	    else $data['error'] = $returned;
	} catch (PDOException $e) {
		$data['date'] = false;
		$data['error'] = "Error : ".$e->getMessage()." !!!";
	}
    $data['date'] = $submit_date;
    echo json_encode($data);
}
function nextLevel(){
	try {
		include('../connection.php');
		$stmt = $conn->prepare("SELECT subtopic_num FROM subtopic WHERE id>(SELECT id FROM subtopic WHERE subtopic_num = :subtopic_num) AND topic_num = :topic_num LIMIT 1");
		$stmt->bindParam(':subtopic_num', $_SESSION['subtopic_num'], PDO::PARAM_STR);
	    $stmt->bindParam(':topic_num', $_SESSION['topic_num'], PDO::PARAM_STR);
	    $stmt->execute();
	    $result_stmt = $stmt->fetch(PDO::FETCH_ASSOC);
	    $stmt_count = $stmt->rowCount();
	    if($stmt_count==1){
	    	$next_subtopic_num = $result_stmt['subtopic_num'];
	    	$stmt = $conn->prepare("SELECT * FROM student_permission WHERE student_num = :student_num");
			$stmt->bindParam(':student_num', $_SESSION['student_num'], PDO::PARAM_STR);
			$stmt->execute();
			$result_stmt = $stmt->fetch(PDO::FETCH_ASSOC);
			$student_permission_num = $result_stmt['student_permission_num'];

			// $stmt = $conn->prepare("UPDATE student_test_permission stp JOIN student_permission sp on sp.student_permission_num = stp.student_permission_num  SET stp.done = 'y' WHERE sp.student_num = :student_num AND stp.subtopic_num = :subtopic_num");
			$stmt = $conn->prepare("UPDATE student_test_permission  SET done = 'y' WHERE subtopic_num = :subtopic_num AND student_permission_num = (SELECT student_permission_num FROM student_permission WHERE student_num = :student_num)");
			$stmt->bindParam(':student_num', $_SESSION['student_num'], PDO::PARAM_STR);
			$stmt->bindParam(':subtopic_num', $_SESSION['subtopic_num'], PDO::PARAM_STR);
			$stmt->execute();

			$stmt = $conn->prepare("DELETE FROM student_test_permission WHERE student_permission_num = :student_permission_num AND subtopic_num = :subtopic_num");
	    	$stmt->bindParam(':subtopic_num', $next_subtopic_num, PDO::PARAM_STR);
	    	$stmt->bindParam(':student_permission_num', $student_permission_num, PDO::PARAM_STR);
	    	$stmt->execute();
	    	$result_count = $stmt->rowCount();

			$stmt = $conn->prepare("INSERT student_test_permission (student_permission_num, subtopic_num, video_permission, test_permission, done) VALUES(:student_permission_num, :subtopic_num, 't', 'f', 'n')");
			$stmt->bindParam(':student_permission_num', $student_permission_num, PDO::PARAM_STR);
			$stmt->bindParam(':subtopic_num', $next_subtopic_num, PDO::PARAM_STR);
			$stmt->execute();

	    }
		unset($_SESSION['test_num']);
		unset($_SESSION['topic_num']);
		unset($_SESSION['subtopic_num']);
		return 'none';
	} catch (PDOException $e) {
		return "Error : ".$e->getMessage()." !!!";
	}
}
?>