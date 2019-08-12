<?php
//$pid=$_REQUEST['pid'];
//$client=$_REQUEST['client'];
$vid=$_REQUEST['vid'];


$con = mysqli_connect("localhost","firstadd_digital","admin@123","firstadd_digital");

// Check connection
if (mysqli_connect_errno())
  {
  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  $clid=0;
  $advid=0;
  $loc=0;
  
$selectRecords = "SELECT client_id,advertise_id,location_id from  schedules where videoUrl='".$vid."'";
$result=mysqli_query($con, $selectRecords);
if (mysqli_num_rows($result) > 0) {

    while($row = $result->fetch_assoc()) {
        $clientid=$row['client_id'];
        $clid=$clientid;
        $advid=$row['advertise_id'];
        $loc=$row['location_id'];


    }
    $updateRecords = "UPDATE clients SET totalslots=totalslots-1,consumslots=consumslots+1 where id=".$clientid;

  $result2 = mysqli_query($con, $updateRecords);
  
}

$selectRecordslc = "SELECT id from  location_slot_data where videoid='".$vid."' AND status=1";
$result2=mysqli_query($con, $selectRecordslc);

if (mysqli_num_rows($result2) > 0) {

  $updateRecordslc="update location_slot_data SET cosumetill=cosumetill+1 where videoid='".$vid."' AND clientid=".$clid." AND advid=".$advid." AND locationid=".$loc." AND status=1";
  $result3=mysqli_query($con, $updateRecordslc);

  }

  else
  {
    $date = date('Y-m-d H:i:s');
    $insertRecordslc="INSERT INTO location_slot_data ( `clientid`, `advid`, `locationid`, `videoid`, `updatedate`, `cosumetill`, `status`, `lastupdate`) VALUES (".$clid.",".$advid.",".$loc.",'".$vid."','".$date."',1,1,'".$date."')";

  $result4=mysqli_query($con, $insertRecordslc);
  }



echo "1";


 //$conn->close();
  
?>