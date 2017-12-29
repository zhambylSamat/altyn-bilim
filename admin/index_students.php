
<table class="table table-striped table-bordered">
	<?php
		$result_student = array();
		include('../connection.php');
		// if(!$_SESSION['load_page']){
		// 	$_SESSION['page'] = 'student';
		// }
		if(!isset($_GET['search']) || $_GET['search']==''){ 
			try {
				$stmt = $conn->prepare("SELECT s.student_num,
											s.name,
										    s.surname,
										    s.username,
										    s.password_type,
										    (select n.readed from news n where n.type = s.student_num ) as readed,
                                            (select count(r2.group_student_num) 
                                        	from review r2, 
                                        		group_student gs3,
                                        		group_info gr_info
                                        	where r2.review_info_num != (select review_info_num 
                                        								from review_info 
                                        								where description = 'comment') 
                                        		AND r2.group_student_num = gs3.group_student_num 
                                        		AND gs3.group_info_num = gr_info.group_info_num
                                        		AND gr_info.subject_num != 'S5985a7ea3d0ae721486338'
                                        		AND s.student_num = gs3.student_num ) as c1,
    										(select count(group_student_num) 
    										from group_student gs2,
    											group_info gr_info
    										where gs2.student_num = s.student_num
    											AND gs2.group_info_num = gr_info.group_info_num
    											AND gr_info.subject_num != 'S5985a7ea3d0ae721486338'
    											) as c2
										FROM student s
 										where s.block = 0
										group by s.student_num 
										order by s.surname, s.name asc");
			    $stmt->execute();
			    $result_student = $stmt->fetchAll(); 

			    $stmt = $conn->prepare("SELECT count(description) c FROM review_info where description = 'review' group by description");
			    $stmt->execute();
			    $total_comment_number = $stmt->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo "Error ".$e->getMessage()." !!!";
			}
			$_SESSION['result_student'] = $result_student;
			$_SESSION['total_comment_number'] = $total_comment_number;
		}
		else{
			$q = $_GET['search'];
			$total_comment_number = $_SESSION['total_comment_number'];
			foreach ($_SESSION['result_student'] as $val) {
				if (strpos(mb_strtolower($val['name']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['surname']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['username']), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['surname'])."_".mb_strtolower($val['name'])), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['name'])."_".mb_strtolower($val['surname'])), mb_strtolower($q)) !== false) {
					array_push($result_student, $val);
				}
			}
		}
		$student_number = 1;
		foreach ($result_student as $readrow) {
			$class = '';
			if($readrow['c1']!=$readrow['c2']*intval($total_comment_number['c'])){
				$class='warning';
			}
	?>
	<tr class='head <?php echo $class;?>'>
		<td style='width: 1%;'>
			<center>
				<h4>
					<?php 
						echo $student_number;
					?>	
				</h4>
			</center>
		</td>
		<td style=''>
			<form class='form-inline form-edit user_info' action='admin_controller.php' method='post' style='display:none;'>
				<div class='form-group'>
					<input type="text" class='form-control' name="surname" required="" value="<?php echo $readrow['surname'];?>">
				</div>
				<div class='form-group'>
					<input type="text" class='form-control' name="name" required="" value="<?php echo $readrow['name'];?>">
				</div>
				<div class='input-group'>
					<input type="text" class='form-control' name="username" required="" value='<?php echo $readrow['username']?>'>
				</div>
				<input type="hidden" name="edit-student-num" value='<?php echo $readrow['student_num'];?>'>
				<button type='submit' name='edit_user' class='btn btn-default btn-md' title='OK'>
					<span class='glyphicon glyphicon-ok-sign text-success pull-right' aria-hidden='true' style='cursor:pointer;'></span>
				</button>
				<a class='btn btn-default btn-md cancel_edit'  title='Отмена'>
					<span class='glyphicon glyphicon-remove-sign text-warning pull-right' aria-hidden='true' style='cursor:pointer;'></span>
				</a>
			</form>
			<div class='user_info'>
				<table class='' style='width:100%; background-color:rgba(0,0,0,0); margin:0; padding:0; border:none;'>
					<tr style='width: 100%;'>
						<td style='width: 40%;'><h4 class='text-success'><a href="student_info_marks.php?data_num=<?php echo $readrow['student_num']; ?>" target="_blank"><?php echo $readrow['surname']?>&nbsp;<?php echo $readrow['name']?></a></h4> </td>
						<td style='width: 30%;'><h5>Username: <b class='text-info'><?php echo $readrow['username']?></b></h5></td>
						<td style='width: 30%;'>
							<div class='password'>
								<h5'>Пароль: 
								<?php if($readrow['password_type']!='default'){?>
								<button class='btn btn-info btn-xs reset_password' data-name='student' style='display: inline-block;'>Сбросить пароль</button>
								<input type="hidden" name="reset" value='<?php echo $readrow['student_num']?>'>
								<?php }else{?>
								<b><u><i>'12345'</i></u></b>
								<?php }?>
								</h5'>
							</div>
						</td>
					</tr>
				</table>				
			</div>
		</td>
		<td>
			<form style='display: inline-block;' onsubmit="return confirm('Вы точно хотите удалить студента? Все данные об студенте будут удалены.')" action='' method=''>
				<center>
					<a class='btn btn-default btn-xs more_info' data-name='student' data_toggle='false' data_num = "<?php echo $readrow['student_num']?>" title='Толығырақ'>
						<span class='glyphicon glyphicon-list-alt text-primary' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
					</a>
					<a class='btn btn-default btn-xs edit_user' title='Өзгерту'>
						<span class='glyphicon glyphicon-pencil text-warning' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
					</a>
					<input type="hidden" name="remove-student-num" value="<?php echo $readrow['student_num']?>">
					<button class='btn btn-default btn-xs' type='submit' value='student_num' name='remove_student' title='Жою'>
						<span class='glyphicon glyphicon-remove text-danger' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
					</button>
				</center>
			</form>
			<form style='display: inline-block;' onsubmit="return confirm('Заблокировать студента?')" method='post' action='admin_controller.php'>
				<center>
					<input type="hidden" name="data_num" value='<?php echo $readrow['student_num']?>'>
					<button type='submit' name='block_student' class="btn btn-default btn-xs" title='Блокировать'>
						<span class='glyphicon glyphicon-ban-circle text-default' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
					</button>
				</center>
			</form>
			<a class="btn btn-default btn-xs single-student-news" data-num='<?php echo $readrow['student_num']?>' data-name='<?php echo $readrow['surname']?>&nbsp;<?php echo $readrow['name']?>' data-toggle='modal' data-target='.box-news'>
				<span class='glyphicon glyphicon-envelope' aria-hidden='true' style='font-size: 20px; cursor: pointer; <?php echo ($readrow['readed']=='') ? "color:black;" : (($readrow['readed']==0) ? "color:orange;" : "color:#00F300"); ?>'></span>
			</a>
			<form style='display: inline-block;' onsubmit="return confirm('Подтвердите действие!')" method='post' action='admin_controller.php'>
				<center>
					<input type="hidden" name="data_num" value='<?php echo $readrow['student_num']?>'>
					<button type='submit' name='student_no_payment' class="btn btn-default btn-xs" title='Оплатасы жоқ'>
						<span class='glyphicon glyphicon-usd text-danger' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
					</button>
				</center>
			</form>
			<form style='display: inline-block;' onsubmit="return confirm('Подтвердите действие!')" method='post' action='admin_controller.php'>
				<center>
					<input type="hidden" name="data_num" value='<?php echo $readrow['student_num']?>'>
					<button type='submit' name='student_no_contract' class="btn btn-default btn-xs" title='Договор өткізбегендер'>
						<span class='glyphicon glyphicon-file text-danger' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
					</button>
				</center>
			</form>
		</td>
	</tr>
	<tr class='body'>
		
	</tr>
	<?php $student_number++; }?>
</table>
<hr>
<center><h3 class='text-warning'>Оплатасы жоқтар!</h3></center>
<table class="table table-striped table-bordered">
	<?php
		$result_no_payment_student = array();
		if(!isset($_GET['search']) || $_GET['search']==''){ 
			try {
				
				$stmt = $conn->prepare("SELECT * FROM student WHERE block = 2 OR block = 3 order by surname asc");
				$stmt->execute();
				$result_no_payment_student = $stmt->fetchAll(); 
			} catch (PDOException $e) {
				echo "Error ".$e->getMessage()." !!!";
			}
			$_SESSION['result_no_payment_student'] = $result_no_payment_student;
		}
		else{
			$q = $_GET['search'];
			foreach ($_SESSION['result_no_payment_student'] as $val) {
				if (strpos(mb_strtolower($val['name']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['surname']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['username']), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['surname'])."_".mb_strtolower($val['name'])), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['name'])."_".mb_strtolower($val['surname'])), mb_strtolower($q)) !== false) {
					array_push($result_no_payment_student, $val);
				}
			}
		}
		$student_no_payment_number = 1;
		foreach ($result_no_payment_student as $readrow) {
	?>
	<tr class='head' style='<?php if($readrow['block']==3){ echo "border: 2px solid red;"; } ?>'>
		<td style='width: 5%;'><center><h4><?php echo $student_no_payment_number;?></h4></center></td>
		<td style='width: 75%;'>
			<div>
				<table class='table' style='background-color:rgba(0,0,0,0); margin:0; padding:0; border:none;'>
					<tr style='width: 100%;'>
						<td style='width: 50%;'><h4 class='text-success'><a href="student_info_marks.php?data_num=<?php echo $readrow['student_num']; ?>" target="_blank"><?php echo $readrow['surname']?>&nbsp;<?php echo $readrow['name']?></h4></a></td>
						<td style='width: 50%;'><h5>Username: <b class='text-info'><?php echo $readrow['username']?></b></h5></td>
						<td class='warned'>
							<?php if($readrow['block']==3){?><b style='color:#f00;'>Ескертілген</b><?php } ?>
						</td>
					</tr>
				</table>				
			</div>
		</td>
		<td style='width:20%'>
			<center>
				<a class='btn btn-default btn-sm more_info' data-name='student' data_toggle='false' data_num = "<?php echo $readrow['student_num']?>" title='Толығырақ'>
					<span class='glyphicon glyphicon-list-alt text-primary' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
				</a>
				<form style='display: inline-block;' method='post' action='admin_controller.php'>
					<center>
						<input type="hidden" name="data_num" value='<?php echo $readrow['student_num'];?>'>
						<button type='submit' class='btn btn-default btn-sm' name='unblock_student'>
							<span class='glyphicon glyphicon-ok-circle text-success' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
						</button>
					</center>
				</form>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<form style='display: inline-block;' onsubmit='return confirm("Вы точно хотите удалить студента? Все данные об студенте будут удалены.");' method='post' action='admin_controller.php'>
					<center>
						<input type="hidden" name="remove-student-num" value="<?php echo $readrow['student_num']?>">
						<button class='btn btn-danger btn-xs' type='submit' value='student_num' name='remove_student' title='Жою' style='height:25px;'>
							<!-- <span class='glyphicon glyphicon-remove text-danger' aria-hidden='true' style='font-size: 15px; vertical-align: center; cursor: pointer;'></span> -->
							<b style='color:white; vertical-align: middle;'>Удалить</b>
						</button>
					</center>
				</form>
				<br>
				<br>
				<?php if($readrow['block']!=3){ ?>
				<a class='btn btn-warning btn-sm open-access' data-num='<?php echo $readrow['student_num']?>' data-block='3'>Открыть портал</a>
				<?php }?>
			</center>
		</td>
	</tr>
	<tr class='body'>
		
	</tr>
	<?php
			$student_no_payment_number++; 
		} 
		if($student_no_payment_number == 1){
			echo "<center><h1 class='text-primary'>N/A</h1></center>";
		}
	?>
</table>
<hr>
<center><h3 class='text-warning'>Договор өткізбегендер!</h3></center>
<table class="table table-striped table-bordered">
	<?php
		$result_no_contract_student = array();
		if(!isset($_GET['search']) || $_GET['search']==''){ 
			try {
				
				$stmt = $conn->prepare("SELECT * FROM student WHERE block = 4 OR block = 5 order by surname asc");
				$stmt->execute();
				$result_no_contract_student = $stmt->fetchAll(); 
			} catch (PDOException $e) {
				echo "Error ".$e->getMessage()." !!!";
			}
			$_SESSION['result_no_contract_student'] = $result_no_contract_student;
		}
		else{
			$q = $_GET['search'];
			foreach ($_SESSION['result_no_contract_student'] as $val) {
				if (strpos(mb_strtolower($val['name']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['surname']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['username']), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['surname'])."_".mb_strtolower($val['name'])), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['name'])."_".mb_strtolower($val['surname'])), mb_strtolower($q)) !== false) {
					array_push($result_no_contract_student, $val);
				}
			}
		}
		$student_no_contract_number = 1;
		foreach ($result_no_contract_student as $readrow) {
	?>
	<tr class='head' style='<?php if($readrow['block']==5){ echo "border: 2px solid red;"; } ?>'>
		<td style='width: 5%;'><center><h4><?php echo $student_no_contract_number;?></h4></center></td>
		<td style='width: 75%;'>
			<div>
				<table class='table' style='background-color:rgba(0,0,0,0); margin:0; padding:0; border:none;'>
					<tr style='width: 100%;'>
						<td style='width: 50%;'><h4 class='text-success'><a href="student_info_marks.php?data_num=<?php echo $readrow['student_num']; ?>" target="_blank"><?php echo $readrow['surname']?>&nbsp;<?php echo $readrow['name']?></h4></a></td>
						<td style='width: 50%;'><h5>Username: <b class='text-info'><?php echo $readrow['username']?></b></h5></td>
						<td class='warned'>
							<?php if($readrow['block']==5){?><b style='color:#f00;'>Ескертілген</b><?php } ?>
						</td>
					</tr>
				</table>				
			</div>
		</td>
		<td style='width:20%'>
			<center>
				<a class='btn btn-default btn-sm more_info' data-name='student' data_toggle='false' data_num = "<?php echo $readrow['student_num']?>" title='Толығырақ'>
					<span class='glyphicon glyphicon-list-alt text-primary' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
				</a>
				<form style='display: inline-block;' method='post' action='admin_controller.php'>
					<center>
						<input type="hidden" name="data_num" value='<?php echo $readrow['student_num'];?>'>
						<button type='submit' class='btn btn-default btn-sm' name='unblock_student'>
							<span class='glyphicon glyphicon-ok-circle text-success' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
						</button>
					</center>
				</form>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<form style='display: inline-block;' onsubmit='return confirm("Вы точно хотите удалить студента? Все данные об студенте будут удалены.");' method='post' action='admin_controller.php'>
					<center>
						<input type="hidden" name="remove-student-num" value="<?php echo $readrow['student_num']?>">
						<button class='btn btn-danger btn-xs' type='submit' value='student_num' name='remove_student' title='Жою' style='height:25px;'>
							<!-- <span class='glyphicon glyphicon-remove text-danger' aria-hidden='true' style='font-size: 15px; vertical-align: center; cursor: pointer;'></span> -->
							<b style='color:white; vertical-align: middle;'>Удалить</b>
						</button>
					</center>
				</form>
				<br>
				<br>
				<?php if($readrow['block']!=5){ ?>
				<a class='btn btn-warning btn-sm open-access' data-num='<?php echo $readrow['student_num']?>' data-block='5'>Открыть портал</a>
				<?php }?>
			</center>
		</td>
	</tr>
	<tr class='body'>
		
	</tr>
	<?php
			$student_no_contract_number++; 
		} 
		if($student_no_contract_number == 1){
			echo "<center><h1 class='text-primary'>N/A</h1></center>";
		}
	?>
</table>
<hr>
<center><h3 class='text-danger'>Blocked student(s)!</h3></center>
<table class="table table-striped table-bordered">
	<?php
		$result_blocked_student = array();
		if(!isset($_GET['search']) || $_GET['search']==''){ 
			try {
				
				$stmt = $conn->prepare("SELECT * FROM student WHERE block = 1 order by surname asc");
				$stmt->execute();
				$result_blocked_student = $stmt->fetchAll(); 
			} catch (PDOException $e) {
				echo "Error ".$e->getMessage()." !!!";
			}
			$_SESSION['result_blocked_student'] = $result_blocked_student;
		}
		else{
			$q = $_GET['search'];
			foreach ($_SESSION['result_blocked_student'] as $val) {
				if (strpos(mb_strtolower($val['name']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['surname']), mb_strtolower($q)) !== false 
					|| strpos(mb_strtolower($val['username']), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['surname'])."_".mb_strtolower($val['name'])), mb_strtolower($q)) !== false 
					|| strpos((mb_strtolower($val['name'])."_".mb_strtolower($val['surname'])), mb_strtolower($q)) !== false) {
					array_push($result_blocked_student, $val);
				}
			}
		}
		$student_blocked_number = 1;
		foreach ($result_blocked_student as $readrow) {
	?>
	<tr>
		<td style='width: 5%;'><center><h4><?php echo $student_blocked_number;?></h4></center></td>
		<td style='width: 75%;'>
			<div>
				<table class='table' style='background-color:rgba(0,0,0,0); margin:0; padding:0; border:none;'>
					<tr style='width: 100%;'>
						<td style='width: 50%;'><h4 class='text-success'><?php echo $readrow['surname']?>&nbsp;<?php echo $readrow['name']?></h4> </td>
						<td style='width: 50%;'><h5>Username: <b class='text-info'><?php echo $readrow['username']?></b></h5></td>
					</tr>
				</table>				
			</div>
		</td>
		<td style='width:20%'>
			<center>
				<form style='display: inline-block;' method='post' action='admin_controller.php'>
					<center>
						<input type="hidden" name="data_num" value='<?php echo $readrow['student_num'];?>'>
						<button type='submit' class='btn btn-default btn-sm' name='unblock_student'>
							<span class='glyphicon glyphicon-ok-circle text-success' aria-hidden='true' style='font-size: 20px; cursor: pointer;'></span>
						</button>
					</center>
				</form>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<form style='display: inline-block;' onsubmit='return confirm("Вы точно хотите удалить студента? Все данные об студенте будут удалены.");' method='post' action='admin_controller.php'>
					<center>
						<input type="hidden" name="remove-student-num" value="<?php echo $readrow['student_num']?>">
						<button class='btn btn-danger btn-xs' type='submit' value='student_num' name='remove_student' title='Жою' style='height:25px;'>
							<!-- <span class='glyphicon glyphicon-remove text-danger' aria-hidden='true' style='font-size: 15px; vertical-align: center; cursor: pointer;'></span> -->
							<b style='color:white; vertical-align: middle;'>Удалить</b>
						</button>
					</center>
				</form>
			</center>
		</td>
	</tr>
	<?php
			$student_blocked_number++; 
		} 
		if($student_blocked_number == 1){
			echo "<center><h1 class='text-primary'>N/A</h1></center>";
		}
	?>
</table>