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
		<title>Westside Auto Inc. | New Payment</title>
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

		<!-- CSS -->
		
		<link rel="stylesheet" href="../css/app.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<link href="../css/foundation.min.css" rel="stylesheet" type="text/css" />
	</head>
	
	<body>
        
        <!-- NAVIGATION -->
        
        <div class="grid-x">
            <div class="cell">
                <ul class="menu align-right menu-hover-lines">
                    <li><a href="/">Home</a></li>
                    <li><a href="../buyer.php">Buyer</a></li>
                    <li><a href="../purchase.php">Purchase</a></li>
                    <li><a href="../vehicle.php">Vehicle</a></li>
                    <li><a href="../warrantyitem.php">Warranty Item</a></li>
                    <li><a href="../salesperson.php">Salesperson</a></li>
                    <li><a href="../sale.php">Sale</a></li>
                    <li class="active"><a href="../customer.php">Customer</a></li>
                </ul>
            </div>
        </div>
        
        <div class="form">
            <div class="grid-container">
                
                <div class="large-12 cell">
                        <?php 
                            $customerID = intval($_GET['q']);
                            $sql = "SELECT FirstName, LastName FROM Customer WHERE CustomerID = ".$customerID;
                            $result = mysqli_query($conn, $sql);
                            while ($row = $result->fetch_assoc()) {
                                $FirstName = $row['FirstName'];
                                $LastName = $row['LastName'];
                                echo '<div class="title">Add a new payment for '.$FirstName.'</div>';
                                echo '<div class="description">Use this form to add a new payment for '.$FirstName.' '.$LastName.'.</div>';
                            }
                        ?>
                </div>

                <?php
                    if(isset($_POST['addPayment'])){
                        $customerID = $_POST['q'];
                        $expectedDate = $_POST['expectedDate'];
                        $paidDate = $_POST['paidDate'];
                        $amountDue = $_POST['amountDue'];
                        $amountPaid = $_POST['amountPaid'];
                        $bankAccount = $_POST['bankAccount'];

                        $stmt = $conn->prepare("INSERT INTO Payment (CustomerID, ExpectedDate, PaidDate, AmountDue, AmountPaid, BankAccount) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("issddi", $customerID, $expectedDate, $paidDate, $amountDue, $amountPaid, $bankAccount);

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
                            <strong>Yay!</strong> - You added a new payment! <a href="../customer.php?q='.$customerID.'#customer">Click here to go back to the customer screen.</a>
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div></div>';
                            }

                        $stmt->close();
                    }
                ?>
                
                
                <form class="data" action="newpayment.php?q=<?php 
                    $customerID = intval($_GET['q']);
                    echo $customerID;
                ?>" method="post">
                
                    <div class="grid-x grid-padding-x align-middle">

                        <div class="large-1 cell align-middle">
                            <label for="expectedDate" class="text-right middle">Expected date</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="date" name="expectedDate">
                        </div>

                        <div class="large-1 cell">
                            <label for="paidDate" class="text-right middle">Paid date</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="date" name="paidDate">
                        </div>

                        <div class="large-1 cell">
                            <label for="amountDue" class="text-right middle">Amount due</label>
                        </div>
                        <div class="large-5 cell">
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field" name="amountDue" type="number" placeholder="3549.00">
                            </div>
                        </div>

                        <div class="large-1 cell">
                            <label for="amountPaid" class="text-right middle">Amount paid</label>
                        </div>
                        <div class="large-5 cell">
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field" name="amountPaid" type="number" placeholder="3549.00">
                            </div>
                        </div>

                        <div class="large-1 cell">
                            <label for="bankAccount" class="text-right middle">Bank account</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="number" name="bankAccount">
                        </div>

                        <div class="large-12 cell">
                            <?php 
                                $customerID = intval($_GET['q']);
                                echo '<input type="hidden" id="q" name="q" value="'.$customerID.'">';
                            ?>
                            <input type="submit" class="button float-right" id="addPayment" name="addPayment" value="Add payment">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- JQUERY FIRST -->
		
		<script type="text/javascript" src="../js/vendor/jquery.js"></script>
		
		<!-- OTHER SCRIPTS -->
		
		<script type="text/javascript" src="../js/vendor/foundation.min.js"></script>
        <script src="../js/foundation/foundation.alert.js"></script>
		<script type="text/javascript" src="../js/app.js"></script>		
	</body>
</html>

<?php 
    $conn->close();
?>