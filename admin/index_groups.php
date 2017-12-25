<?php
	$result_group_info = array();
	include('../connection.php');
	if(!isset($_GET['search']) || $_GET['search']==''){ 
		try {
			$stmt = $conn->prepare("SELECT gi.group_info_num group_info_num, gi.group_name group_name, gi.comment comment, t.name name, t.surname surname, s.subject_name subject_name, (SELECT count(*) FROM group_student gs, student s WHERE gs.group_info_num = gi.group_info_num AND s.student_num = gs.student_num AND s.block != 1 ) student_quantity FROM group_info gi, teacher t, subject s WHERE gi.teacher_num=t.teacher_num AND gi.subject_num = s.subject_num order by t.surname asc");
			
		    $stmt->execute();
		    $result_group_info = $stmt->fetchAll(); 
		    $_SESSION['result_group_info'] = $result_group_info;
		} catch (PDOException $e) {
			echo "Error ".$e->getMessage()." !!!";
		}	
	}	
	else{
		$q = $_GET['search'];
		foreach ($_SESSION['result_group_info'] as $val) {
			if (strpos(mb_strtolower($val['name']), mb_strtolower($q)) !== false 
				|| strpos(mb_strtolower($val['surname']), mb_strtolower($q)) !== false 
				|| strpos(mb_strtolower($val['subject_name']), mb_strtolower($q)) !== false 
				|| strpos(mb_strtolower($val['group_name']), mb_strtolower($q)) !== false
				|| strpos((mb_strtolower($val['surname'])."_".mb_strtolower($val['name'])), mb_strtolower($q)) !== false 
				|| strpos((mb_strtolower($val['name'])."_".mb_strtolower($val['surname'])), mb_strtolower($q)) !== false) {
				array_push($result_group_info, $val);
			}
		}
	}
?>
<table class="table table-bordered table-hover table-groups-info">
	<?php
		foreach ($result_group_info as $value) {
	?>
	<tr class='row-groups-info' style='padding:1%;'>
		<td style='border-color:black; padding:2% 1% 2% 1%;'>
			<div class='group-info'>
				<a href="group.php?data_num=<?php echo $value['group_info_num'];?>">
					<center><span class='h3'>Группа: <?php echo $value['group_name']?></span></center>
					<hr style='margin:5px 0 5px 0; border:0.5px solid lightgray;'>
					<table class='' style='width:100%;'>
						<tr>
							<td style="width: 25%;"><center><span><u class='h4 text-success'><?php echo $value['surname']." ".$value['name'];?></u></span></center></td>
							<td style="width: 25%;"><center><span><u class='h4 text-primary'><?php echo $value['subject_name'];?></u></span></center></td>
							<td style="width: 25%;"><center><span style='color:black;'><b>Оқушылар саны:</b> <?php echo $value['student_quantity'];?></span></center></td>
							<td style="width: 25%;"><center><span style='color:black;'><b>Түсініктеме:<br></b> <?php echo ($value['comment']!='') ? $value['comment'] : "N/A";?></span></center></td>
						</tr>
					</table>
				</a>
			</div>
			<form class="form-inline group-form" onsubmit="return confirm('Подтвердите действие!!!');" action='admin_controller.php' method='post' style='display: none;'>
				<div class='form-group'>
					<input type="text" name="group_name" class='form-control' value='<?php echo $value['group_name']; ?>'>
				</div>
				<div class='form-group'>
					<?php
						try {
							$stmt = $conn->prepare("SELECT * FROM teacher order by surname asc");
				     
						    $stmt->execute();
						    $result_group_teacher = $stmt->fetchAll();
						} catch (PDOException $e) {
							echo "Error ".$e->getMessage()." !!!";
						}
					?>
					<select name='group_teacher' class='form-control' required="">
						<option value=''>Мұғалім</option>
						<?php 
							foreach ($result_group_teacher as $tValue) {
						?>
						<option value='<?php echo $tValue['teacher_num'];?>' <?php echo ($tValue['name']==$value['name'] && $tValue['surname']==$value['surname']) ? "selected" : ''; ?>><?php echo $tValue['surname']." ".$tValue['name'];?></option>
						<?php } ?>
					</select>
				</div>
				<div class='form-group'>
					<?php
						try {
							$stmt = $conn->prepare("SELECT * FROM subject order by subject_name asc");
				     
						    $stmt->execute();
						    $result_group_subject = $stmt->fetchAll();
						} catch (PDOException $e) {
							echo "Error ".$e->getMessage()." !!!";
						}
					?>
					<select name='group_subject' class='form-control' required="">
						<option value=''>Пән</option>
						<?php 
							foreach ($result_group_subject as $sValue) {
						?>
						<option value='<?php echo $sValue['subject_num'];?>' <?php echo ($sValue['subject_name']==$value['subject_name']) ? "selected" : ''; ?>><?php echo $sValue['subject_name'];?></option>
						<?php } ?>
					</select>
					<textarea name='group_comment' class='form-control' rows='1' cols='30'><?php echo $value['comment'];?></textarea>
					<input type="hidden" name="data_num" value='<?php echo $value['group_info_num'];?>'>
				</div>
				<input type="submit" class='btn btn-sm btn-success' name="edit_group_info" value='Сохранить'>
				<input type="reset" class='btn btn-warning btn-sm' value='Отмена'>
				<input type="submit" class='btn btn-xs btn-danger' name="delet_group_info" value='Удалить'>
			</form>
		</td>
		<td style='border-color:black;'>
			<center>
				<button class='btn btn-sm btn-default' btn-info='edit'>&nbsp;&nbsp;&nbsp;&nbsp;<span class='glyphicon glyphicon-option-horizontal'></span>&nbsp;&nbsp;&nbsp;&nbsp;</button>
			</center>
			<center>
				<button class='btn btn-sm schedule-btn' data-toggle='modal' data-target='.box-group-schedule' btn-info='schedule'>&nbsp;&nbsp;&nbsp;&nbsp;<span class='glyphicon glyphicon-calendar'></span>&nbsp;&nbsp;&nbsp;&nbsp;</button>
			</center>
		</td>
	</tr>
	<?php } ?>
</table>