<?php
$data = array();
$data['success'] = false;
$data['error'] = '';

include_once('../connection.php');
if(isset($_GET[md5(md5('video_comment'))])){
	try {
		$subtopic_num = $_POST['data_num'];
		$video_comment = $_POST['video_comment'];
		$stmt = $conn->prepare("DELETE FROM video_comment WHERE subtopic_num = :subtopic_num");
		$stmt->bindParam(':subtopic_num', $subtopic_num, PDO::PARAM_STR);
		$stmt->execute();

		$video_comment_num = uniqid("VC", true);
		$stmt = $conn->prepare("INSERT INTO video_comment (video_comment_num, subtopic_num, comment) VALUES(:video_comment_num, :subtopic_num, :comment)");
		$stmt->bindParam(':video_comment_num', $video_comment_num, PDO::PARAM_STR);
		$stmt->bindParam(':subtopic_num', $subtopic_num, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $video_comment, PDO::PARAM_STR);
		$stmt->execute();
		$data['success'] = true;
	} catch (PDOException $e) {
		$data['success'] = false;
		$data['error'] .= "Error : ".$e->getMessage()." !!!";
	}
	echo json_encode($data);
}
?>