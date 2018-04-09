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
		<title>Westside Auto Inc. | Sale</title>
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

		<!-- CSS -->
		
		<link rel="stylesheet" href="css/app.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<link href="css/foundation.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.foundation.min.css">
	</head>
	
	<body>
        
        <!-- NAVIGATION -->
        
        <div class="grid-x">
            <div class="cell">
                <ul class="menu align-right menu-hover-lines">
                    <li><a href="/">Home</a></li>
                    <li><a href="buyer.php">Buyer</a></li>
                    <li><a href="purchase.php">Purchase</a></li>
                    <li><a href="vehicle.php">Vehicle</a></li>
                    <li><a href="warrantyitem.php">Warranty Item</a></li>
                    <li><a href="salesperson.php">Salesperson</a></li>
                    <li class="active"><a href="sale.php">Sale</a></li>
                    <li><a href="customer.php">Customer</a></li>
                </ul>
            </div>
        </div>
        
        <div class="form">
            <div class="grid-container">
                <div class="large-12 cell">
                    <div class="title">Sell a vehicle</div>
                    <div class="description">Use this form when selling a vehicle to a customer.</div>
                </div>
				  <?php
                    if(isset($_POST['submitSale'])){
                        
                        $vehicleID = $_POST['postVehicleID'];
                        $firstName = $_POST['firstName'];
                        $lastName = $_POST['lastName'];
						$gender = $_POST['gender'];
						$birthday = $_POST['birthday'];
						$taxID = $_POST['taxID'];
                        $phone = $_POST['phone'];
						$address = $_POST['address'];
						$city = $_POST['city'];
						$state = $_POST['state'];
						$zip = $_POST['zip'];
                        $date = date('Y-m-d');
						$EndDate = $_POST['EndDate'];
						$Cost = $_POST['WarrantyCost'];
						$Deductible = $_POST['Deductible'];
						$Commission = $_POST['Commission'];
						$DownPayment = $_POST['DownPayment'];
						$FinanceAmount = $_POST['FinanceAmount'];
						$TotalDue = $_POST['TotalDue'];
                        $postSalesmanID = $_POST['postSalesmanID'];
                        $warrantyItemID = $_POST['WarrantyItem'];
                        
                        $stmtCustomer = $conn->prepare("INSERT INTO Customer (FirstName, LastName, Gender, Birthday, TaxID, Phone, Address, City, State, Zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmtCustomer->bind_param("ssssiissss", $firstName, $lastName, $gender, $birthday, $taxID, $phone, $address, $city, $state, $zip);
						$stmtCustomer->execute();
						
                        
						$stmtSale = $conn->prepare("INSERT INTO Sale (CustomerID, VehicleID, SalespersonID, Commission, DownPayment, FinanceAmount, TotalDue, Date) VALUES((SELECT MAX(CustomerID) AS CustomerID FROM Customer), ?, ?, ?, ?, ?, ?, ?)");
						$stmtSale->bind_param("iidddds", $vehicleID, $postSalesmanID, $Commission, $DownPayment, $FinanceAmount, $TotalDue, $date);
                        $stmtSale->execute();						      
                        
                        for ($i = 0; $i < count($_POST['WarrantyItem']); $i++) 
                        {
                            if( $Deductible[$i] != '' && $Cost[$i] != '' && $EndDate[$i] != '')
                            {
                                $warrantyItemCur = $warrantyItemID[$i];
                                $EndDateCur = $EndDate[$i];
                                $CostCur = $Cost[$i];    
                                $DeductibleCur = $Deductible[$i];

                                $stmtCoverage = $conn->prepare("INSERT INTO Coverage (SaleID, WarrantyItemID, EndDate, Cost, Deductible) VALUES((SELECT MAX(SaleID) AS SaleID FROM Sale), ?, ?, ?, ?)");
                                $stmtCoverage->bind_param("isdd", $warrantyItemCur, $EndDateCur, $CostCur, $DeductibleCur);
                                $stmtCoverage->execute();
                            }
                        }
                        
                        if($stmtCustomer->affected_rows === -1 || $stmtSale->affected_rows === -1 && isset($stmtCoverage) && $stmtCoverage->affected_rows === -1) {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border alert">
                            <strong>Boo!</strong> - It broke!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div></div>';
                        } else {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border success">
                            <strong>Yay!</strong> - You sold a new vehicle!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div></div>';
                            }
                        
                        $stmtCustomer->close();
                        $stmtSale->close();
                        if(isset($stmtCoverage))
                            $stmtCoverage->close();    
                    }
					
                ?>
                
                <fieldset>
                <div class="grid-x grid-padding-x ">
                    <div class="large-1 cell">
                        <label for="middle-label" class="text-right middle">Salesperson</label>
                    </div>
                    <div class="large-5 cell">
                        <?php 
                            $sql = "SELECT SalespersonID, FirstName, LastName FROM Salesperson ORDER BY LastName";
                            $result = mysqli_query($conn, $sql);

                            echo "<select id='salespersonID' name='buyerID'>";
                            while ($row = $result->fetch_assoc()) {
                                $SalespersonID = $row['SalespersonID'];
                                $FirstName = $row['FirstName'];
                                $LastName = $row['LastName'];
                                echo '<option value="'.$SalespersonID.'">' .$LastName.', ' .$FirstName. '</option>';
                            }
                            echo "</select>";
                        ?>
                    </div>
                </div>
                    </fieldset>
                
                <!--CUSTOMER INFORMATION-->
                
                <form class="data" action="sale.php" method="post" onsubmit="addParams()">
					
                    <div class="grid-x grid-padding-x align-middle">    
                        <div class="large-12 cell">
                            <hr>
                        </div>
                        
                        <div class="large-12 cell">
                            <h5> Customer information</h5>
                        </div>

                        <div class='large-1 cell'>
                            <label for='firstName' class='text-right middle'>First name</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='text' 
                                   name='firstName' 
                                   id='firstName' 
                                   placeholder='John'
                                   maxlength='50'
                                   required>
                        </div>

                        <div class='large-1 cell'>
                            <label for='lastName' class='text-right middle'>Last name</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='text' 
                                   name='lastName' 
                                   id='lastName' 
                                   placeholder='Doe'
                                   maxlength='50'
                                   required>
                        </div>

                        <div class='large-1 cell'>
                            <label for='gender' class='text-right middle'>Gender</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='text' 
                                   name='gender' 
                                   id='gender' 
                                   placeholder='Male'
                                   maxlength='20'
                                   required>
                        </div>

                        <div class='large-1 cell'>
                            <label for='birthday' class='text-right middle'>Birthday</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='date' 
                                   min='1900-01-01'
                                   max='<?php echo date("Y"); ?>-<?php echo date("m"); ?>-<?php echo date("d"); ?>'
                                   name='birthday' 
                                   required>
                        </div>

                        <div class='large-1 cell'>
                            <label for='taxID' class='text-right middle'>Tax ID</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='number' 
                                   name='taxID'
                                   placeholder='1234567890' 
                                   oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                   maxlength='10'
                                   required>
                        </div>

                        <div class='large-1 cell'>
                            <label for='phone' class='text-right middle'>Phone</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='text' 
                                   name='phone' 
                                   id='phone' 
                                   placeholder='14031234567'
                                   oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                   maxlength='11'
                                   required>
                        </div>

                        <div class='large-1 cell'>
                            <label for='address' class='text-right middle'>Address</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='text' 
                                   name='address' 
                                   id='address' 
                                   placeholder='123 Center Street SE'
                                   maxlength='50'
                                   required>
                        </div>

                        <div class='large-1 cell'>
                            <label for='city' class='text-right middle'>City</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='text' 
                                   name='city' 
                                   id='city' 
                                   placeholder='Calgary'
                                   maxlength='20'
                                   required>
                        </div>

                        <div class='large-1 cell'>
                            <label for='state' class='text-right middle'>State</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='text' 
                                   name='state' 
                                   id='state' 
                                   placeholder='Alberta'
                                   maxlength='20'
                                   required>
                        </div>

                        <div class='large-1 cell'>
                            <label for='zip' class='text-right middle'>ZIP code</label>
                        </div>
                        <div class='large-5 cell'>
                            <input type='text' 
                                   name='zip' 
                                   id='zip' 
                                   maxlength='6'
                                   placeholder='T1K4G3'
                                   required>
                        </div>
                    </div>
                    
                    <!--TABLE OF VEHICLES-->
                    
                    <div class="grid-x grid-padding-x">
                        <div class="large-12 cell">
                            <hr>
                        </div>
                        <div class="large-12 cell">
                            <h5>Vehicle selection</h5>
                        </div>
                        <div class="large-12 cell">
                            <table id="customerTable" class="display">
                                <thead>
                                    <tr>
                                        <th>Make</th>
                                        <th>Mode</th>
                                        <th>Year</th>
                                        <th>Color</th>
                                        <th>Mileage</th>
                                        <th>Style</th>
                                        <th>Interior Color</th>
                                        <th>Listing Price</th>
                                        <th>Selected</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sql = "SELECT VehicleID, Make, Model, Year, Color, Mileage, Style, InteriorColor, IsSold, ListingPrice FROM Vehicle WHERE IsSold = 0 AND ListingPrice IS NOT NULL";
                                        $result = mysqli_query($conn, $sql);
                                        while ($row = $result->fetch_assoc()) {
                                            $VehicleID = $row['VehicleID'];
                                            $Make = $row['Make'];
                                            $Model= $row['Model'];
                                            $Year = $row['Year'];
                                            $Color = $row['Color'];
                                            $Mileage = $row['Mileage'];
                                            $Style = $row['Style'];
                                            $InteriorColor = $row['InteriorColor'];
                                            $ListingPrice = $row['ListingPrice'];
                                            echo '<tr>';
                                            echo '<td>'.$Make.'</td>';
                                            echo '<td>'.$Model.'</td>';
                                            echo '<td>'.$Year.'</td>';
                                            echo '<td>'.$Color.'</td>';
                                            echo '<td>'.$Mileage.'</td>';
                                            echo '<td>'.$Style.'</td>';
                                            echo '<td>'.$InteriorColor.'</td>';
                                            echo '<td>$'.$ListingPrice.'</td>';		
                                            echo '<td><input type="radio" id="VehicleID" value="'. $VehicleID.',' . $ListingPrice . '" name="VehicleID" onchange="changeTotal()"></td>';	
                                            echo '</tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!--WARRANTY-->
                    
                    <div class="warrantyTemplate grid-x grid-padding-x align-middle"> 
                        <div class="large-12 cell">
                            <hr>
                        </div>
                        
                        <div class="large-1 cell">
                            <label for="WarrantyItem" class="text-right middle">Warranty type</label>
                        </div>
                        <div class="large-5 cell">
                            <?php 
                                $sql = "SELECT WarrantyItemID, Type FROM WarrantyItem ORDER BY Type";
                                $result = mysqli_query($conn, $sql);

                                echo "<select name='WarrantyItem[]'>";
                                while ($row = $result->fetch_assoc()) {
                                    $WarrantyItemID = $row['WarrantyItemID'];
                                    $Type = $row['Type'];
                                    echo '<option value="'.$WarrantyItemID.'">' .$Type.'</option>';
                                }
                                echo "</select>";
                            ?>			
                        </div>
                        
                        <div class="large-1 cell">
                            <label for="model" class="text-right middle">End date</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="date"
                                   min="1900-01-01"
                                   name="EndDate[]">
                        </div>
                        
                        <div class="large-1 cell">
                            <label for="year" class="text-right middle">Cost</label>
                        </div>
                        <div class="large-5 cell">
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field WarrantyCost" 
                                       type="number" 
                                       oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                       maxlength='9'
                                       onchange="changeTotal()"
                                       name="WarrantyCost[]"
                                       id="WarrantyCost" 
                                       placeholder="----.--">
                            </div>
                        </div>

                        <div class="large-1 cell">
                            <label for="style" class="text-right middle">Deductible</label>
                        </div>

                        <div class="large-5 cell">
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field"
                                       type="number"
                                       name="Deductible[]"
                                       oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                       maxlength='9'
                                       placeholder="----.--">
                            </div>
                        </div>
                        
                    </div>  
                    <div id="addWarrantyDiv" class ="grid-x grid-padding-x">    
                        <div class="large-12 cell">
                            <input type="button" class="button float-right" id="addWarrantyButton" name="addWarrantyButton" value="Add another warranty" />
                        </div>
                    </div>

                    <div class="finalizeTemplate grid-x grid-padding-x"> 
                        <div class="large-12 cell">
                            <hr>
                        </div>                
                        <div class="large-7 cell">
                            <label for="Commission" class="text-right middle">Commission</label>
                        </div>
                        <div class="large-5 cell">
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field" 
                                       type="number" 
                                       name="Commission" 
                                       oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                       maxlength='9'
                                       onchange="changeTotal()"
                                       placeholder="----.--"
                                       id="Commission"
                                       required>
                            </div>
                        </div>
                        
                        <div class="large-7 cell">
                            <label for="DownPayment" class="text-right middle">Down payment</label>
                        </div>
                        <div class="large-5 cell">
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field" 
                                       type="number" 
                                       name="DownPayment" 
                                       oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                       maxlength='9'
                                       onchange="changeTotal()"
                                       placeholder="----.--" 
                                       id="DownPayment" 
                                       required>
                            </div>
                        </div>

                        <div class="large-7 cell">
                            <label for="FinanceAmount" class="text-right middle">Finance amount</label>
                        </div>
                        <div class="large-5 cell">
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field" 
                                       type="number"
                                       name="FinanceAmount"
                                       oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                       maxlength='9'
                                       placeholder="----.--"
                                       id="Finance"
                                       readonly>
                            </div>
                        </div>

                        <div class="large-7 cell">
                            <label for="TotalDue" class="text-right middle">Total cost</label>
                        </div>
                        <div class="large-5 cell">
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field" type="number" name="TotalDue" id="TotalDue" placeholder="----.--" readonly>
                            </div>
                        </div>
                        <div class="large 12 cell" id="bottom">
                            <input type="submit" class="button float-right" id="submitSale" name="submitSale" value="Submit sale">
                        </div>
                    </div><!--/finalizeTemplate-->
                </form>
            
            </div><!--/grid-container-->
        </div><!--/form-->
    
        <!-- JQUERY FIRST -->
		
		<script type="text/javascript" src="js/vendor/jquery.js"></script>
        <script type="text/javascript" >
            jQuery(function($){
                var $warrantyButton = $('#addWarrantyButton'),
                    $warrantyDiv = $('#addWarrantyDiv'),
                    $warrantyRow = $('.warrantyTemplate:first').clone();

                $warrantyButton.click(function(){
                    $warrantyRow.clone().insertBefore( $warrantyDiv ).find("input").val("");
                });
            });
        </script>
		
		<!-- OTHER SCRIPTS -->
		
		<script type="text/javascript" src="js/vendor/foundation.min.js"></script>
		<script type="text/javascript" src="js/app.js"></script>		
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.foundation.min.js"></script>
        
		<script type="text/javascript"> 
            $(document).ready( function () {
                $('#customerTable').DataTable();
            });
               
            var radios = document.getElementsByName('VehicleID');

            function addParams()
            {
                    var para = document.createElement("input");
                    para.setAttribute("name","postVehicleID");
                    para.setAttribute("type","hidden");
                
                    var VehicleID;
                    for( i = 0; i < radios.length; i++ ) {
                        if( radios[i].checked ) {
                            VehicleID = radios[i].value.split(',');
                        }
                    }
                
                    para.setAttribute("value", VehicleID[0]);
                    document.getElementById("bottom").appendChild(para);
                
                    var para2 = document.createElement("input");
                    para2.setAttribute("name","postSalesmanID");
                    para2.setAttribute("type","hidden");
                
                    var id = document.getElementById("salespersonID");
                    var val = id.options[id.selectedIndex].value;
                
                    para2.setAttribute("value", val);
                    document.getElementById("bottom").appendChild(para2);
            }
            
            function changeTotal()
            {
                var WarrantyCost = document.getElementsByClassName('WarrantyCost'),
                Commission = document.getElementById('Commission'),
                DownPayment = document.getElementById('DownPayment'),
                Finance = document.getElementById('Finance');
                
                TotalCost = 0;
                var VehiclePrice;
                for( i = 0; i < radios.length; i++ ) {
                    if( radios[i].checked ) {
                        VehiclePrice = radios[i].value.split(',');
                    }
                }
                
                if(Commission.value !== "")
                    TotalCost+=parseFloat(Commission.value);
                
                for(var i = 0; i < WarrantyCost.length; i++)
                {
                    if(WarrantyCost[i].value !== "")
                        TotalCost+=parseFloat(WarrantyCost[i].value);
                }
                
                if(VehiclePrice !== undefined)
                    TotalCost+=parseFloat(VehiclePrice[1]);
                
                document.getElementById('TotalDue').value = parseFloat(TotalCost);
                
                if(TotalCost != 0 && DownPayment.value !== "")
                    Finance.value = TotalCost-parseFloat(DownPayment.value);
                else
                    Finance.value = "";
            }		
		</script>
    
	</body>
</html>

<?php 
    $conn->close();
?>