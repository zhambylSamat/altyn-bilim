<?php
include_once('../connection.php');
try {
  echo $_SESSION['mac'];
	$stmt = $conn->prepare("SELECT * FROM device_mac_address");
	     
    $stmt->execute();
    $result_mac = $stmt->fetchAll(); 
    print_r($result_mac);
    echo "<br>";
    // foreach($result_mac as $mac){
    // 	ech/o $mac['mac_address'];
    // }
} catch (PDOException $e) {
	echo "Error : ".$e->getMessage()." !!!";
}
$count = 0;
?>

	<script type="text/javascript">
		(function() {
            // Connect to WMI
            console.log('start check');
            var locator = new ActiveXObject("WbemScripting.SWbemLocator");
            console.log('end check');
              var service = locator.ConnectServer(".");
              
              // Get the info
              var properties = service.ExecQuery("SELECT * FROM Win32_NetworkAdapterConfiguration");
              // for (var i in properties) {
              //     document.write("<p>"+i+"</p>");
              // }
              var e = new Enumerator (properties);
              var count = 0;
              <?php $count = 0;?>
              for (;!e.atEnd();e.moveNext ())
              { 
                  var p = e.item ();
                  var pp = p.MACAddress;
                  console.log(pp);
                  <?php $count = 0; foreach($result_mac as $mac){?>
                   if(pp == '<?php echo $mac['mac_address'];?>'){
                    count++;
                     <?php 
                        $count++;
                        $_SESSION[md5(md5('mac'))] = md5(md5($mac['mac_address']));
                        $_SESSION['mac'] = $mac['mac_address'];
                        // header('location:finish.php');
                      ?>
                     break;
                   }
                 <?php } ?>
                 
              }
              if(count==0){
                <?php 
                  $_SESSION['mac']='ema';
                  $_SESSION[md5(md5('mac'))] = 'false';
                ?>
              }
        })();
	</script>
  <?php 
    echo $_SESSION['mac']." - ".$count;
    echo "<br>";
    echo $_SESSION[md5(md5('mac'))];
    unset($_SESSION['mac']);
  ?>