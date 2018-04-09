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
		<title>Westside Auto Inc. | New Employment History</title>
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
                                echo '<div class="title">Add employment history for '.$FirstName.'</div>';
                                echo '<div class="description">Use this form to add employment history for '.$FirstName.' '.$LastName.'.</div>';
                            }
                        ?>
                </div>

                <?php
                    if(isset($_POST['addEmploymentHistory'])){
                        $customerID = $_POST['q'];
                        $employer = $_POST['employer'];
                        $title = $_POST['title'];
                        $supervisor = $_POST['supervisor'];
                        $phone = $_POST['phone'];
                        $address = $_POST['address'];
                        $startDate = $_POST['startDate'];

                        $stmt = $conn->prepare("INSERT INTO EmploymentHistory (CustomerID, Employer, Title, Supervisor, Phone, Address, StartDate) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("isssiss", $customerID, $employer, $title, $supervisor, $phone, $address, $startDate);

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
                            <strong>Yay!</strong> - You added employment history! <a href="../customer.php?q='.$customerID.'#customer">Click here to go back to the customer screen.</a>
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div></div>';
                            }

                        $stmt->close();
                    }
                ?>
                
                
                <form class="data" action="newemploymenthistory.php?q=<?php 
                    $customerID = intval($_GET['q']);
                    echo $customerID;
                ?>" method="post">
                
                    <div class="grid-x grid-padding-x align-middle">

                        <div class="large-1 cell align-middle">
                            <label for="employer" class="text-right middle">Employer</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="text" name="employer">
                        </div>

                        <div class="large-1 cell">
                            <label for="title" class="text-right middle">Title</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="text" name="title">
                        </div>

                        <div class="large-1 cell">
                            <label for="supervisor" class="text-right middle">Supervisor</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="text" name="supervisor">
                        </div>

                        <div class="large-1 cell">
                            <label for="phone" class="text-right middle">Phone</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="number" name="phone">
                        </div>

                        <div class="large-1 cell">
                            <label for="address" class="text-right middle">Address</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="tel" name="address">
                        </div>

                        <div class="large-1 cell">
                            <label for="startDate" class="text-right middle">Start date</label>
                        </div>
                        <div class="large-5 cell">
                            <input type="date" name="startDate">
                        </div>

                        <div class="large-12 cell">
                            <?php 
                                $customerID = intval($_GET['q']);
                                echo '<input type="hidden" id="q" name="q" value="'.$customerID.'">';
                            ?>
                            <input type="submit" class="button float-right" id="addEmploymentHistory" name="addEmploymentHistory" value="Add employment history">
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