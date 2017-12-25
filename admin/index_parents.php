<?php
	include_once('../connection.php');
	$result_parent_list = array();
	try {
		if(!isset($_GET['search']) || $_GET['search']==''){
			$stmt = $conn->prepare("SELECT p.parent_num parent_num, p.name parent_name, p.surname parent_surname, p.phone phone, s.student_num student_num, s.name student_name, s.surname student_surname FROM student s, parent p, child ch WHERE s.student_num = ch.student_num AND p.parent_num = ch.parent_num ORDER BY parent_surname, student_name asc ");
			$stmt->execute();
			$result_parent_list = $stmt->fetchAll();
			$_SESSION['result_parent_list'] = $result_parent_list;
		}
		else{
			$q = $_GET['search'];
			foreach ($_SESSION['result_parent_list'] as $val) {
				if (strpos(mb_strtolower($val['parent_name']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['parent_surname']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['phone']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['student_name']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['student_surname']), mb_strtolower($q)) !== false
					|| strpos((mb_strtolower($val['parent_surname'])."_".mb_strtolower($val['parent_name'])), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['parent_name'])."_".mb_strtolower($val['parent_surname'])), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['student_name'])."_".mb_strtolower($val['student_surname'])), mb_strtolower($q)) !== false
					|| strpos((mb_strtolower($val['student_surname'])."_".mb_strtolower($val['student_name'])), mb_strtolower($q)) !== false) {
					array_push($result_parent_list, $val);
				}
			}
		}
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>
<table class="table table-striped table-bordered">
<?php 
	$parent_number = 1;
	$parent_num = '';
	for ($i = 0; $i<count($result_parent_list); $i++) {
		if($parent_num != $result_parent_list[$i][0]){
		$parent_num = $result_parent_list[$i][0];
?>
	<tr class='row-parents-info'>
		<td style='width: 5%;'><center><h4><?php echo $parent_number;?></h4></center></td>
		<td style='width: 75%'>
			<form class='form-inline form-edit parent-form' action='admin_controller.php' method='post'>
				<div class='form-group'>
					<input type="text" class='form-control' name="parent_surname" required="" value="<?php echo $result_parent_list[$i][2];?>">
				</div>
				<div class='form-group'>
					<input type="text" class='form-control' name="parent_name" required="" value="<?php echo $result_parent_list[$i][1];?>">
				</div>
				<div class='form-group'>
					<div class='input-group'>
						<div class='input-group-addon'>+7</div>
						<input type="number" max='7999999999' min='7000000000' step='1' class='form-control' name="phone" required="" value='<?php echo $result_parent_list[$i][3]?>'>
					</div>
				</div>
				<input type="hidden" name="edit-parent" value='<?php echo $result_parent_list[$i][0];?>'>
				<br>
				<div style='border:1px dashed lightgray; border-radius: 5px; margin:1%; padding:1%;'>
					<div class='row'>
						<div class='col-md-6 col-sm-6'>
							<?php
								$result_students;
								try {
									$stmt = $conn->prepare("SELECT student_num, name, surname FROM student ORDER BY name asc");
									$stmt->execute();
									$result_students = $stmt->fetchAll();	
								} catch (PDOException $e) {
									echo "Error: " . $e->getMessage();
								}
							?>
							<select name='students' class='form-control student-list' style='width: 100%;'>
								<option value=''>Студентті таңдаңыз</option>
								<?php
									foreach($result_students as $value){
								?>
								<option value='<?php echo $value['student_num'];?>'><?php echo $value['name']." ".$value['surname'];?></option>
								<?php } ?>
							</select>
						</div>
						<div class='col-md-6 col-sm-6 std'>
							<?php
								$j = $i;
								while(isset($result_parent_list[$j][0]) && $result_parent_list[$i][0]==$result_parent_list[$j][0]){
							?>
							<div class='single-student' style='border:1px solid lightgray; border-radius: 5px; padding:2% 4%;'>
								<span style='overflow:hidden;'><?php echo $result_parent_list[$j][5]." ".$result_parent_list[$j][6]; ?></span>
								<a class='btn btn-xs pull-right remove-student-from-list'>
									<span class='glyphicon glyphicon-remove text-danger'></span>
								</a>
								<input type='hidden' name='students[]' value='<?php echo $result_parent_list[$j][4]; ?>'>
							</div>
							<?php $j++; } ?>
						</div>
					</div>
				</div>
				<center>
					<button type='submit' name='edit_parent' class='btn btn-default btn-md' title='OK'>
						<span class='glyphicon glyphicon-ok-sign text-success pull-right' aria-hidden='true' style='cursor:pointer;'></span>
					</button>
					<a class='btn btn-default btn-md cancel_edit'  title='Отмена'>
						<span class='glyphicon glyphicon-remove-sign text-warning pull-right' aria-hidden='true' style='cursor:pointer;'></span>
					</a>
				</center>
			</form>
			<div class='parent-info'>
				<table class='' style='width:100%; background-color:rgba(0,0,0,0); margin:0; padding:0; border:none;'>
					<tr style='width: 100%;'>
						<td style='width: 25%;'>
							<h4 class='text-success'><?php echo $result_parent_list[$i][2]?>&nbsp;<?php echo $result_parent_list[$i][1];?></h4> 
						</td>
						<td style='width: 25%;'><h5>Телефон: <b class='text-info'><?php echo $result_parent_list[$i][3];?></b></h5></td>
						<td style='width: 25%;'>
							<?php
								$j = $i;
								while(isset($result_parent_list[$j][0]) && $result_parent_list[$i][0]==$result_parent_list[$j][0]){
							?>
							<div class='single-student' style='border:1px solid lightgray; border-radius: 5px; padding:2% 4%;'>
								<span style='overflow:hidden;'><?php echo $result_parent_list[$j][5]." ".$result_parent_list[$j][6]; ?></span>
							</div>
							<?php $j++; } ?>
						</td>
					</tr>
				</table>				
			</div>
		</td>
		<td style='width: 20%;'>
			<form onsubmit="return confirm('Вы точно хотите удалить родителя?')" action='admin_controller.php' method='post'>
				<center>
					<a class='btn btn-default btn-sm edit_parent' title='Өзгерту'>
						<span class='glyphicon glyphicon-pencil text-warning' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
					</a>
					<input type="hidden" name="remove-parent-num" value="<?php echo $result_parent_list[$i][0];?>">
					<button class='btn btn-default btn-sm' type='submit' value='student_num' name='remove_parent' title='Жою'>
						<span class='glyphicon glyphicon-remove text-danger' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
					</button>
				</center>
			</form>
		</td>
	</tr>
	<?php $parent_number++; }} ?>
</table>