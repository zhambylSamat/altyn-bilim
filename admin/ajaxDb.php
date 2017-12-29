<?php
$includeBool = false;
$error = array();
$data = array();
$data['script'] = "";
$data['text'] = "";
$data['success'] = false;
$data['error'] = '';
$question_img = '';


if(isset($_GET[md5(md5('new_question'))])){
	if((isset($_POST['question-txt']) && $_POST['question-txt']!= '') ||  (isset($_FILES['question-img']) && $_FILES['question-img']['name']!='') || (isset($_POST['question-img-hidden']) && $_POST['question-img-hidden']!='')){
		$data['script'] .= "<script type='text/javascript'> window['notEmptyQuestion'](); </script>";
		$photo_path = '../img/test/';
		$photo_path = $photo_path.basename($_FILES['question-img']['name']);
		$photo_torf = true;
		if($_FILES['question-img']['name']!=''){
			$question_img = $_FILES['question-img']['name'];
			$photo_torf = checkFile($photo_path, $_FILES['question-img']['name'], $_FILES['question-img']['tmp_name'], $_FILES['question-img']['size']);
		}
		else if(isset($_POST['question-img-hidden'])){
			$question_img = $_POST['question-img-hidden'];
		}
		if($photo_torf){
			if(isset($_POST['torf'])){
				$data['script'] .= "<script type='text/javascript'> window['notEmptyCheckbox'](); </script>";
				addToDB();
			}
			else{
				$data['script'] .= "<script type='text/javascript'> emptyCheckbox.call(); </script>";
				echo json_encode($data);
			}
		}
		else{
			echo json_encode($data);
		}
	}
	else{
		$data['script'] .= "<script type='text/javascript'> emptyQuestion.call(); </script>";
		echo json_encode($data);
	}	
}
if(isset($_POST['delete_question']) && $_POST['delete_question'] = 'delete_question' && isset($_POST['question_num'])){
	deleteQuestion($_POST['question_num']);
	deleteAnswer($_POST['question_num']);
	// $obj = requireToVar('ajax_adminTest.php');
	$data['text'] = '';
	$data['success']=true;
	echo json_encode($data);

}
if(isset($_GET[md5(md5('createStudent'))])){
	try {
		include_once('../connection.php');
		$stmt = $conn->prepare("INSERT INTO student (student_num, name, surname, password, username, password_type) VALUES(:student_num, :name, :surname, :password, :username, 'default')");
   
	    $stmt->bindParam(':student_num', $student_num, PDO::PARAM_STR);
	    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	    $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	    // $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
	    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

	    $student_num = uniqid('US', true);
	    $name = $_POST['name'];
	    $surname = $_POST['surname'];
	    // $gender = $_POST['gender'];
	    $username = mb_strtolower($_POST['username']);
	    $password = md5('12345');
	       
	    $stmt->execute();
	    $data['success'] = true;
	    $data['response_code'] = $response_code;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	// $obj = requireToVar('index_students.php');
	$data['text'] .= '';
	echo json_encode($data);
}
if(isset($_GET[md5(md5('createTeacher'))])){
	try {
		include_once('../connection.php');
		$stmt = $conn->prepare("INSERT INTO teacher (teacher_num, name, surname, password, username) VALUES(:teacher_num, :name, :surname, :password, :username)");
   
	    $stmt->bindParam(':teacher_num', $teacher_num, PDO::PARAM_STR);
	    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	    $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
	    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

	    $teacher_num = uniqid('UT', true);
	    $name = $_POST['name'];
	    $surname = $_POST['surname'];
	    $username = strtolower($_POST['username']);
	    $password = md5('123456');
	       
	    $stmt->execute();
	    $data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	// $obj = requireToVar('index_students.php');
	$data['text'] .= '';
	echo json_encode($data);
}
if(isset($_GET[md5(md5('createGroup'))])){
	try {
		include_once('../connection.php');
		$stmt = $conn->prepare("INSERT INTO group_info (group_info_num, subject_num, teacher_num, group_name, comment) VALUES(:group_info_num, :subject_num, :teacher_num, :group_name, :comment)");
   
	    $stmt->bindParam(':group_info_num', $group_info_num, PDO::PARAM_STR);
	    $stmt->bindParam(':subject_num', $subject_num, PDO::PARAM_STR);
	    $stmt->bindParam(':teacher_num', $teacher_num, PDO::PARAM_STR);
	    $stmt->bindParam(':group_name', $group_name, PDO::PARAM_STR);
	    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

	    $group_info_num = uniqid('GI', true);
	    $subject_num = $_POST['subject'];
	    $teacher_num = $_POST['teacher'];
	    $group_name = $_POST['group_name'];
	    $comment = $_POST['comment'];
	       
	    $stmt->execute();
	    $data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	// $obj = requireToVar('index_students.php');
	$data['text'] .= '';
	echo json_encode($data);
}
if(isset($_GET[md5(md5('createParent'))])){
	try {
		include_once('../connection.php');
		$parent_num = uniqid('P',true);
		$name = $_POST['parent_name'];
		$surname = $_POST['parent_surname'];
		$phone = $_POST['parent_phone'];
		$student_num = $_POST['students'];
		$data['error'].="   ".$phone."   ";
		$stmt = $conn->prepare("INSERT INTO parent (parent_num, name, surname, phone) VALUES(:parent_num, :name, :surname, :phone)");
		$stmt->bindParam(':parent_num', $parent_num, PDO::PARAM_STR);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
		$stmt->bindParam(':phone', $phone, PDO::PARAM_INT);
		$stmt->execute();
		$query = "INSERT INTO child (child_num, parent_num, student_num) VALUES";
	    $qPart = array_fill(0, count($student_num), "(?, ?, ?)");
	    $query .= implode(",",$qPart);
	    $stmtA = $conn->prepare($query);
	    $j = 1;
	    for($i = 0; $i<count($student_num); $i++){
	    	$child_num = uniqid('CH',true);
	    	$stmtA->bindValue($j++, $child_num, PDO::PARAM_STR);
	    	$stmtA->bindValue($j++, $parent_num, PDO::PARAM_STR);
	    	$stmtA->bindValue($j++, $student_num[$i], PDO::PARAM_STR);
	    }
	    $stmtA->execute();

	    $data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	// $obj = requireToVar('index_students.php');
	$data['text'] .= '';
	echo json_encode($data);
}
if(isset($_GET[md5(md5('set_permission'))])){
	try {
		include_once('../connection.php');

		$stmt = $conn->prepare("SELECT video_num FROM video WHERE subtopic_num = :subtopic_num");
		$stmt->bindParam(':subtopic_num', $subtopic_num, PDO::PARAM_STR);
		$subtopic_num = $_POST['extra_num'];
		$stmt->execute();
		$video_exist = ($stmt->rowCount()==0) ? false : true;

		if($video_exist){
			$stmt = $conn->prepare("INSERT IGNORE INTO student_permission (student_permission_num, student_num) VALUES(:student_permission_num, :student_num) ");
	 
		    $stmt->bindParam(':student_permission_num', $student_permission_num, PDO::PARAM_STR);
		    $stmt->bindParam(':student_num', $student_num, PDO::PARAM_STR);

		    $student_permission_num = uniqid('S_P', true);
		    $student_num = $_POST['data_num'];

		    $stmt->execute();

		    $stmt_check = $conn->prepare("SELECT stp.student_permission_num studentPermissionNum FROM student_test_permission stp, student_permission sp WHERE sp.student_num = :student_num AND stp.student_permission_num = sp.student_permission_num AND stp.subtopic_num = :subtopic_num");

		    $stmt_check->bindParam(':student_num', $student_num, PDO::PARAM_STR);
		    $stmt_check->bindParam(':subtopic_num', $subtopic_num, PDO::PARAM_STR);

		    $subtopic_num = $_POST['extra_num'];
		    $video_permission = isset($_POST['video_permission']) ? "t" : "f";
		    $test_permission = isset($_POST['test_permission']) ? "t" : "f";

		    $stmt_check->execute();
	        $result_exists = $stmt_check->rowCount(); 
	        if($result_exists==0){
	        	$stmt2=$conn->prepare("INSERT INTO student_test_permission (student_permission_num, subtopic_num, video_permission, test_permission) SELECT (SELECT student_permission_num FROM student_permission WHERE student_num = :student_num2), :subtopic_num, :video_permission, :test_permission");
	        	$stmt2->bindParam(':student_num2', $student_num, PDO::PARAM_STR);
			    $stmt2->bindParam(':subtopic_num', $subtopic_num, PDO::PARAM_STR);
			    $stmt2->bindParam(':video_permission', $video_permission, PDO::PARAM_STR);
			    $stmt2->bindParam(':test_permission', $test_permission, PDO::PARAM_STR);
			    $stmt2->execute();
	        }
	        else if($result_exists==1){
	        	$result = $stmt_check->fetch(PDO::FETCH_ASSOC);
	        	$stmt2 = $conn->prepare("UPDATE student_test_permission SET video_permission = :video_permission, test_permission = :test_permission WHERE student_permission_num = :student_permission_num AND subtopic_num = :subtopic_num");
	   
			   	$stmt2->bindParam(':student_permission_num', $result['studentPermissionNum'], PDO::PARAM_STR);
			    $stmt2->bindParam(':subtopic_num', $subtopic_num, PDO::PARAM_STR);
			    $stmt2->bindParam(':video_permission', $video_permission, PDO::PARAM_STR);
			    $stmt2->bindParam(':test_permission', $test_permission, PDO::PARAM_STR);
			       
			    $stmt2->execute();
	        }
	    }
    	else if(!$video_exist){
    		$data['text'] = "noVideo";
    	}
        $data['success'] = true;
	    // header('location:group.php?data_num='.$_SESSION['tmp_group_info_num']);
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
if(isset($_GET[md5(md5('schedule'))])){
	try {
		include_once('../connection.php');
		$start_lesson = $_POST['start_hour'].":".$_POST['start_minute'].":00";
		$finish_lesson = $_POST['finish_hour'].":".$_POST['finish_minute'].":00"; 
		$group_info_num = $_POST['data_num'];
		$weeks = $_POST['week_id'];
		$office = $_POST['office'];

		$stmt = $conn->prepare("UPDATE group_info SET start_lesson = :start_lesson, finish_lesson = :finish_lesson, office_number = :office WHERE group_info_num = :group_info_num");
		$stmt->bindParam(':group_info_num', $group_info_num, PDO::PARAM_STR);
		$stmt->bindParam(':office', $office, PDO::PARAM_STR);
		$stmt->bindParam(':start_lesson', $start_lesson, PDO::PARAM_STR);
		$stmt->bindParam(':finish_lesson', $finish_lesson, PDO::PARAM_STR);
		$stmt->execute();

		$stmt = $conn->prepare("DELETE FROM schedule WHERE group_info_num = :group_info_num");
		$stmt->bindParam(':group_info_num', $group_info_num, PDO::PARAM_STR);
		$stmt->execute();

		$query = "INSERT INTO schedule (group_info_num, week_id) VALUES";
	    $qPart = array_fill(0, count($weeks), "(?, ?)");
	    $query .= implode(",",$qPart);
	    $stmtA = $conn->prepare($query);
	    $j = 1;
	    for($i = 0; $i<count($weeks); $i++){
	    	$stmtA->bindValue($j++, $group_info_num, PDO::PARAM_STR);
	    	$stmtA->bindValue($j++, $weeks[$i], PDO::PARAM_STR);
	    }
	    $stmtA->execute();

		$data['success']=true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
if(isset($_GET[md5(md5('submitNews'))])){
	try {
		include_once('../connection.php');

		$news_content = $_POST['news_content'];
		$id = $_POST['id'];
		$last_updated_date = date("Y-m-d ");
		$stmt = $conn->prepare("UPDATE news SET content = :content, last_updated_date = :last_updated_date WHERE type = :type");
		$stmt->bindParam(':type', $id, PDO::PARAM_STR);
		$stmt->bindParam(':content', $news_content, PDO::PARAM_STR);
		$stmt->bindParam(':last_updated_date', $last_updated_date, PDO::PARAM_STR);
		$stmt->execute();
		$data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('change-attendance-date'))])){
	try {
		include_once('../connection.php');
		$date = date("Y-m-d", strtotime($_POST['to_date']));
		$progress_group_num = $_POST['pgn'];
		$stmt = $conn->prepare("UPDATE progress_group SET created_date = :created_date WHERE progress_group_num = :progress_group_num");
		$stmt->bindParam(':created_date', $date, PDO::PARAM_STR);
		$stmt->bindParam(':progress_group_num', $progress_group_num, PDO::PARAM_STR);
		$stmt->execute();
		$data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('singelStudentNews'))])){
	try {
		include_once('../connection.php');
		$data['context'] = 'empty';
		$student_num = $_POST['data_num'];
		$content = $_POST['news_context'];
		$last_updated_date = date("Y-m-d ");
		$publish = 1;
		$readed = 0;
		if($content!=''){
			$stmt = $conn->prepare("INSERT INTO news (type, content, last_updated_date, readed, publish) 
										VALUES(:type, :content, :last_updated_date, :readed, :publish)
										ON DUPLICATE KEY UPDATE content = :content, readed = :readed");
			$stmt->bindParam(':type', $student_num, PDO::PARAM_STR);
			$stmt->bindParam(':content', $content, PDO::PARAM_STR);
			$stmt->bindParam(':last_updated_date', $last_updated_date, PDO::PARAM_STR);
			$stmt->bindParam(':publish', $publish, PDO::PARAM_INT);
			$stmt->bindParam(':readed', $readed, PDO::PARAM_INT);
			$stmt->execute();
			$data['context'] = 'notEmpty';
		}
		$data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('removeSingelStudentNews'))])){
	try {
		include_once('../connection.php');
		$student_num = $_GET['data_num'];
		$stmt = $conn->prepare("DELETE FROM news WHERE type = :student_num");
		$stmt->bindParam(':student_num', $student_num, PDO::PARAM_STR);
		$stmt->execute();
		$data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('acceptSuggestions'))])){
	try {
		include_once('../connection.php');
		$sid = $_POST['sid'];
		$stmt = $conn->prepare("UPDATE suggestion SET status = ? WHERE suggestion_id = ?");
		for ($i=0; $i < count($sid); $i++) {
			$stmt->execute(array('1', $sid[$i]));
		}
		$data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('implementSuggestions'))])){
	try {
		include_once('../connection.php');
		$sid = $_POST['sid'];
		$last_changed_date = date("Y-m-d H:i:s");
		$stmt = $conn->prepare("UPDATE suggestion SET status = ?, last_changed_date = ? WHERE suggestion_id = ?");
		for ($i=0; $i < count($sid); $i++) {
			$stmt->execute(array('2', $last_changed_date, $sid[$i]));
		}
		$data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('rejectSuggestions'))])){
	try {
		include_once('../connection.php');

		$suggestion_id = $_POST['sid'];

		$query = 'DELETE FROM suggestion WHERE suggestion_id = ';
		$qPart = array_fill(0, count($suggestion_id), "?");
	    $query .= implode(" OR suggestion_id = ",$qPart);
	    $stmt = $conn->prepare($query);
	    $j = 1;
	    for($i = 0; $i<count($suggestion_id); $i++){
	    	$stmt->bindValue($j++, $suggestion_id[$i], PDO::PARAM_STR);
	    }
	    $stmt->execute();

		$data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('newProblemSolvingFile'))])){
	try {
		// setlocale(LC_ALL, 'en_US.UTF8');
		$subtopic_num = $_POST['sbtn'];
		$document_link = $_FILES['pdf_file']['name'];
		$document_link = merge($document_link,"___".md5($subtopic_num));
		// $current = file_get_contents($document_link);
		// $current .= md5($subtopic_num);
		// file_put_contents($document_link, $current);
		// file_put_contents($document_link, md5($subtopic_num), FILE_APPEND | LOCK_EX);

		$document_dir = $_POST['file_dir'];
		$document_dir = $document_dir.basename($document_link);

		// $document_link = iconv("UTF-8", "cp1251", $document_link);
		$data['text'] = $document_link;
		if(checkPDFFile($document_dir, $document_link, $_FILES['pdf_file']['tmp_name'], $_FILES['pdf_file']['size'])){
			include_once('../connection.php');

			$stmt = $conn->prepare("INSERT INTO problem_solution (document_link, subtopic_num) VALUES(:document_link, :subtopic_num)");
			$stmt->bindParam(':document_link', $document_link, PDO::PARAM_STR);
			$stmt->bindParam(':subtopic_num', $subtopic_num, PDO::PARAM_STR);
			$stmt->execute();

			$data['success'] = true;
		}
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('removeProblemSolvingFile'))])){
	try {
		include_once('../connection.php');

		$problem_solution_id = $_POST['id'];
		$file_name = $_POST['file_name'];
		$file_dir = $_POST['file_dir'];

		if (unlink($file_dir.$file_name)){
			$stmt = $conn->prepare("DELETE FROM problem_solution WHERE problem_solution_id = :problem_solution_id");
			$stmt->bindParam(':problem_solution_id', $problem_solution_id, PDO::PARAM_STR);
			$stmt->execute();

			$data['success'] = true;
		}
		else {
			$data['success'] = false;
			$data['script'] .= "<script type='text/javascript'> alert('Ошибка! Файл не был удален. Попробуйте еще раз'); </script>";
		}
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
else if(isset($_GET[md5(md5('openAccess'))])){
	try {
		include_once('../connection.php');
		$student_num = $_POST['sn'];
		$block = $_POST['block'];
		$block_date = date("Y-m-d H:i:s");
		$stmt = $conn->prepare("UPDATE student SET block = :block, block_date = :block_date WHERE student_num = :student_num");
		$stmt->bindParam(':block', $block, PDO::PARAM_INT);
		$stmt->bindParam(':block_date', $block_date, PDO::PARAM_STR);
		$stmt->bindParam(':student_num', $student_num, PDO::PARAM_STR);
		$stmt->execute();
		$data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}








// ----------------------------------------------------------------------------------------------------functions--------------------------------------







function merge($file, $language){
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $filename = str_replace('.'.$ext, '', $file).$language.'.'.$ext;
    return ($filename);
}

function checkPDFFile($file_path, $file, $tmp_name, $filesize){
	// 10485760 byte = 10MB
	global $data;
	$file_test = false;
	$fileType = pathinfo($file_path, PATHINFO_EXTENSION);
	if($fileType == "PDF" || $fileType == 'pdf'){
		if($filesize>10485760){
			$data['script'] .= "<script type='text/javascript'> alert('Ошибка! Максимальный размер файла 10MБ ~ (10485760 байт)'); </script>";
		}
		else if(!file_exists($file_path)) {
        	// $data['test'] .= $tmp_name;
            if(move_uploaded_file($tmp_name, $file_path)){
                $file_test = true;
            }
            else{
            	$data['script'] .= "<script type='text/javascript'> alert('Ошибка! Файл не загружен. Попробуйте еще раз!'); </script>";
            }
        }
        else if(file_exists($file_path)){
        	$data['script'] .= "<script type='text/javascript'> alert('Файл с таким названием уже сушествует'); </script>";
        }
        else{
        	$file_test = true;
        }
	}
	else{
    	$data['script'] .= "<script type='text/javascript'> alert('Не правильный формат файла. Доступные форматы : \".pdf, .PDF\"'); </script>";
    }
    return $file_test;
}
function checkFile($photo_path, $file, $tmp_name, $filesize){
	global $data;
	$file_test = false;
	$img_corr = 'false';
    $imageFileType = pathinfo($photo_path,PATHINFO_EXTENSION);
	if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" || $imageFileType == "JPG" || $imageFileType == "PNG" || $imageFileType == "JPEG" || $imageFileType == "GIF"){
		if($filesize>307200){
			$data['script'] .= "<script type='text/javascript'> alert('Ошибка! Максимальный размер изображении 300КБ ~ (307200 байт)'); </script>";
		}
        else if(!file_exists($photo_path)) {
        	// $data['test'] .= $tmp_name;
            if(move_uploaded_file($tmp_name, $photo_path)){
                $file_test = true;
            }
            else{
            	$data['script'] .= "<script type='text/javascript'> alert('Ошибка! Картинка не загружено. Попробуйте еще раз!'); </script>";
            }
        }
        else{
        	$file_test = true;
        }
    }
    else{
    	$data['script'] .= "<script type='text/javascript'> alert('Не правильный формат картинки. Доступный форматы : \".jpg , .png , .jpeg , .gif\"'); </script>";
    }
    if($file_test){
    	return true;
    }
    else{ 
    	return false;
    }
    
}
function addToDB(){
	global $question_img;
	$photo_path = '../img/test/';
	$answer_txt = $_POST['answer'];
	$answer_img_file_size = array();
	$answer_torf = array();
	$answer_img = array();
	$answer_tmp_name = array();
	$answer_torf_array = $_POST['torf'];
	$number_of_answers = $_POST['number_of_answers'];
	$answer_error = false;
	global $data;
	$decrease = 0;
	$decrease_txt = 0;
	$decrease_img = 0;
	for($i=2; $i<$number_of_answers+2; $i++){
		if(isset($_POST['answer'][$i-2]) || isset($_FILES['answer_img']['name'][$i-2]) || isset($_POST['answer-img-hidden'][$i-2])){
			if(isset($_FILES['answer_img']['name'][$i-2]) && $_FILES['answer_img']['name'][$i-2]!=''){
				$answer_img[$decrease] = $_FILES['answer_img']['name'][$i-2];
				$answer_tmp_name[$decrease] = $_FILES['answer_img']['tmp_name'][$i-2];
				$answer_img_file_size[$decrease] = $_FILES['answer_img']['size'][$i-2];
			}
			else if (isset($_POST['answer-img-hidden'][$i-2]) && $_POST['answer-img-hidden'][$i-2]!=''){
				$answer_img[$decrease] = $_POST['answer-img-hidden'][$i-2];
				$answer_tmp_name[$decrease] = $_POST['answer-img-hidden'][$i-2];
			}
			else{
				$answer_img[$decrease] = '';
			}
		
			if($_POST['answer'][$i-2]=='' && $answer_img[$decrease]==''){
				$answer_error = true;
				$data['script'] .= "<script type='text/javascript'> window['emptyAnswer'](".$i."); </script>";
			}	
			else if($_POST['answer'][$i-2]!='' || $_FILES['answer_img']['name'][$i-2]!='') {
				$answer_txt[$decrease] = $_POST['answer'][$i-2];
				$data['script'] .= "<script type='text/javascript'> window['notEmptyAnswer'](".$i."); </script>";
			}
			$answer_torf[$decrease] = isset($answer_torf_array[$i-2]) ? $answer_torf_array[$i-2] : "0";
			$decrease++;
		}
	}
	$torf = true;
	for($i=0; $i<$decrease; $i++){
		$photo_path = '../img/test/';
		$photo_path = $photo_path.basename($answer_img[$i]);
		if($answer_img[$i]!='' && isset($answer_img_file_size[$i])){
			if(!checkFile($photo_path, $answer_img[$i], $answer_tmp_name[$i], $answer_img_file_size[$i])){
				$torf = false;
				$data['test'] = 'testing';
				echo json_encode($data);
				break;
			}
		}
	}
	if($answer_error){
		echo json_encode($data);
	}
	else if(!$torf){
		echo json_encode($data); 
	}
	try {
		if(!$answer_error){
			include("../connection.php");
			$question_num = '';
			if(isset($_POST['hidden_question_num'])){
				deleteAnswer($_POST['hidden_question_num']);
				$question_num = $_POST['hidden_question_num'];
				$text = isset($_POST['question-txt']) ? $_POST['question-txt'] : '';
				updateQuestion($_POST['hidden_question_num'],$text,$question_img);
			}
			else{
				$stmtT = $conn->prepare("SELECT test_num FROM test WHERE subtopic_num = :subtopic_num");
				$stmtT->bindParam(':subtopic_num', $subtopic_num, PDO::PARAM_STR);
				$subtopic_num = $_GET[md5('elementNum')];
				$stmtT->execute();
				$resultT = $stmtT->fetch(PDO::FETCH_ASSOC);

				$stmtQ = $conn->prepare("INSERT INTO question (question_num, test_num, question_text, question_img) VALUES(:question_num, :test_num, :question_text, :question_img)");
	   
			    $stmtQ->bindParam(':question_num', $question_num, PDO::PARAM_STR);
			    $stmtQ->bindParam(':test_num', $test_num, PDO::PARAM_STR);
			    $stmtQ->bindParam(':question_text', $question_txt, PDO::PARAM_STR);
			    $stmtQ->bindParam(':question_img', $question_img, PDO::PARAM_STR);
			    // preg_replace("/\n/", "<br />", $str)
			    // nl2br()


			    $question_num = str_replace('.','',uniqid('Q', true));
			    $test_num = $resultT['test_num'];
			    // $question_txt = str_replace("\n", "<br>", $_POST['question-txt']);
			    // $question_txt =preg_replace("/\n/", '<br />', $_POST['question-txt']);
			    // $question_txt = nl2br($_POST['question-txt']);
			    $question_txt = $_POST['question-txt'];

			    $stmtQ->execute();
			}

		    $query = "INSERT INTO answer (answer_num, question_num, answer_text, answer_img, torf) VALUES";
		    $qPart = array_fill(0, $decrease, "(?, ?, ?, ?, ?)");
		    $query .= implode(",",$qPart);
		    $stmtA = $conn->prepare($query);
		    $j = 1;
		    for($i = 0; $i<$decrease; $i++){
		    	$answer_num = str_replace('.','',uniqid('A', true));
		    	$stmtA->bindValue($j++, $answer_num, PDO::PARAM_STR);
		    	$stmtA->bindValue($j++, $question_num, PDO::PARAM_STR);
		    	$stmtA->bindValue($j++, $answer_txt[$i], PDO::PARAM_STR);
		    	$stmtA->bindValue($j++, $answer_img[$i], PDO::PARAM_STR);
		    	$stmtA->bindValue($j++, $answer_torf[$i], PDO::PARAM_STR);
		    }
		    $stmtA->execute();
		    // $obj = requireToVar('ajax_adminTest.php');
			$data['text'] = '';
			$data['success']=true;
			echo json_encode($data);
		}
	} catch(PDOException $e) {
		$data['error'] .= "Error: " . $e->getMessage();
        echo json_encode($data); 
    }
}
function deleteQuestion($question_num){
	try {
		include("../connection.php");
		$stmt = $conn->prepare("DELETE FROM question WHERE question_num = :question_num");

		$stmt->bindParam(':question_num',$question_num,PDO::PARAM_STR);

		$stmt->execute();
	} catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function deleteAnswer($question_num){
	try {
		include("../connection.php");
		$stmt = $conn->prepare("DELETE FROM answer WHERE question_num = :question_num");

		$stmt->bindParam(':question_num',$question_num,PDO::PARAM_STR);

		$stmt->execute();
	} catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function requireToVar($file){
    ob_start();
    require($file);
    return ob_get_clean();
}
function updateQuestion($question_num,$question_txt,$question_img){
	try {
		include('../connection.php');
		$stmt = $conn->prepare("UPDATE question SET question_text = :question_text, question_img = :question_img WHERE question_num = :question_num");
   
	    $stmt->bindParam(':question_num', $question_num, PDO::PARAM_STR);
	    $stmt->bindParam(':question_img', $question_img, PDO::PARAM_INT);
	    $stmt->bindParam(':question_text', $question_txt, PDO::PARAM_INT);
	       
	    $stmt->execute();
	} catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>