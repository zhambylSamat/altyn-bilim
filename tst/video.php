<?php include_once('../connection.php');?>
<?php
	if(!isset($_SESSION['tst_number'])){
		header('location:signin.php');
	}
	if(!isset($_GET['data_num'])){
		header('location:index.php');
	}

	try {
		$stmt = $conn->prepare("SELECT s.subject_name subject_name, 
									t.topic_num topic_num, 
									t.topic_name topic_name, 
									st.subtopic_num subtopic_num, 
									st.subtopic_name subtopic_name 
									FROM subject s
									LEFT JOIN topic t 
										ON t.subject_num = s.subject_num
											AND t.quiz = 'n'
									LEFT JOIN subtopic st 
										ON st.topic_num = t.topic_num
									WHERE s.subject_num = :subject_num
									ORDER BY t.topic_order, 
										st.subtopic_order ASC");
		$stmt->bindParam(':subject_num', $_GET['data_num'], PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();
	} catch (PDOException $e) {
		echo "Error: ".$e->getMessage()." !!!";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include_once('../meta.php');?>
	<title>Admins-Test-Altyn Bilim</title>
	<?php include_once('style.php');?>
	<link rel="stylesheet/less" type='text/css' href="css/style.less">
	<style type="text/css">
		button[title]:hover:after, a[title]:hover:after {
		  	content: attr(title);
		  	padding: 4px 8px;
		  	color: #fff;
		  	position: absolute;
		  	left: 100%;
		  	top: 10%;
		  	z-index: 20;
		  	white-space: nowrap;
		    -moz-border-radius: 5px;
		    -webkit-border-radius: 5px;
		  	border-radius: 5px;
		    -moz-box-shadow: 0px 0px 4px #222;
		    -webkit-box-shadow: 0px 0px 4px #222;
		  	background-color: black;
		}
	</style>
</head>
<body onload="startAjax('<?php echo $_GET['data_num'];?>','tstMain.php')">
<?php include_once('nav.php');?>
	<section>
		<div class="container">
			<div class='row'>
				<div class='col-md-12 col-sm-12'>
					<h3 class='text-primary' id='header-nav'><?php echo $result[0][0]." / ";?></h3>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-3 col-sm-3'>
					<div class='btn-group-vertical' style='width:100%;'>
						<!-- <button type='button' class='btn btn-primary' onclick='show("#primary",".secondary")'>Басты бет</button> -->
						<button type='button' class='btn btn-primary section' data_name='main_section' data_num='<?php echo $_GET['data_num'];?>'><?php echo $result[0][0];?></button>
						<?php 
							$topic_num = '';
							$count = 0;
							foreach($result as $value){
								if($value['subtopic_num']!=null){
						?>
							<?php 
								if($topic_num != $value['topic_num']){ 
									if($count!=0){
										echo "</ul></div>";
									}
							?>
							<div class='btn-group' role='group' target_name='<?php echo $value['topic_name'];?>'>
							<button id='btn-dropdown-1' type='button' class='btn btn-primary dropdown-toggle tooltp' data-toggle='dropdown' title='<?php echo $value['topic_name'];?>' aria-haspopup='true' aria-expanded='true'>
								<?php echo substr($value['topic_name'],0,35).((strlen($value['topic_name'])>35) ? "..." : "");?>
								<span class='caret'></span>
							</button>
							<ul class='dropdown-menu' aria-labelledby='btn-dropdown-1' style="width:100%;">
							<?php } $topic_num = $value['topic_num']; ?>
								<li>
									<a title='<?php echo $value['subtopic_name'];?>' target_name='<?php echo $value['subtopic_name'];?>' data_name='subtopic' data_num='<?php echo $value['subtopic_num'];?>' class='section' style='cursor:pointer;'><span class='glyphicon glyphicon-triangle-right'></span>&nbsp;<?php echo substr($value['subtopic_name'],0,35).((strlen($value['subtopic_name'])>35) ? "..." : "");?></a>
								</li>
						<?php $count++; }} ?>
						</div>
					</div>
				</div>

				<div class="col-md-9 col-sm-9" id='main-content'>
				</div>
			</div>
		</div>
	</section>

	<div id='lll' style='width: 100%; height: 100%; position: fixed; top:0; background-color: rgba(0,0,0,0); z-index: 100;'>
		<center>
			<img src="../img/loader.gif" style='width: 10%; margin-top:25%;'>
		</center>
	</div>
	<?php include_once('js.php');?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#lll").css('display','none');
		});
	</script>

	<script type="text/javascript">
		thisParent = '';
		// -------------------------------------------------AJAX-----------------------------------------------
		function startAjax(data_num,page){
			console.log(data_num);
			$(function(){
				$.ajax({url:page+'?<?php echo md5('elementNum')?>='+data_num,
					beforeSend:function(){
						$('#lll').css('display','block');
					},
					success: function(result){
					$('#lll').css('display','none');
					$("#main-content").html(result);
				}});
			});
		}

		$(document).on('submit','.comment-form',(function(e) {
				thisParent = $(this);
				e.preventDefault();
				$.ajax({
		        	url: "ajaxDb.php?<?php echo md5(md5('video_comment')); ?>",
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
				    		$('#comment-form').stop();
							$('#comment-form').show();
						 	$('#comment-form').css({'background-color':"#5CB85C","color":"#000"}).animate({backgroundColor: 'rgba(255, 255, 255, 0)',"color":"rgba(0,0,0,0)"},2000);
				    	}
				    	else{
				    	}
				    },
				  	error: function(dataS) 
			    	{
			    		console.log(dataS);
			    	} 	        
			   	});
			}));
		$(document).on('click',".section",function(){
			$data_name = $(this).attr('data_name');
			$data_num = $(this).attr('data_num');
			if($data_name == 'main_section'){
				$.ajax({url:'tstMain.php?<?php echo md5('elementNum')?>='+$data_num,success: function(result){
					$("#main-content").html(result);
				}});
			}
			else if($data_name == 'subtopic'){
				$target_name = "<span id='header-nav-tmp'>"+$(this).parent().parent().parent().attr('target_name')+" / "+$(this).attr('target_name')+" / </span>";
				$("#header-nav-tmp").remove();
				$("#header-nav").append($target_name);
				$element = '';
				$element += "<div class='row'>";
				$element += "<div class='col-md-12 col-sm-12 section-block'>";
				$element += "<h4>";
				$element += "<center><a class='section' data_name='video' data_num='"+$data_num+"'>Видео урок</a></center>";
				$element += "</h4>";
				$element += "</div>";
				$element += "</div>";
				$element += "";
				$("#main-content").html($element);
			}
			else if($data_name == 'video'){
				$.ajax({url:'tstVideo.php?<?php echo md5('elementNum')?>='+$data_num,
				beforeSend:function(){
					$('#lll').css('display','block');
				},
				success:function(result){
					$('#lll').css('display','none');
					$('#main-content').html(result);
				}});
			}
		});
	</script>
	
</body>
</html>