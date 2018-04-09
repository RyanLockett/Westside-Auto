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
		<title>Westside Auto Inc. | Purchase</title>
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

		<!-- CSS -->
		
		<link rel="stylesheet" href="css/app.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<link href="css/foundation.min.css" rel="stylesheet" type="text/css" />
	</head>
	
	<body>
        
        <!-- NAVIGATION -->
        
        <div class="grid-x">
            <div class="cell">
                <ul class="menu align-right menu-hover-lines">
                    <li><a href="/">Home</a></li>
                    <li><a href="buyer.php">Buyer</a></li>
                    <li class="active"><a href="purchase.php">Purchase</a></li>
                    <li><a href="vehicle.php">Vehicle</a></li>
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
                    <div class="title">Purchase a vehicle</div>
                    <div class="description">Use this form when a buyer purchases a vehicle.</div>
                </div>

                <?php
                    if(isset($_POST['purchaseVehicle'])){
                        
                        /*Purchase*/
                        $buyerID = $_POST['buyerID'];
                        $date = $_POST['date'];
                        if (isset($_POST['auction']))
                            $auction = 1;
                        else
                            $auction = 0;
                        $seller = $_POST['seller'];
                        $location = $_POST['location'];
                        
                        /*Insert into Purchase*/
                        $stmtPurchase = $conn->prepare("INSERT INTO Purchase (BuyerID, Date, IsAuction, Seller, Location) VALUES (?, ?, ?, ?, ?)");
                        $stmtPurchase->bind_param("isiss", $buyerID, $date, $auction, $seller, $location);
                        $stmtPurchase->execute();
                        
                        /*Vehicle*/
                        
                        $sumRepairs = 0;
                        for ($i = 0; $i < count($_POST['make']); $i++) 
                        {
                            $makeCur = $_POST['make'][$i];
                            $modelCur = $_POST['model'][$i];
                            $yearCur = $_POST['year'][$i];
                            $styleCur = $_POST['style'][$i];
                            $colorCur = $_POST['color'][$i];
                            $interiorColorCur = $_POST['interiorColor'][$i];
                            $mileageCur = $_POST['mileage'][$i];
                            $conditionCur = $_POST['condition'][$i];
                            $bookPriceCur = $_POST['bookPrice'][$i];
                            $pricePaidCur = $_POST['pricePaid'][$i];
                            
                            /*Insert into Vehicle*/
                            $stmtVehicle = $conn->prepare("INSERT INTO Vehicle (PurchaseID, Make, Model, Year, Color, Mileage, `Condition`, BookPrice, PricePaid, Style, InteriorColor, IsSold) VALUES ((SELECT MAX(PurchaseID) AS PurchaseID FROM Purchase), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
                            $stmtVehicle->bind_param("ssisisddss", $makeCur, $modelCur, $yearCur, $colorCur, $mileageCur, $conditionCur, $bookPriceCur, $pricePaidCur, $styleCur, $interiorColorCur);
                            $stmtVehicle->execute();
                            
                            $numRepairs = $_POST['numRepairs'][$i];
                            for($j = $sumRepairs; $j < $sumRepairs + $numRepairs; $j++)
                                {
                                    if(!empty($_POST['estCost'][$j]) && !empty($_POST['problem'][$j])) {
                                        /*Repair*/
                                        $estCostCur = $_POST['estCost'][$j];
                                        $problemCur = $_POST['problem'][$j];   

                                        /*Insert into Repair*/
                                        $stmtRepair = $conn->prepare("INSERT INTO Repair (VehicleID, EstCost, Problem) VALUES ((SELECT MAX(VehicleID) AS VehicleID FROM Vehicle), ?, ?)");
                                        $stmtRepair->bind_param("ds", $estCostCur, $problemCur);
                                        $stmtRepair->execute();
                                    }
                                }
                                $sumRepairs += $numRepairs;
                        }
                     
                        if($stmtVehicle->affected_rows === -1 || $stmtPurchase->affected_rows === -1 && isset($stmtRepair) && $stmtRepair->affected_rows === -1) {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border alert">
                            <strong>Boo!</strong> - It broke!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div></div>';
                        } else {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border success">
                            <strong>Yay!</strong> - You added a new purchase!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div></div>';
                            }
                        $stmtPurchase->close();
                        $stmtVehicle->close();
                        if(isset($stmtRepair))
                            $stmtRepair->close();
                    }
                ?>
                
                <form class="data" action="purchase.php" method="post" onsubmit="countRepairs()">
                    
                    <fieldset>
                        <div class="grid-x grid-padding-x ">
                            <div class="large-1 cell">
                                <label for="middle-label" class="text-right middle">Buyer</label>
                            </div>
                            <div class="large-5 cell">
                                <?php 
                                    $sql = "SELECT BuyerID, FirstName, LastName FROM Buyer ORDER BY LastName";
                                    $result = mysqli_query($conn, $sql);

                                    echo "<select name='buyerID'>";
                                    while ($row = $result->fetch_assoc()) {
                                        $BuyerID = $row['BuyerID'];
                                        $FirstName = $row['FirstName'];
                                        $LastName = $row['LastName'];
                                        echo '<option value="'.$BuyerID.'">' .$LastName.', ' .$FirstName. '</option>';
                                    }
                                    echo "</select>";
                                ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <div class="grid-x grid-padding-x ">    
                        <div class="large-12 cell">
                            <hr>
                        </div>

                        <div class="large-1 cell">
                            <label for="date" class="text-right middle">Date</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="date" 
                                   name="date" 
                                   min="1900-01-01"
                                   max="<?php echo date("Y"); ?>-<?php echo date("m"); ?>-<?php echo date("d"); ?>"
                                   required>
                        </div>

                        <div class="large-1 cell">
                            <label for="auction" class="text-right middle">Auction</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="checkbox" 
                                   name="auction">
                        </div>

                        <div class="large-1 cell">
                            <label for="seller" class="text-right middle">Seller</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="text" 
                                   name="seller" 
                                   placeholder="John Doe" 
                                   maxlength="50" required>
                        </div>

                        <div class="large-1 cell">
                            <label for="location" class="text-right middle">Location</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="text" 
                                   name="location" 
                                   placeholder="National Auto Outlet" 
                                   maxlength="50" required>
                        </div>
                    </div>
                        
                    <div class="vehicleTemplate">
                        <div class="grid-x grid-padding-x align-middle">
                            <div class="large-12 cell">
                                    <hr>
                            </div>
                            <div class="large-1 cell">
                                <label for="year" class="text-right middle">Year</label>
                            </div>
                            <div class="large-5 cell">
                                <input type="number" 
                                       name="year[]" 
                                       min="1900"
                                       max="<?php echo date("Y"); ?>" 
                                       oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                       maxlength="4"
                                       placeholder="2008" required>
                            </div>
                            <div class="large-1 cell">
                                <label for="make" class="text-right middle">Make</label>
                            </div>
                            <div class="large-5 cell">
                                <input type="text" 
                                       name="make[]" 
                                       placeholder="Volkswagen"  
                                       maxlength="50"
                                       required>
                            </div>

                            <div class="large-1 cell">
                                <label for="model" class="text-right middle">Model</label>
                            </div>
                            <div class="large-5 cell">
                                <input type="text" 
                                       name="model[]" 
                                       placeholder="Golf" 
                                       maxlength="50"
                                       required>
                            </div>
                            <div class="large-1 cell">
                                <label for="mileage" class="text-right middle">Mileage</label>
                            </div>
                            <div class="large-5 cell">
                                <div class="input-group">
                                    <input class="input-group-field" 
                                           type="number" 
                                           name="mileage[]" 
                                           placeholder="15000" 
                                           oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                           maxlength='7'
                                           required>
                                    <span class="input-group-label">km</span>
                                </div>
                            </div>
                            <div class="large-1 cell">
                                <label for="color" class="text-right middle">Color</label>
                            </div>
                            <div class="large-5 cell">
                                <input type="text" 
                                       name="color[]" 
                                       placeholder="Red" 
                                       maxlength="25"
                                       required>
                            </div>
                            <div class="large-1 cell">
                                <label for="interiorColor" class="text-right middle">Interior color</label>
                            </div>
                            <div class="large-5 cell">
                                <input type="text" 
                                       name="interiorColor[]" 
                                       placeholder="Tan"
                                       maxlength="25"
                                       required>
                            </div>
                            <div class="large-1 cell">
                                <label for="style" class="text-right middle">Style</label>
                            </div>
                            <div class="large-5 cell">
                                <select name="style[]">
                                    <option value="Convertible">Convertible</option>
                                    <option value="Coupe">Coupe</option>
                                    <option value="Crossover">Crossover</option>
                                    <option value="Hatchback">Hatchback</option>
                                    <option value="MPV">MPV</option>
                                    <option value="Sedan">Sedan</option>
                                    <option value="SUV">SUV</option>
                                    <option value="Station-Wagon">Station wagon</option>
                                    <option value="Truck">Truck</option>
                                    <option value="Van">Van</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>  
                            <div class="large-1 cell">
                                <label for="condition" class="text-right middle">Condition</label>
                            </div>
                            <div class="large-5 cell">
                                <input type="text" 
                                       name="condition[]" 
                                       placeholder="Mint" 
                                       maxlength='20'
                                       required>
                            </div>
                            <div class="large-1 cell">
                                <label for="bookPrice" class="text-right middle">Book price</label>
                            </div>
                            <div class="large-5 cell">
                                <div class="input-group">
                                    <span class="input-group-label">$</span>
                                    <input class="input-group-field" 
                                           name="bookPrice[]" 
                                           type="number" 
                                           placeholder="3549.00" 
                                           oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                           maxlength='9'
                                           required>
                                </div>
                            </div>
                            <div class="large-1 cell">
                                <label for="pricePaid" class="text-right middle">Price paid</label>
                            </div>
                            <div class="large-5 cell">
                                <div class="input-group">
                                    <span class="input-group-label">$</span>
                                    <input class="input-group-field" 
                                           name="pricePaid[]" 
                                           type="number" 
                                           placeholder="2950.00" 
                                           oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                           maxlength='9'
                                           required>
                                </div>
                            </div>
                        </div>  

                        <!-- REPAIR TEMPLATE -->
                        <div class="repairTemplate grid-x grid-padding-x align-middle">
                            <div class="large-12 cell">
                                <hr>
                            </div>                

                            <div class="large-1 cell">
                                <label for="estCost" class="text-right middle">Est. cost</label>
                            </div>
                            <div class="large-11 cell">
                                <div class="input-group">
                                    <span class="input-group-label">$</span>
                                    <input class="input-group-field" name="estCost[]" type="number" placeholder="400.00">
                                </div>
                            </div>

                            <div class="large-1 cell">
                                <label for="problem" class="text-right middle">Problem</label>
                            </div>                        
                            <div class="large-11 cell">
                                <textarea name="problem[]" placeholder="The problem is..."></textarea>
                            </div>
                        </div>     


                        <div id="addRepairDiv" class="grid-x grid-padding-x">
                            <!--ADD REPAIR BUTTON-->
                            <div class="large-12 cell">
                                <input type="button" class="button float-right" id="addRepairButton" name="addRepairButton" value="Add repair" />
                            </div>
                        </div>

                    </div><!--/vehicletemplate-->
                    
                    <div id="addVehicleDiv" class="grid-x grid-padding-x">
                        <!--ADD VEHICLE BUTTON-->
                        <div class="large-12 cell">
                            <input type="button" class="button float-right" id="addVehicleButton" name="addVehicleButton" value="Add vehicle" />
                        </div>
                    </div>
                        
                    <div class="grid-x grid-padding-x">
                        <div class="large-12 cell">
                            <input type="submit" class="button float-right" id="purchaseVehicle" name="purchaseVehicle" value="Complete purchase">
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
        
        <!-- JQUERY FIRST -->
		<script type="text/javascript" src="js/vendor/jquery.js"></script>
        <script type="text/javascript" >
            
            function countRepairs()
            {
                var vehicles = document.getElementsByClassName("vehicleTemplate");
                for(i = 0; i < vehicles.length; i++)
                {
                    var curVehicle = vehicles[i];
                    var repairs = curVehicle.getElementsByClassName("repairTemplate").length;
                    var para = document.createElement("input");
                    para.setAttribute("name","numRepairs[]");
                    para.setAttribute("type","text");
                    para.setAttribute("style","display:none");
                    para.setAttribute("value", repairs.toString());
                    curVehicle.appendChild(para);
                }
            }
            
            jQuery(function($){
                var $vehicleButton = $('#addVehicleButton'),
                    $vehicleDiv = $('#addVehicleDiv'),
                    $vehicleRow = $('.vehicleTemplate:first').clone();

                $vehicleButton.click(function(){
                    $vehicleRow.clone().insertBefore( $vehicleDiv );
                    var $lowButtonIndex = ($("[id=addRepairButton]").length) - 1;
                    var $lowButton = $("[id=addRepairButton]:eq(" + $lowButtonIndex + ")");
                    $lowButton.click(function(){
                        $repairRow = $('.repairTemplate:first').clone();
                        $repairRow.clone().insertBefore( $lowButton ).find("input,textarea").val("");
                    });
                });
                
                var $repairButton = $('#addRepairButton'),
                    $repairDiv = $('#addRepairDiv'),
                    $repairRow = $('.repairTemplate:first').clone();

                $repairButton.click(function(){
                    $repairRow.clone().insertBefore( $repairDiv );
                });
            });
            
            $(document).ready(function(){                
                $(function() {
                    $("includeVehicleTemplate").load("vehicletemplate.php");
                    $("includeRepairTemplate").load("repairtemplate.php");

                });
            });
        </script>
		
		<!-- OTHER SCRIPTS -->
		
		<script type="text/javascript" src="js/vendor/foundation.min.js"></script>
		<script type="text/javascript" src="js/app.js"></script>		
	</body>
</html>