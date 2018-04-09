<?php
    $q = intval($_GET['q']);
    $conn = new mysqli("localhost", "root", "", "WestsideAutoIncDB");

    $customersql="SELECT * FROM Customer WHERE CustomerID = '".$q."'";
    $result = mysqli_query($conn, $customersql);    

    /* CUSTOMER INPUTS BASED ON THE SELECTION FROM THE CUSTOMER TABLE */

    while($row = mysqli_fetch_array($result)) {
        echo "<form class='data' action='customer.php?q=" . $q . "' method='post'>
            <div class='grid-x grid-padding-x align-middle'>    
                <div class='large-12 cell'>
                    <hr>
                </div>
                
                <div class='large-6 cell'>
                    <h5>" . $row['FirstName'] . " " . $row['LastName'] . "<h5>
                </div>
                
                <div class='large-6 cell'>
                    <input type='hidden' id='q' name='q' value=" . $q . ">
                    <input type='submit' class='button float-right' id='updateCustomer' name='updateCustomer' value='Update'>
                </div>

                <div class='large-1 cell'>
                    <label for='firstName' class='text-right middle'>First name</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='firstName' 
                           id='firstName' 
                           maxlength='50'
                           placeholder='John'
                           value='" . $row['FirstName'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='lastName' class='text-right middle'>Last name</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='lastName' 
                           id='lastName' 
                           maxlength='50'
                           placeholder='Doe'
                           value='" . $row['LastName'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='gender' class='text-right middle'>Gender</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='gender' 
                           id='gender' 
                           maxlength='20'
                           placeholder='Male'
                           value='" . $row['Gender'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='birthday' class='text-right middle'>Birthday</label>
                </div>
                <div class='large-5 cell'>
                    <input type='date' 
                           name='birthday' 
                           min='1900-01-01'
                           max='" . date("Y") . "-" . date("m") . "-" . date("d") . "'
                           value='" . $row['Birthday'] . "'
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
                           value='" . $row['TaxID'] . "'
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
                           value='" . $row['Phone'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='address' class='text-right middle'>Address</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='address' 
                           id='address' 
                           maxlength='50'
                           placeholder='123 Center Street SE'
                           value='" . $row['Address'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='city' class='text-right middle'>City</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='city' 
                           id='city' 
                           maxlength='20'
                           placeholder='Calgary'
                           value='" . $row['City'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='state' class='text-right middle'>State</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='state' 
                           id='state' 
                           maxlength='20'
                           placeholder='Alberta'
                           value='" . $row['State'] . "'
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
                           value='" . $row['Zip'] . "'
                           required>
                </div>
            </div>
        </form>";
    }
    mysqli_close($conn);
?>

<!--VEHICLE TABLE-->
                
<div class="grid-x grid-padding-x align-middle">
    <div class="large-12 cell">
        <hr>
    </div>
    <div class="large-12 cell">
        <h5>Vehicles purchased</h5>
    </div>
    <div class="large-12 cell"> 
        <table id="vehicleTable" class="display">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Color</th>
                    <th>Mileage</th>
                    <th>Style</th>
                    <th>Interior Color</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $conn = new mysqli("localhost", "root", "", "WestsideAutoIncDB");
                    $sqlvehicle = "SELECT VehicleID, Make, Model, Year, Color, Mileage, Style, InteriorColor FROM Vehicle WHERE VehicleID IN (SELECT VehicleID FROM Sale WHERE CustomerID = ".$q.")";
                    $resultvehicle = mysqli_query($conn, $sqlvehicle);
                    while ($rowvehicle= $resultvehicle->fetch_assoc()) {
                        $VehicleID = $rowvehicle['VehicleID'];
                        $Make = $rowvehicle['Make'];
                        $Model= $rowvehicle['Model'];
                        $Year = $rowvehicle['Year'];
                        $Color = $rowvehicle['Color'];
                        $Mileage = $rowvehicle['Mileage'];
                        $Style = $rowvehicle['Style'];
                        $InteriorColor = $rowvehicle['InteriorColor'];
                        echo '<tr>';
                        echo '<td>'.$Year.'</td>';
                        echo '<td>'.$Make.'</td>';
                        echo '<td>'.$Model.'</td>';
                        echo '<td>'.$Color.'</td>';
                        echo '<td>'.$Mileage.'</td>';
                        echo '<td>'.$Style.'</td>';
                        echo '<td>'.$InteriorColor.'</td>';
//                        echo '<td><form action="vehicle.php?q='.$VehicleID.'" method="POST"><button type="button" class="float-right table-button">View</button>';
                        echo '<td><a href="vehicle.php?q='.$VehicleID.'"><button type="button" class="float-right table-button">View</button></a>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>		
    </div>
</div>

<!-- DIV FOR PAYMENT HISTORY BUTTON -->

<div class="grid-x grid-padding-x align-middle">
    <div class='large-12 cell'>
        <hr>
    </div>
    <div class="large-6 cell">
        <h5>Payment history</h5>
    </div>
    <div class="large-6 cell"> 
        <?php 
            echo '<a href="customer/newpayment.php?q='.$q.'"><button class="button float-right">New payment</button></a>';
        ?>
    </div>
</div>

<!-- PAYMENT HISTORY TABLE -->

<div class="grid-x grid-padding-x align-middle">
    <div class="large-12 cell"> 
        <table id="paymentTable" class="display">
            <thead>
                <tr>
                    <th>Expected Date</th>
                    <th>Paid Date</th>
                    <th>Amount Due</th>
                    <th>Amount Paid</th>
                    <th>Bank Account</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $conn = new mysqli("localhost", "root", "", "WestsideAutoIncDB");
                
                     echo "<div class='grid-x grid-padding-x align-middle'>";
                    
                    $sqlavgnodayslate = "SELECT ROUND(AVG(DateDifference),2) AS AvgDateDifference FROM (SELECT DATEDIFF(PaidDate, ExpectedDate) AS DateDifference FROM Payment WHERE CustomerID = '".$q."') AS DateDifferences";
                    $resultavgnodayslate = mysqli_query($conn, $sqlavgnodayslate);
                    while($row = $resultavgnodayslate->fetch_assoc()) {
                        if($row['AvgDateDifference'] === NULL) {
                            echo "<div class='large-1 cell'>
                                    <label for='avgNoDaysLate' class='text-right middle'>Avg no. days late</label>
                                </div>
                                <div class='large-5 cell'>
                                    <input type='number' id='avgNoDaysLate' name='avgNoDaysLate' value='0' disabled>
                                </div>";
                        } else {
                            echo "<div class='large-1 cell'>
                                    <label for='avgNoDaysLate' class='text-right middle'>Avg no. days late</label>
                                </div>
                                <div class='large-5 cell'>
                                    <input type='number' id='avgNoDaysLate' name='avgNoDaysLate' value='".$row['AvgDateDifference']."' disabled>
                                </div>";
                        }
                    }
                
                    $sqlnolatepayments = "SELECT COUNT(DateDifference) AS NoLatePayments FROM (SELECT DATEDIFF(PaidDate, ExpectedDate) AS DateDifference FROM Payment WHERE CustomerID = '".$q."') AS DateDifferences WHERE DateDifference > 0";
                    $resultnolatepayments = mysqli_query($conn, $sqlnolatepayments);
                    while($row = $resultnolatepayments->fetch_assoc()) {
                        echo "<div class='large-1 cell'>
                                    <label for='noLatePayments' class='text-right middle'>No. late payments</label>
                                </div>
                                <div class='large-5 cell'>
                                    <input type='number' id='noLatePayments' name='noLatePayments' value='".$row['NoLatePayments']."'disabled>
                                </div>
                            </div>";
                    }

                    $sqlpayment = "SELECT * FROM Payment WHERE CustomerID = '".$q."' ORDER BY PaymentID DESC";
                    $resultpayment = mysqli_query($conn, $sqlpayment);
                    
                    while ($row = $resultpayment->fetch_assoc()) {
                        $ExpectedDate = $row['ExpectedDate'];
                        $PaidDate = $row['PaidDate'];
                        $AmountDue = $row['AmountDue'];
                        $AmountPaid = $row['AmountPaid'];
                        $BankAccount = $row['BankAccount'];
                        echo '<tr>';
                        echo '<td>'.$ExpectedDate.'</td>';
                        echo '<td>'.$PaidDate.'</td>';
                        echo '<td>$'.$AmountDue.'</td>';
                        echo '<td>$'.$AmountPaid.'</td>';
                        echo '<td>'.$BankAccount.'</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>		
    </div>
</div>

<!-- DIV FOR EMPLOYMENT HISTORY BUTTON -->

<div class="grid-x grid-padding-x align-middle">
    <div class='large-12 cell'>
        <hr>
    </div>
    <div class="large-6 cell">
        <h5>Employment history</h5>
    </div>
    <div class="large-6 cell"> 
        <?php 
            echo '<a href="customer/newemploymenthistory.php?q='.$q.'"><button class="button float-right">New employment history</button></a>';
        ?>
    </div>
</div>

<!-- EMPLOYMENT HISTORY TABLE -->

<div class="grid-x grid-padding-x align-middle">
    <div class="large-12 cell float-bottom"> 
        <table id="employmentHistoryTable" class="display">
            <thead>
                <tr>
                    <th>Employer</th>
                    <th>Title</th>
                    <th>Supervisor</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Start Date</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $conn = new mysqli("localhost", "root", "", "WestsideAutoIncDB");
                    $sqlemployment = "SELECT * FROM EmploymentHistory WHERE CustomerID = '".$q."' ORDER BY EmploymentHistoryID DESC";
                    $result = mysqli_query($conn, $sqlemployment);
                    while ($row = $result->fetch_assoc()) {
                        $Employer = $row['Employer'];
                        $Title = $row['Title'];
                        $Supervisor = $row['Supervisor'];
                        $Phone = $row['Phone'];
                        $Address = $row['Address'];
                        $StartDate = $row['StartDate'];
                        echo '<tr>';
                        echo '<td>'.$Employer.'</td>';
                        echo '<td>'.$Title.'</td>';
                        echo '<td>'.$Supervisor.'</td>';
                        echo '<td>'.$Phone.'</td>';
                        echo '<td>'.$Address.'</td>';
                        echo '<td>'.$StartDate.'</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>		
    </div>
</div>