<?php

$tbs='<table id="myTable" style="width:100%"><thead><tr><th>Client Name</th><th>Location</th><th>device Id</th><th>Total Slot</th><th>Consume Till Time</th><th>Available Slots</th><th>Last Updated</th></tr></thead><tbody>';

$con = mysqli_connect("localhost","firstadd_digital","admin@123","firstadd_digital");

// Check connection
if (mysqli_connect_errno())
  {
  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  
  
$selectRecords = "SELECT t3.fname,t3.lname,t2.location,t2.deviceId,t3.totalslots,t3.avilslots,t3.consumslots,t1.cosumetill, t1.lastupdate FROM location_slot_data as t1 LEFT JOIN locations as t2 ON t1.locationid=t2.id LEFT JOIN clients as t3 ON t1.clientid=t3.id LEFT JOIN advertises as t4 ON t4.id=t1.advid where  t2.IsActive=1 AND t3.IsActive=1";

$result=mysqli_query($con, $selectRecords);

if (mysqli_num_rows($result) > 0) {
$flarr=array();

    while($row = $result->fetch_assoc()) {
        
        
        //$flarr[]=$row;
        $tbs=$tbs."<tr><td>".$row['fname']." ".$row['lname']."</td><td>".$row['location']."</td><td>".$row['deviceId']."</td><td>".$row['avilslots']."</td><td>".$row['cosumetill']."</td><td>".$row['totalslots']."</td><td>".$row['lastupdate']."</td></tr>";


    }
    //$tbs=$tbs.'</tbody><tfoot> <tr><th colspan="4" style="text-align:right">Total:</th><th></th></tr></tfoot></table>';
     $tbs=$tbs.'</tbody></table>';
    
    
    
    
  
}



 //$conn->close();
  
?>

<html>
    <head>
       <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
       
       <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css">
       <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.material.min.css">
       


       <script src="//code.jquery.com/jquery-3.3.1.js"></script>
      
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    </head>
    <body>
        <?php
        echo $tbs;
        ?>
        
        <script>
            $(document).ready( function () {
                    $('#myTable').DataTable({
        columnDefs: [ {
            targets: [ 0 ],
            orderData: [ 0, 1 ]
        }, {
            targets: [ 1 ],
            orderData: [ 1, 0 ]
        }, {
            targets: [ 4 ],
            orderData: [ 4, 0 ]
        } ],
        "pagingType": "full_numbers",
        className: 'mdl-data-table__cell--non-numeric',
        
    });
            } );
        </script>
    </body>
</html>