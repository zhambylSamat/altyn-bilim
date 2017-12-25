<?php
	include('../connection.php');
	if(isset($_GET[md5('elementNum')]) || isset($_SESSION['elementNum'])){
		$elementNum = isset($_GET[md5('elementNum')]) ? $_GET[md5('elementNum')] : $_SESSION['elementNum'];
		$_SESSION['elementNum'] = $elementNum;
	}
	else{
		header('location:index.php');
	}
?>
<div>
	<?php
		$result_count = '';
		try {
			$stmt = $conn->prepare("SELECT * FROM video WHERE subtopic_num = :subtopic_num ORDER BY updated_date ASC");
		    $stmt->bindParam(':subtopic_num', $elementNum, PDO::PARAM_STR);

		    $stmt->execute();
		    $result_question = $stmt->fetchAll(); 
		    $result_count = $stmt->rowCount();
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	?>
	<div class='row'>
		<div class='col-md-12 col-sm-12'>
	<?php

		if($result_count){
			foreach($result_question as $readrow){

	?>
			<center>
				<video id="my-video" class="video-js" controls controlsList="nodownload" preload="auto" width="700" height="300" style='width:none; height: none;' data-setup="{}">
				    <source src="../video/video_lesson/<?php echo $readrow['video_link']?>" type='video/mp4'>
				    Техникалық жағдайға байланысты видео қосылмайды.
				  </video>
            </center>
            <hr>
	<?php }} else {?>
	<h4><center>Видео сабақ(тар) жүктелмеген</center></h4>
	<?php } ?>
			</div>
			<?php
				try {
					$stmt = $conn->prepare("SELECT comment FROM video_comment WHERE subtopic_num = :subtopic_num");
				    $stmt->bindParam(':subtopic_num', $elementNum, PDO::PARAM_STR);
				    $stmt->execute();
				    $comment = $stmt->fetch(PDO::FETCH_ASSOC);
				} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
			?>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<form class='comment-form'>
					<center><h3 id='comment-form' style='color:rgba(0,0,0,0); padding-top:1%; padding-bottom:1%; width: 100%;'>Комментарий сақталды</h3></center>
					<input type="hidden" name="data_num" value='<?php echo $elementNum;?>'>
					<label for='comment'>Коммент</label>
					<input type="submit" name="submit_comment" class='btn btn-sm btn-success' value='Сақтау'>
					<textarea class='form-control' cols='50' rows='20' placeholder="Comment..." id='comment' name='video_comment'><?php echo $comment['comment'];?></textarea>
				</form>
			</div>
	</div>
	<script type="text/javascript">
$(document).ready(function(){
	$('script[src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"]').remove();
    $('<script>').attr('src', 'http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js').appendTo('body');
    $('script[src="http://vjs.zencdn.net/6.1.0/video.js"]').remove();
    $('<script>').attr('src', 'http://vjs.zencdn.net/6.1.0/video.js').appendTo('body');
});
</script>
</div>
