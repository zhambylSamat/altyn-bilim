
<?php
	include_once('../connection.php');
	if(!isset($_SESSION['student_num']) || (isset($_SESSION['access']) && $_SESSION['access']==md5('false'))){
		header('location:signin.php');
	}
?>
<?php
	$permission_count = 0;
	try {
		$stmt_permission = $conn->prepare("SELECT stp.video_permission videoPermission, stp.test_permission testPermission, stp.subtopic_num subtopicNum FROM student_permission sp, student_test_permission stp, subtopic s WHERE sp.student_num = :student_num AND stp.student_permission_num = sp.student_permission_num AND s.subtopic_num=stp.subtopic_num order by s.created_date asc");

		$stmt_permission->bindParam(':student_num', $_SESSION['student_num'], PDO::PARAM_STR);
     	
	    $stmt_permission->execute();
	    $permission_count = $stmt_permission->rowCount();
	    $result_permission = $stmt_permission->fetchAll(); 
	    $list_arr = array();
	    if($permission_count!=0){
	    	foreach($result_permission as $readrow_permission){
	    		if($readrow_permission['videoPermission']=='t'){
	    			$stmt = $conn->prepare("SELECT s.subject_num sNum, s.subject_name sName, t.topic_num tNum, t.topic_name tName, st.subtopic_num stNum, st.subtopic_name stName FROM subject s, topic t, subtopic st WHERE s.subject_num = t.subject_num AND t.topic_num = st.topic_num AND st.subtopic_num = :subtopic_num");
	    			$stmt->bindParam(':subtopic_num', $readrow_permission['subtopicNum'], PDO::PARAM_STR);
	    			$stmt->execute();
	    			$result_list = $stmt->fetchAll();
	    			foreach ($result_list as $value) {
	    				$list_arr[$value['sNum']]['name'] = $value['sName'];
	    				$list_arr[$value['sNum']]['topic'][$value['tNum']]['name'] = $value['tName'];
	    				$list_arr[$value['sNum']]['topic'][$value['tNum']]['subtopic'][$value['stNum']]['name'] = $value['stName'];
	    			}
				}
			}
		}
	} catch (PDOException $e) {
		echo "Error ".$e->getMessge()." !!!";
	}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<?php include_once('../meta.php');?>
	<title>Altyn Bilim</title>
	<?php include_once('style.php');?>
	<link rel="stylesheet" type="text/less" href="css/style.less">
	<style type="text/css">
		.secondary{
			display:none;
		}
		.video, .test{
			display:none;
		}
		.question{
			display:none;
		}
		.question:first-child{
			display:block;
		}
		video::-internal-media-controls-download-button {
		    display:none;
		}
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
		.schedule th, .schedule td{
			border:1px solid black !important;
		}
	</style>
	<script>
 function fallback(video) {
   // replace <video> with its contents
   while (video.hasChildNodes()) {
     if (video.firstChild instanceof HTMLSourceElement)
       video.removeChild(video.firstChild);
     else
       video.parentNode.insertBefore(video.firstChild, video);
   }
   video.parentNode.removeChild(video);
 }
</script>

</head>
<body>
<?php include_once('nav.php');?>
	<section class='box'>
		<div class='container'>
			<div class='row'>
				<div class='col-md-3 col-sm-3'>
					<div class='btn-group-vertical' style='width:100%;'>
						<button type='button' class='btn btn-info section_content' onclick='mainContent()'>Главная</button>
						<?php 
						foreach ($list_arr as $subject_key => $subject_value) {
						?>
						<button title="<?php echo $subject_value['name']; ?>" class='btn btn-default arena_section section_content' data_name='subject' data_num = "<?php echo $subject_key;?>">
						<?php echo substr($subject_value['name'],0,35).((strlen($subject_value['name']>35) ? "..." : ''));?>							
						</button>
						<?php
							foreach($subject_value['topic'] as $topic_key => $topic_val){ 
						?>
						<div class='btn-group' role='group'>
							<button title='<?php echo $topic_val['name']; ?>' id='btn-dropdown-1' type='button' class='btn btn-primary dropdown-toggle section_content' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
								<?php 
									echo substr($topic_val['name'],0,35).((strlen($topic_val['name'])>35) ? "..." : "");
								?>
								<span class='caret'></span>
							</button>
							<ul class='dropdown-menu' aria-labelledby='btn-dropdown-1' style="width:100%;">
							<?php 
								foreach($topic_val['subtopic'] as $subtopic_key => $subtopic_val){ 
							?>
								<li>
									<a title='<?php echo $subtopic_val['name']; ?>' data_name='subtopic' data_num = "<?php echo $subtopic_key?>" class='arena_section'>
									<?php echo substr($subtopic_val['name'],0,35).((strlen($subtopic_val['name'])>35) ? "..." : "");?>
									</a>
								</li>
							<?php }?>
							</ul>
						</div>
						<?php }}?>
					</div>
				</div>

				<div class='col-md-9 col-sm-9'>
					<div id='arena_section'>
						
					</div>
				</div>
			</div>
		</div>
	</section>
<!--  -----------------------------------------------------------MODAL-START-------------------------------------------------------------------------------- -->
<div class="modal fade box-schedule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    	<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
    		<br>
    		<center><h3 class="modal-title">Сабақ кестесі</h3></center>
    	</div>
    	<div class="modal-body">
    	</div> 
    </div>
  </div>
</div>

<div class="modal fade box-news" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    	<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
    		<br>
    		<center><h3 class="modal-title">Жалпы жаңалықтар</h3></center>
    	</div>
    	<div class="modal-body">
			<div class='row news-label'>
			<?php if(isset($_SESSION['news_res_student']['header']) && $_SESSION['news_res_student']['header']!=''){?>
			<div class="col-md-12 col-sm-12 col-xs-12 header">
				<center>
					<div class='news-header' style='background-color: #AFDFF7; padding:1% 0 1% 0;'>
						<h3><b><?php echo $_SESSION['news_res_student']['header'];?></b></h3>
					</div>
				</center>
			</div>
			<?php }?>
			<?php if(isset($_SESSION['news_res_student']['content']) && $_SESSION['news_res_student']['content']!=''){?>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<div class='news-content'>
					<pre class='pre-news'><?php echo nl2br($_SESSION['news_res_student']['content']);?></pre>
				</div>
			</div>
			<?php }?>
			<?php if(isset($_SESSION['news_res_student']['img']) && $_SESSION['news_res_student']['img']!=''){?>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<center>
					<img src="../news_img/<?php echo $_SESSION['news_res_student']['img'];?>" alt="teacher-image" class="img-thumbnail img-responsive">
				</center>
			</div>
			<?php } ?>
		</div>
    	</div> 
    </div>
  </div>
</div>

<div class="modal fade box-self-news" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    	<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
    		<br>
    		<center><h3 class="modal-title">Жеке хабарлама!!!</h3></center>
    	</div>
    	<div class="modal-body">
			<div class='row news-label'>
			<?php if(isset($_SESSION['news_res_self_student']['content']) && $_SESSION['news_res_self_student']['content']!=''){?>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<div class='news-content'>
					<pre class='pre-news'><?php echo nl2br($_SESSION['news_res_self_student']['content']);?></pre>
				</div>
			</div>
			<form method='post' action='controll-user.php'>
				<center><input type="submit" name="confirm_single_student_news" class='btn btn-sm btn-success' value='Оқыдым'></center>
			</form>
			<?php }?>
		</div>
    	</div> 
    </div>
  </div>
</div>
<!--  -----------------------------------------------------------MODAL-END-------------------------------------------------------------------------------- -->

<div id='lll' style='width: 100%; height: 100%; position: fixed; top:0; background-color: rgba(0,0,0,0); z-index: 100;'>
	<center>
		<img src="../img/loader.gif" style='width: 10%; margin-top:25%;'>
	</center>
</div>
<?php 
// include_once('js.php');
?>
<?php
	if(isset($_SESSION['news_notificaiton_self_student']) && $_SESSION['news_notificaiton_self_student']=='true'){
		$_SESSION['news_notificaiton_self_student'] = 'false';
		if(isset($_SESSION['news_notificaiton_student'])) $_SESSION['news_notificaiton_student'] = 'false';
		echo '<script type="text/javascript">$single_news = true; $(document).ready(function(){$(".box-self-news").modal("show");});</script>';
	}
	else if(isset($_SESSION['news_notificaiton_student']) && $_SESSION['news_notificaiton_student']=='true'){
		$_SESSION['news_notificaiton_student'] = 'false';
		echo '<script type="text/javascript">$single_news = false; $(document).ready(function(){$(".box-news").modal("show");});</script>';
	}
?>
<script type="text/javascript">
	$('.modal, .close').click(function(){
		if($('.box-news').css('display')=='none' && $single_news && "<?php echo (isset($_SESSION['news_notificaiton_self_student']) && isset($_SESSION['news_notificaiton_student'])) ? "true" : 'false'; ?>"=='true'){
			$(".box-self-news").modal("hide");
			$(".box-news").modal("show");
			$single_news = false;
		}
		// console.log($('.box-news').style.display);
	});


	$t_name = '';
	$(function(){
		$('#lll').hide().ajaxStart( function() {
			$(this).css('display','block');  // show Loading Div
		} ).ajaxStop ( function(){
			$(this).css('display','none'); // hide loading div
		});
	});
	mainContent();
	function mainContent(){
		$(function(){
			$('#arena_section').load('total_info.php');
		});
	}
	
	$(document).on("click",".arena_section",function(){
		$data_name = $(this).attr('data_name');
		$data_num = $(this).attr('data_num');
		// console.log($data_name);
		if($data_name == 'subject'){
			// console.log($data_num);
			$('#arena_section').load('total_info.php?<?php echo md5('data_num')?>='+$data_num+' #'+$data_num);
		}
		else if($data_name == 'subtopic'){
			// console.log($data_num);
			$st_num = $data_num;
			$('#arena_section').load('lesson.php?<?php echo md5(md5('dataNum'));?>='+$data_num);
		}
		else if($data_name=='startTest'){
			var alert = confirm("-Тестті бастағаннан кейін оны бітірмей шыға алмайсыз!\n--Тесттен өту үшін сіздің үпайыңыз 80%-дан асуы тиіс. \n---Кейбір сұрақтардың бірнеше жауаптары болуы мүмкін*");
			if(alert){
				$('.section_content').attr('disabled','');
				$('#arena_section').load('test.php?<?php echo md5(md5('dataNum'));?>='+$data_num);
			}
		}
	});
</script>


<script type="text/javascript">
	function uplFile(){
		$(function(){
			$("#idd").load('admin/abc.php');
		});
	}
</script>
<script type="text/javascript">
	$count_question = 0;
	$(function(){
		$('.btn-answer').on("click",function(){
			if($(this).hasClass('btn-info')){
				$(this).removeClass('btn-info').addClass('btn-primary');
			}
		});
	});
	$(function(){
		$(".answer_checkbox").on("change",function(){
			$attr = $(this).attr('data');
			if($(this).prop('checked')==true){
				if(arr[$attr]==undefined){
					arr[$attr] = 0;
				}
				arr[$attr]++;
			}
			else{
				arr[$attr]--;
			}
			if($('.btn-answer').eq($attr).hasClass("btn-primary") && arr[$attr]!=0){
				$('.btn-answer').eq($attr).removeClass("btn-primary").addClass("btn-success");
			}
			else if(arr[$attr]==0){
				$('.btn-answer').eq($attr).removeClass("btn-success").addClass("btn-primary");
			}
		});
	});
	// function startTest(objShow, objHide){
	// 	var alert = confirm("-Тестті бастағаннан кейін оны бітірмей шыға алмайсыз!\n--Тесттен өту үшін сіздің үпайыңыз 80%-дан асуы тиіс. \n---Кейбір сұрақтардың бірнеше жауаптары болуы мүмкін*");
	// 	if(alert == true){
	// 		$(function(){
	// 			console.log('should be disabled');
	// 			$('.section_content').attr('disabled','');
	// 			$objDisable='.btn-group-vertical button';
	// 			$(objHide).css("display","none");
	// 			$($objDisable).addClass('disabled');
	// 			$(objShow).css("display","block");
	// 		});
	// 	}
	// }
	$(document).on('click','.btn-question',function(){
		$this = $(this);
		$this.removeClass('btn-info').addClass('btn-primary');
		$this.siblings().removeClass('text-underline');
		$this.addClass('text-underline');
		$data_number = $this.attr('data-number');
		$('.test-box').children().css('display','none');
		$('.test-box').children(":nth-child("+$data_number+")").css('display','block');
	});
	$(document).on('click','.img-big',function(){
		$attr = $(this).find('img').attr('src');
		console.log($attr);
		$('.img-section').find('img').attr('src',$attr);
		$('.img-section').css('display','block');
	});
	$(document).on('click','.remove-img-section',function(){
		$(this).siblings().attr('src','');
		$(this).parents('.img-section').css('display','none');
	});
	$(document).on('click','.img-section',function(){
		$(this).find('img').attr('src','');
		$(this).css('display','none');
	});
	$(document).on('change','.answer-box',function(){
		$data_number = $(this).attr('data_number')-1;
		$aNum = $(this).attr("data_num");
		$qNum = $(this).parents('.box-test').attr('data_num');
		if($(this).prop('checked')==true){
			if(!Array.isArray($dic[$qNum])) $dic[$qNum] = [];
			$dic[$qNum].push($aNum); 
			if($arr[$data_number]==undefined){
				$arr[$data_number] = 0;
			}
			$arr[$data_number]++;
		}
		else{
			var index = ($dic[$qNum]).indexOf($aNum);
			($dic[$qNum]).splice(index,1);
			$arr[$data_number]--;
		}
		$obj = JSON.stringify($dic);
		if($arr[$data_number]!=0){
			$('.footer_navigation').children(":nth-child("+($data_number+2)+")").removeClass('btn-primary').addClass('btn-warning');
		}
		else if($arr[$data_number]==0){
			$('.footer_navigation').children(":nth-child("+($data_number+2)+")").removeClass('btn-warning').addClass('btn-primary');
		}
	});
	$(document).on('click','.submit',function(){
		// console.log($count_question+"  -  "+arr.length+"  -  "+arr.includes(0)+"  -  "+arr.includes(undefined));
		
		if($arr.length==0) $obj = null;

		if($arr.length==$count_question && !$arr.includes(0) && !$arr.includes(undefined)){
			if(confirm("Подтверждение действия")){
				// $('.section_content').removeAttr('disabled');
				sendAjax();
				// loadResultPage();
			}
		}
		else{
			if(confirm("Вы не отметили некоторые вопросы. Все равно продолжить?")){
				$('.section_content').removeAttr('disabled');
				sendAjax();
				// loadResultPage();
			}
		}
		function completeTest(date){
			$date = date.replace(/ /g , "_");
			// $('.section_content').removeAttr('disabled');
			// $('#arena_section').load('total_info.php');
			window.open(window.location.origin+'/altynbilim/local/test_result.php?date='+$date+'&<?php echo md5(md5('data_json'));?>='+$obj+'&t_name='+$t_name,'_blank');
			window.location.reload();
			// var win=window.open('', '_blank');
	 		// win.focus();
		}
		function sendAjax(){
			var formData = {
				'json':$obj
			};
			$.ajax({
				type 		: 'POST',
				url 		: 'controll-user.php?<?php echo md5(md5('test_result'))?>', 
				data 		: formData, 
				cache		: false,
				beforeSend:function(){
					$('#lll').css('display','block');
				},
				success: function(dataS){
					$('#lll').css('display','none');
					console.log("dataS");
					console.log(dataS);
					data = $.parseJSON(dataS);
					console.log("Data");
					console.log(data);
					if(data.success){
						completeTest(data.date);
			    	}
			    	else{
			    		console.log(data);
			    	}
				}
			});
		}
	});

	$(document).on('click','a',function(){
		if($(this).attr('data-type')=='schedule'){
			$('.box-schedule .modal-body').html("<center><h1>Loading...</h1></center>");
			$('.box-schedule .modal-body').load('schedule.php?data_num=<?php echo $_SESSION['student_num'];?>');
		}
	});



	function load_vimeo_video(link){
		console.log(link);
		$.ajax({
	    	url: "https://vimeo.com/api/oembed.json?url="+link,
			type: "GET",
			beforeSend:function(){
				$('#lll').css('display','block');
			},
			success: function(data){
		    	$('#lll').css('display','none');
		    	$('#video').append("<div class='vimeo_video'><center>"+data.html+"</div><hr>");
		    },
		  	error: function(dataS) 
	    	{
	    		console.log(dataS);
	    	} 	     
	   	});
	}
</script>
</body>
</html>
