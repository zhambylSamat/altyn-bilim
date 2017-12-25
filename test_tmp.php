<?php
  if(isset($_POST['sss']) && isset($_POST['ch'])){
    $res = $_POST['ch'];
    for($i = 0; $i<count($res); $i++){
      // for($j = 0; $j<3; $j++){
        echo $res[$i]." - ".$i."</br>";
      // }
    }
    // echo $res['asdf'][1][0];
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>test</title>
</head>
<body>
<form action='test_tmp.php' method='post'>
  <div>
    <label>01</label>
    <input type="checkbox" name="ch[0]" value='01'>
  </div>
  <div>
    <label>12</label>
    <input type="checkbox" name="ch[1]" value='12'>
  </div>
  <div>
    <label>23</label>
    <input type="checkbox" name="ch[2]" value='23'>
  </div>
  <div>
    <label>34</label>
    <input type="checkbox" name="ch[3]" value='34'>
  </div>
  <div>
    <label>45</label>
    <input type="checkbox" name="ch[4]" value='45'>
  </div>
  <div>
    <label>56</label>
    <input type="checkbox" name="ch[5]" value='56'>
  </div>
  <input type="submit" name="sss">
</form>

<video width="400" controls>
  <source src="video/v1.mp4" type="video/mp4">
  Your browser does not support HTML5 video.
</video>
<?php
 echo uniqid('Admin',true);
?>
</body>
</html>