<?php 
include_once '../connection.php';
if(isset($_POST['signIn'])){
		
	try {
		$username = $_POST['username'];
		$password = md5($_POST['password']); 
		$stmt = $conn->prepare("SELECT * FROM student WHERE username = :username AND password = :password AND block != 1");
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		// echo $password;
	    $stmt->execute();
	   	$readrow = $stmt->fetch(PDO::FETCH_ASSOC);
	   	$result_count = $stmt->rowCount();
	    $count = 0;
	    $no_payment = false;
	    if($result_count==0){
	    	header('location:signin.php');
	    }

	    $news_type = "student";
		$stmt = $conn->prepare("SELECT * FROM news WHERE type = :type");
		$stmt->bindParam(':type', $news_type, PDO::PARAM_STR);
		$stmt->execute();
		$news_res = $stmt->fetch(PDO::FETCH_ASSOC);
		$date = date("Y-m-d",strtotime(date("Y-m-d")."-7 days"));
		if($news_res['publish']==1 && $news_res['last_updated_date']>$date && ((isset($news_res['header']) && $news_res['header']!='') || (isset($news_res['content']) && $news_res['content']!='') || (isset($news_res['img']) && $news_res['img']!=''))){
			$_SESSION['news_res_student'] = $news_res;
			$_SESSION['news_notificaiton_student'] = 'true';
		}
    	if(isset($readrow['student_num'])){
    		if($readrow['block']==0 || ($readrow['block']==3 && date("Y-m-d")==date('Y-m-d',strtotime($readrow['block_date']))) || ($readrow['block']==5 && date("Y-m-d")==date('Y-m-d',strtotime($readrow['block_date'])))){
			    $_SESSION['student_name'] = $readrow['name'];
			    $_SESSION['student_surname'] = $readrow['surname'];
	    		if($readrow['password_type']=='default'){
	    			$_SESSION['default_student_num'] = $readrow['student_num'];
	    			header('location:reset.php');
	    		}
	    		else{
	    			$_SESSION['student_num'] = $readrow['student_num'];
	    			$_SESSION['access'] = md5('true');
	    			$stmt = $conn->prepare("SELECT content FROM news WHERE type = :student_num AND readed = 0");
					$stmt->bindParam(':student_num', $readrow['student_num'], PDO::PARAM_STR);
					$stmt->execute();
					$ccc = $stmt->rowCount();
					$news_res = $stmt->fetch(PDO::FETCH_ASSOC);
					if($ccc==1){
						$_SESSION['news_res_self_student'] = $news_res;
						$_SESSION['news_notificaiton_self_student'] = 'true';
					}

					$link = 'index.php';

					// $stmt = $conn->prepare("SELECT DISTINCT 
					// 			s.subject_num,
					// 			s.subject_name,
					// 			gi.group_info_num,
    	// 						gi.group_name,
					// 			ps.progress_student_num,
					// 			pg.created_date,
					// 		    ps.attendance
					// 		FROM subject s,
					// 			group_info gi,
					// 		    group_student gs,
					// 		    progress_group pg,
					// 		    progress_student ps
					// 		WHERE ps.student_num = :student_num    
					// 			AND ps.progress_student_num NOT IN (SELECT progress_student_num FROM student_reason)
					// 		    AND ps.progress_group_num = pg.progress_group_num
					// 		    AND pg.group_info_num = gi.group_info_num
					// 		    AND s.subject_num = gi.subject_num
					// 		    AND pg.created_date BETWEEN (CURDATE() - INTERVAL 1 MONTH ) and CURDATE()
					// 		ORDER BY s.subject_name, gi.group_name, pg.created_date ASC");
					// $stmt->bindParam(':student_num', $_SESSION['student_num'], PDO::PARAM_STR);
					// $stmt->execute();
					// $reason_result = $stmt->fetchAll();
					// $absents_arr = array();
					// $absents_arr_tmp = array();
					// $prev_abs = -1;
					// $group_info_num = '';
					// foreach ($reason_result as $key => $value) {
						
					// 	$sj_num = $value['subject_num'];
					// 	$gr_inf_num = $value['group_info_num'];
					// 	$attendance = $value['attendance'];
					// 	$sj_name = $value['subject_name'];
					// 	$gr_name = $value['group_name'];
					// 	$cr_date = $value['created_date'];
					// 	$pr_gr_num = $value['progress_student_num'];
					// 	// echo "(".$value['created_date']." -> ".$attendance.")<br>";
					// 	if($group_info_num!=$gr_inf_num || $attendance == 1){
					// 		// echo "enter att==1 or grinf equal";
					// 		$absents_arr_tmp = array();
					// 	}
					// 	if($attendance==0){
					// 		// echo "enter att==0 ";
					// 		$absents_arr_tmp[$sj_num]['subject_name'] = $sj_name;
					// 		$absents_arr_tmp[$sj_num]['group'][$gr_inf_num]['group_name'] = $gr_name;
					// 		$absents_arr_tmp[$sj_num]['group'][$gr_inf_num]['data'][$pr_gr_num] = $cr_date;
					// 	}
						
					// 	// print_r($absents_arr_tmp);
					// 	// echo "<br><br>";
					// 	if($group_info_num==$gr_inf_num && $prev_abs==0 && $attendance==0){
					// 		// echo count($absents_arr_tmp)."<br>";
					// 		if(count($absents_arr_tmp)!=0){
					// 			foreach ($absents_arr_tmp as $key => $value) {
					// 				$absents_arr[$sj_num]['subject_name'] = $value['subject_name'];
					// 				foreach ($value['group'] as $key => $value) {
					// 					$absents_arr[$sj_num]['group'][$gr_inf_num]['group_name'] = $value['group_name'];
					// 					foreach ($value['data'] as $key => $value) {
					// 						echo "<li>".$key." -- ".$value."</li>";
					// 						$absents_arr[$sj_num]['group'][$gr_inf_num]['data'][$key] = $value;
					// 					}
					// 				}
					// 			}
					// 			$absents_arr_tmp = array();
					// 		}

					// 		$absents_arr[$sj_num]['subject_name'] = $sj_name;
					// 		$absents_arr[$sj_num]['group'][$gr_inf_num]['group_name'] = $gr_name;
					// 		$absents_arr[$sj_num]['group'][$gr_inf_num]['data'][$pr_gr_num] = $cr_date;
					// 	}
					// 	$prev_abs = $attendance;
					// 	$group_info_num = $gr_inf_num;
					// }
					$stmt = $conn->prepare("SELECT count(*) as c FROM reason_info");
					$stmt->execute();
					if(intval($stmt->fetch(PDO::FETCH_ASSOC)['c'])>0){
						$stmt = $conn->prepare("SELECT DISTINCT 
									s.subject_num,
									s.subject_name,
									gi.group_info_num,
	    							gi.group_name,
									ps.progress_student_num,
									pg.created_date,
								    ps.attendance
								FROM subject s,
									group_info gi,
								    group_student gs,
								    progress_group pg,
								    progress_student ps
								WHERE ps.student_num = :student_num    
									AND ps.progress_student_num NOT IN (SELECT progress_student_num FROM student_reason)
								    AND ps.progress_group_num = pg.progress_group_num
								    AND pg.group_info_num = gi.group_info_num
								    AND s.subject_num = gi.subject_num
								    AND pg.created_date > '2017-12-31'
								    AND pg.created_date BETWEEN (CURDATE() - INTERVAL 1 MONTH ) and CURDATE()
								ORDER BY s.subject_name, gi.group_name, pg.created_date ASC");
						$stmt->bindParam(':student_num', $_SESSION['student_num'], PDO::PARAM_STR);
						$stmt->execute();
						$reason_result = $stmt->fetchAll();
						$absents_arr = array();
						foreach ($reason_result as $key => $value) {
							if($value['attendance']==0){
								$absents_arr[$value['subject_num']]['subject_name'] = $value['subject_name'];
								$absents_arr[$value['subject_num']]['group'][$value['group_info_num']]['group_name'] = $value['group_name'];
								$absents_arr[$value['subject_num']]['group'][$value['group_info_num']]['data'][$value['progress_student_num']] = $value['created_date'];
							}
						}
						if(count($absents_arr)>0){
							$_SESSION['access'] = md5('false');
							$link = 'reason.php';
							$_SESSION['reason'] = $absents_arr;
						}
					}
					// echo $link;
				 	header('location:'.$link);
			    }
			}
			else if($readrow['block']==2 || ($readrow['block']==3 && date("Y-m-d")!=date('Y-m-d',$readrow['block_date']))){
				header('location:signin.php?noPayment');
			}
			else if($readrow['block']==4 || ($readrow['block']==5 && date("Y-m-d")!=date('Y-m-d',$readrow['block_date']))){
				header('location:signin.php?noContract');
			}
	    }
	    else{
    		header('location:signin.php');
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
	else if(strlen($_POST['new-password'])<6){
		$data['error'] .= "Важно: Ваш пароль должен содержать не менее 6 символов! ";
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
	    $test_result_num = uniqid('TR', true)."_".time();
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
else if(isset($_POST['confirm_single_student_news'])){
	try {
		$readed = 1;
		$stmt = $conn->prepare("UPDATE news SET readed = :readed WHERE type = :student_num");
		$stmt->bindParam(':student_num', $_SESSION['student_num'], PDO::PARAM_STR);
		$stmt->bindParam(':readed', $readed, PDO::PARAM_STR);
		$stmt->execute();
		header('location:index.php');
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
}
else if(isset($_POST['submit_reason'])){
	try {
		$psn = $_POST['psn'];
		$reason_info_num = $_POST['reason'];
		// $student_reason_num_arr = array();

		$query = "INSERT INTO student_reason (student_reason_num, reason_info_num, progress_student_num) VALUES";
	    $qPart = array_fill(0, count($reason_info_num), "(?, ?, ?)");
	    $query .= implode(",",$qPart);
	    $stmtA = $conn->prepare($query);
	    $j = 1;
	    for($i = 0; $i<count($reason_info_num); $i++){
	    	$student_reason_num = uniqid('SR', true)."_".time();
	    	// array_push($student_reason_num_arr, $student_reason_num);
	    	$stmtA->bindValue($j++, $student_reason_num, PDO::PARAM_STR);
	    	$stmtA->bindValue($j++, $reason_info_num[$i], PDO::PARAM_STR);
	    	$stmtA->bindValue($j++, $psn[$i], PDO::PARAM_STR);
	    }
	    $stmtA->execute();

	 //    $stmt = $conn->prepare("UPDATE progress_student SET student_reason_num = ? WHERE student_num = ? AND progress_group_num = ?");
		// for ($i=0; $i < count($reason_info_num); $i++) {
		// 	$stmt->execute(array($student_reason_num_arr[$i], $student_num, $pgn[$i]));
		// }

		$_SESSION['access'] = md5('true');
		header('location:index.php');
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
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