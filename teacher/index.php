<?php 
	include('../connection.php');
	if(!isset($_SESSION['teacher_num'])){
		header('location:signin.php');
	}
	$result_groups = array();
	try {
		$stmt = $conn->prepare("SELECT gi.group_info_num group_info_num, gi.group_name group_name, gi.comment comment, s.subject_name subject_name, (SELECT count(*) FROM group_student gs, student s WHERE gs.group_info_num = gi.group_info_num AND s.student_num = gs.student_num AND s.block != 1 ) student_quantity FROM group_info gi, teacher t, subject s WHERE gi.teacher_num=t.teacher_num AND t.teacher_num = :teacher_num AND gi.subject_num = s.subject_num order by gi.last_update asc");
		$stmt->bindParam(':teacher_num', $_SESSION['teacher_num'], PDO::PARAM_STR);
	    $stmt->execute();
	    $result_groups = $stmt->fetchAll();

	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
	// print_r($result_groups);
?>
<!DOCTYPE html>
<html>
<head>
	<?php include_once('../meta.php');?>
	<title>Мұғалім | Altyn Bilim</title>
	<?php include_once('style.php');?>
</head>
<body>
<?php include_once('nav.php');?>

<section id='groups'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<div class="btn-group" role="group" style='margin-bottom: 20px;'>
					<button class='btn btn-primary suggestion' type='button' data-toggle='modal' data-target='.box-suggestion'>Ұсыныс</button>
					<?php if(isset($_SESSION['news_notificaiton_teacher']) ){?>
					<button class='btn btn-primary news' type='button' data-toggle='modal' data-target='.box-news'>Жаңалықтар</button>
					<?php }?>
				</div>
			</div>
			<div class='col-md-12 col-sm-12 col-xs-12' style='overflow-x: scroll;'>
				<table class='table table-striped table-bordered'>
					<tr>
						<th colspan='4'><center>Мұғалім: <?php echo $_SESSION['teacher_name']." ".$_SESSION['teacher_surname'];?></center></th>
					</tr>
					<tr>
						<th style='width: 25%;'><center>Группа</center></th>
						<th style='width: 25%;'><center>Пән</center></th>
						<th style='width: 25%;'><center>Оқушылар саны</center></th>
						<th style='width: 25%;'><center>Түсініктеме</center></th>
					</tr>
					<?php 
						foreach ($result_groups as $value) {
					?>
					<tr>
						<td><center><a href="group.php?data_num=<?php echo $value['group_info_num'];?>"><?php echo $value['group_name'];?></a></center></td>
						<td><center><a href="group.php?data_num=<?php echo $value['group_info_num'];?>"><?php echo $value['subject_name'] ; ?></a></center></td>
						<td><center><a href="group.php?data_num=<?php echo $value['group_info_num'];?>"><?php echo $value['student_quantity'];?></a></center></td>
						<td><center><a href="group.php?data_num=<?php echo $value['group_info_num'];?>"><?php echo $value['comment'];?></a></center></td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
		<hr>
		<div class='teacher-schedule' style='overflow-x: scroll;'>
			<?php
				$teacher_num = $_SESSION['teacher_num'];
				include_once('schedule.php'); 
			?>
		</div>
	</div>
</section>
<!-- <a class='btn btn-sm btn-info news' data-toggle='modal' data-target='.box-news' data-type='student'>Жаңалықтар</a> -->
<div class="modal fade box-news" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    	<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
    		<br>
    		<center><h3 class="modal-title">Жаңалықтар</h3></center>
    	</div>
    	<div class="modal-body">
			<div class='row news-label'>
			<?php if(isset($_SESSION['news_res_teacher']['header']) && $_SESSION['news_res_teacher']['header']!=''){?>
			<div class="col-md-12 col-sm-12 col-xs-12 header">
				<center>
					<div class='news-header' style='background-color: #AFDFF7; padding:1% 0 1% 0;'>
						<h3><b><?php echo $_SESSION['news_res_teacher']['header'];?></b></h3>
					</div>
				</center>
			</div>
			<?php }?>
			<?php if(isset($_SESSION['news_res_teacher']['content']) && $_SESSION['news_res_teacher']['content']!=''){?>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<div class='news-content'>
					<pre class='pre-news'><?php echo nl2br($_SESSION['news_res_teacher']['content']);?></pre>
				</div>
			</div>
			<?php }?>
			<?php if(isset($_SESSION['news_res_teacher']['img']) && $_SESSION['news_res_teacher']['img']!=''){?>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<center>
					<img src="../news_img/<?php echo $_SESSION['news_res_teacher']['img'];?>" alt="teacher-image" class="img-thumbnail img-responsive">
				</center>
			</div>
			<?php } ?>
		</div>
    	</div> 
    </div>
  </div>
</div>

<div class="modal fade box-suggestion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    	<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
    		<br>
    		<center><h3 class="modal-title">Ұсыныс</h3></center>
    	</div>
    	<div class="modal-body">
    	</div> 
    </div>
  </div>
</div>

<div id='lll'>
	<center>
		<img src="../img/loader.gif" style='width: 10%; margin-top:25%;'>
	</center>
</div>
<?php 
// include_once('js.php');
?>
<?php
	if(isset($_SESSION['news_notificaiton_teacher']) && $_SESSION['news_notificaiton_teacher']=='true'){
		$_SESSION['news_notificaiton_teacher'] = 'false';
		echo '<script type="text/javascript">$(document).ready(function(){$(".box-news").modal("show");});</script>';
	}
?>
<script type="text/javascript">

	$(document).ready(function(){
		$("#lll").css('display','none');
	});
	$(function(){
		$('#lll').hide().ajaxStart( function() {
			$(this).css('display','block');  // show Loading Div
		} ).ajaxStop ( function(){
			$(this).css('display','none'); // hide loading div
		});
	});
	
	$(document).on('click','.suggestion',function(){
		$(".box-suggestion .modal-body").html("<center><h3>Loading...</h3></center>");
		$(".box-suggestion .modal-body").load("load_suggestion.php");
	});

	$(document).on('click','#suggestion, #suggestion-cancel',function(){
		$('#suggestion').toggle();
		$("#suggestion-form").toggle();
	});

	$(document).on('submit','#suggestion-form',function(e){
		$this = $(this);
		e.preventDefault();
		$.ajax({
	    	url: "ajaxDb.php?<?php echo md5(md5('add-new-suggestion'))?>",
	    	type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			beforeSend:function(){
				$('#lll').css('display','block');
			},
			success: function(dataS){
				$('#lll').css('display','none');
		    	// console.log(dataS);
		    	data = $.parseJSON(dataS);
		    	// console.log(data);
		    	if(data.success){
		    		$this.stop().css({'background-color':"#5CB85C"}).animate({backgroundColor: 'rgba(255, 255, 255, 0)'},500,function(){
			    		$(".box-suggestion").modal('hide');		
		    		});
		    	} 
		    	else{
		    		console.log(data);
		    	}
		    },
		  	error: function(dataS) 
	    	{
	    		console.log(dataS);
	    	} 	        
	   	});
	});

	$(document).on('click','.suggestion-edit, .suggestion-edit-cancel',function(){
		$(this).parents('tr').find('.suggestion-text').toggle();
		$(this).parents('tr').find('.suggestion-form-edit').toggle();
	});

	$(document).on('submit','.suggestion-form-edit',function(e){
		$this = $(this);
		e.preventDefault(e);
		$.ajax({
	    	url: "ajaxDb.php?<?php echo md5(md5('edit-suggestion'))?>",
	    	type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			beforeSend:function(){
				$('#lll').css('display','block');
			},
			success: function(dataS){
				$('#lll').css('display','none');
		    	// console.log(dataS);
		    	data = $.parseJSON(dataS);
		    	// console.log(data);
		    	if(data.success){
		    		$this.parents('tr').stop().css({'background-color':"#5CB85C"}).animate({backgroundColor: 'rgba(255, 255, 255, 0)'},500,function(){
		    			$(".box-suggestion").modal('hide');	
		    		});
		    	} 
		    	else{
		    		console.log(data);
		    	}
		    },
		  	error: function(dataS) 
	    	{
	    		console.log(dataS);
	    	} 	        
	   	});
	});
	$(document).on('click','.suggestion-delete',function(){
		$this = $(this);
		$sid = $this.parents('tr').find('input[name=sid]').val();
		$.ajax({
	    	url: "ajaxDb.php?<?php echo md5(md5('remove-suggestion'))?>&sid="+$sid,
			cache : false,
			beforeSend:function(){
				$('#lll').css('display','block');
			},
			success: function(dataS){
				$('#lll').css('display','none');
		    	// console.log(dataS);
		    	data = $.parseJSON(dataS);
		    	// console.log(data);
		    	if(data.success){
		    		$this.parents('tr').stop().css({'background-color':"#5CB85C"}).animate({backgroundColor: 'rgba(255, 255, 255, 0)'},500,function(){
		    			$(this).remove();
		    		});
		    	} 
		    	else{
		    		console.log(data);
		    	}
		    },
		  	error: function(dataS) 
	    	{
	    		console.log(dataS);
	    	} 	        
	   	});
	});
	$(document).on('click','#implementedSuggestion',function(){
		$('#implementedSuggestionBox').fadeToggle();
	});
</script>
</body>
</html>