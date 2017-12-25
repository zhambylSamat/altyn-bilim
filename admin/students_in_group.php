<?php
	include('../connection.php');
	try {
		$stmt = $conn->prepare("SELECT s.subject_name,
									s.subject_num, 
									gi.group_info_num, 
									t.name, 
									t.surname, 
									gi.group_name, 
									gi.comment,
								    (select count(r.group_student_num) from review r WHERE r.group_student_num = gs.group_student_num AND r.review_info_num != (select review_info_num from review_info where description = 'comment') group by r.group_student_num) c
								FROM group_info gi, 
									group_student gs, 
									subject s, 
									teacher t
								WHERE gi.subject_num = s.subject_num 
									AND gs.student_num = :student_num 
									AND gs.group_info_num = gi.group_info_num 
									AND gi.teacher_num = t.teacher_num
								order by last_update asc");
		$stmt->bindParam(':student_num', $_GET['data_num'], PDO::PARAM_STR);
	    $stmt->execute();
	    $result = $stmt->fetchAll(); 

	    $stmt = $conn->prepare("SELECT count(description) c FROM review_info where description = 'review' group by description");
	    $stmt->execute();
	    $total_comment_number = $stmt->fetch(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>
<td colspan='3'>
<ol type='I'>
<?php
	$count = 0;
	foreach ($result as $value) {
		$count++;
		$alert = 'hide';
		if($value['c']=='' || $value['c']%intval($total_comment_number['c'])!=0){
			$alert='show';
		}
?>
<li>
	<?php if($alert=='show' && $value['subject_num']!='S5985a7ea3d0ae721486338'){ ?><span class='glyphicon glyphicon-remove' style='color: red;?>'></span><?php } ?>
	<a href="group.php?data_num=<?php echo $value['group_info_num'];?>" target='_blank'>
		<span style='margin-left:5%'>Группа: <b><?php echo $value["group_name"];?></b></span>
		<span style='margin-left:5%;'>Пән: <b><?php echo $value['subject_name'];?></b></span>
		<span style='margin-left:5%;'>Мұғалім: <b><?php echo $value['surname']." ".$value['name'];?></b></span>
		<span style='margin-left:5%;'>Түсініктеме: <b><?php echo $value['comment'];?></b></span>
	</a>
</li>
<?php } ?>
<?php 
	if($count==0){
		echo "<li>N/A</li>";
	}
?>
</ol>
</td>