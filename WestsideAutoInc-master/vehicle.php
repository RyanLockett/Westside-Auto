<?php
    $conn = new mysqli("localhost", "root", "", "WestsideAutoIncDB");

    // Check connection
    if ($conn->error) {
        die("Error: " . $conn->error);
    }
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

	<head>
		<title>Westside Auto Inc. | Vehicle</title>
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

		<!-- CSS -->
		
		<link rel="stylesheet" href="css/app.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<link href="css/foundation.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.foundation.min.css">
        
        <script>
            function showVehicle(str) {
                if (str == "") {
                    document.getElementById("txtHint").innerHTML = "";
                    return;
                } else { 
                    if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET","getVehicle.php?q="+str,true);
                    xmlhttp.send();
                    console.log('ready!');
                }
            }
        </script>

	</head>
	
	<body>
        
        <!-- NAVIGATION -->
        
        <div class="grid-x">
            <div class="cell">
                <ul class="menu align-right menu-hover-lines">
                    <li><a href="/">Home</a></li>
                    <li><a href="buyer.php">Buyer</a></li>
                    <li><a href="purchase.php">Purchase</a></li>
                    <li class="active"><a href="vehicle.php">Vehicle</a></li>
                    <li><a href="warrantyitem.php">Warranty Item</a></li>
                    <li><a href="salesperson.php">Salesperson</a></li>
                    <li><a href="sale.php">Sale</a></li>
                    <li><a href="customer.php">Customer</a></li>
                </ul>
            </div>
        </div>
        
        <div class="form">
            <div class="grid-container">
                <div class="large-12 cell">
                    <div class="title">Vehicle info</div>
                    <div class="description">Use this form when editing vehicle information.</div>
                </div>
                
                <?php
                
                    // PHP FOR UPDATING A VEHICLE
                        
                    if(isset($_POST['updateVehicle'])){
                        $vehicleID = $_POST['q'];
                        $make = $_POST['make'];
                        $model = $_POST['model'];
                        $year = $_POST['year'];
                        $style = $_POST['style'];
                        $color = $_POST['color'];
                        $interiorColor = $_POST['interiorColor'];
                        $mileage = $_POST['mileage'];
                        $condition = $_POST['condition'];
                        $bookPrice = $_POST['bookPrice'];
                        $pricePaid = $_POST['pricePaid'];
                        $listingPrice = $_POST['listingPrice'];

                        $stmt = $conn->prepare("UPDATE Vehicle SET Make=?, Model=?, Year=?, Style=?, Color=?, InteriorColor=?, Mileage=?, `Condition`=?, BookPrice=?, PricePaid=?, ListingPrice=? WHERE VehicleID=?");
                        $stmt->bind_param("ssisssisdddi", $make, $model, $year, $style, $color, $interiorColor, $mileage, $condition, $bookPrice, $pricePaid, $listingPrice, $vehicleID);
                        $stmt->execute();
                        
                        if($stmt->affected_rows === -1) {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border alert">
                            <strong>Boo!</strong> - It broke!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div></div>';
                        } else {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border success">
                            <strong>Yay!</strong> - You updated a vehicle!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div></div>';
                        }
                        $stmt->close();
                    }
                
                    // PHP FOR UPDATING A REPAIR
                        
                    if(isset($_POST['updateRepair'])){
                        $vehicleID = $_POST['q'];
                        $repairID = $_POST['r'];
                        $actualCost = $_POST['actualCost'];
                        $problem = $_POST['problem'];

                        $stmt = $conn->prepare("UPDATE Repair SET ActualCost=?, Problem=? WHERE RepairID=? AND VehicleID=?");
                        $stmt->bind_param("dsii", $actualCost, $problem, $repairID, $vehicleID);
                        $stmt->execute();
                        
                        if($stmt->affected_rows === -1) {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border alert">
                            <strong>Boo!</strong> - It broke!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div></div>';
                        } else {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border success">
                            <strong>Yay!</strong> - You updated a repair!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div></div>';
                        }

                        $stmt->close();
                    }
                ?>
                
                <!--VEHICLE TABLE-->
                
                <div class="grid-x grid-padding-x">
                    <div class="large-12 cell">
                        <table id="vehicleTable" class="display">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Make</th>
                                    <th>Mode</th>
                                    <th>Color</th>
                                    <th>Mileage</th>
                                    <th>Style</th>
                                    <th>Interior Color</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $sql = "SELECT VehicleID, Make, Model, Year, Color, Mileage, Style, InteriorColor FROM Vehicle";
                                    $result = mysqli_query($conn, $sql);
                                    while ($row = $result->fetch_assoc()) {
                                        $VehicleID = $row['VehicleID'];
                                        $Year = $row['Year'];
                                        $Make = $row['Make'];
                                        $Model= $row['Model'];
                                        $Color = $row['Color'];
                                        $Mileage = $row['Mileage'];
                                        $Style = $row['Style'];
                                        $InteriorColor = $row['InteriorColor'];
                                        echo '<tr>';
                                        echo '<td>'.$Year.'</td>';
                                        echo '<td>'.$Make.'</td>';
                                        echo '<td>'.$Model.'</td>';
                                        echo '<td>'.$Color.'</td>';
                                        echo '<td>'.$Mileage.'</td>';
                                        echo '<td>'.$Style.'</td>';
                                        echo '<td>'.$InteriorColor.'</td>';
                                        echo '<td><button type="button" id="'.$VehicleID.'" class="float-right table-button" onclick="showVehicle(this.id)">Edit</button></td>';
                                        echo '</tr>';

                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="txtHint">Vehicle data will be displayed here...</div>
                
            </div>
        </div>
        
        <!-- JQUERY FIRST -->
		
		<script type="text/javascript" src="js/vendor/jquery.js"></script>
		
		<!-- OTHER SCRIPTS -->
		
		<script type="text/javascript" src="js/vendor/foundation.min.js"></script>
		<script type="text/javascript" src="js/app.js"></script>	
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.foundation.min.js"></script>
        
        <!--START DATATABLES-->
		<script type="text/javascript"> 
            $(document).ready( function () {
                $('#vehicleTable').DataTable();
            });
		</script>
	</body>
</html>

<?php 
    if(isset($_GET['q'])) {
        echo '<script type="text/javascript">
                showVehicle('.$_GET['q'].');
              </script>';
    }
    $conn->close();
?>