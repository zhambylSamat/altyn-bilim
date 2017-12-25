<?php 
	include('../connection.php');
	if(!$_SESSION['load_page']){
		$_SESSION['page'] = 'parent';
	}
?>
<button class='btn btn-success btn-sm new-parent' at='new-parent' id='new-parent-btn'>Ата-ана тіркеу</button>
<div id='new-parent'>
	<form id='create-parent' method='post'>
		<div class='row'>
			<div class='col-md-6 col-sm-6'>
				<div class='form-group'>
					<label for='parent-surname'>Тегі: </label>
					<input type="text" id='parent-surname' class="form-control" name='parent_surname' placeholder="Тегі" required="">
				</div>
				<div class='form-group'>
					<label for='parent-name' style='display: inline-block;'>Аты: </label>
					<input style='display: inline-block;' type="text" id='parent-name' class='form-control' name="parent_name" placeholder="Аты" required="">
				</div>
				<div class='form-group'>
					<label for='parent-phone'>Телефон: </label>
					<div class='input-group'>
						<div class='input-group-addon'>+7</div>
						<input type="number" min='7000000000' max='7999999999' id='parent-phone' name="parent_phone" class='form-control' placeholder='7777044551' required="" pattern='[0-9]{10}'>
					</div>
				</div>	
			</div>
			<div class='col-md-6 col-sm-6'>
				<div class='form-group'>
					<label for='parent-student'>Студент</label>
					<?php
						$result_students;
						try {
							$stmt = $conn->prepare("SELECT student_num, name, surname FROM student WHERE student_num NOT IN (SELECT ch.student_num FROM parent p, child ch WHERE p.parent_num = ch.parent_num) ORDER BY name asc");
							$stmt->execute();
							$result_students = $stmt->fetchAll();	
						} catch (PDOException $e) {
							echo "Error: " . $e->getMessage();
						}
					?>
					<select name='students' class='form-control student-list'>
						<option value=''>Студентті таңдаңыз</option>
						<?php
							foreach($result_students as $value){
						?>
						<option value='<?php echo $value['student_num'];?>'><?php echo $value['name']." ".$value['surname'];?></option>
						<?php } ?>
					</select>
				</div>
				<div class='form-group std' style='border:1px dashed lightgray; border-radius: 5px; padding:3%;'>
				</div>
			</div>
			<center><input type="submit" class='btn btn-sm btn-success' name="create_new_parent" value='Сақтау'></center>
		</div>
	</form>
</div>
<input type="text" name="search" data-name='parent' class='form-control pull-right' id='search' style='width: 20%;' placeholder="Поиск...">
<hr>
<div class='parents'>
	<?php include_once('index_parents.php'); ?>
</div>