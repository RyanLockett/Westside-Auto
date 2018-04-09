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
		<title>Westside Auto Inc. | Salesperson</title>
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
                    <li><a href="purchase.php">Purchase</a></li>
                    <li><a href="vehicle.php">Vehicle</a></li>
                    <li><a href="warrantyitem.php">Warranty Item</a></li>
                    <li class="active"><a href="salesperson.php">Salesperson</a></li>
                    <li><a href="sale.php">Sale</a></li>
                    <li><a href="customer.php">Customer</a></li>
                </ul>
            </div>
        </div>
        
        <div class="form">
            <div class="grid-container">
                
                <div class="large-12 cell">
                    <div class="title">Add a new salesperson</div>
                    <div class="description">Use this form to create a new salesperson.</div>
                </div>

                <?php
                    if(isset($_POST['addSalesperson'])){

                        $firstName = $_POST['firstName'];
                        $lastName = $_POST['lastName'];
                        $phone = $_POST['phone'];

                        $stmt = $conn->prepare("INSERT INTO Salesperson (FirstName, LastName, Phone) VALUES (?, ?, ?)");
                        $stmt->bind_param("ssi", $firstName, $lastName, $phone);

                        $stmt->execute();

                        if($stmt->affected_rows === 0) {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border alert">
                            <strong>Boo!</strong> - It broke!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div></div>';
                        } else {
                            echo '<div class="large-12 cell "><div data-closable class="callout alert-callout-border success">
                            <strong>Yay!</strong> - You added a new salesperson!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div></div>';
                            }

                        $stmt->close();
                    }
                ?>
                
                <form class="data" action="salesperson.php" method="post">
                    
                    <div class="grid-x grid-padding-x align-middle">    

                        <div class="small-3 medium-2 large-1 cell">
                            <label for="firstName" class="text-right middle">First name</label>
                        </div>
                        <div class="small-9 medium-4 large-5 cell">
                            <input type="text" 
                                   name="firstName" 
                                   id="firstName" 
                                   maxlength="25"
                                   placeholder="John" 
                                   required>
                        </div>

                        <div class="small-3 medium-2 large-1 cell">
                            <label for="lastName" class="text-right middle">Last name</label>
                        </div>
                        <div class="small-9 medium-4 large-5 cell">
                            <input type="text" 
                                   name="lastName" 
                                   id="lastName" 
                                   maxlength="25"
                                   placeholder="Doe" 
                                   required>
                        </div>

                        <div class="small-3 medium-2 large-1 cell">
                            <label for="phone" class="text-right middle">Phone</label>
                        </div>
                        <div class="small-9 medium-4 large-5 cell">
                            <input type="number" 
                                   name="phone" 
                                   placeholder="14031234567" 
                                   pattern="^[0-9]*$" 
                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                   maxlength="11" 
                                   required>
                        </div>


                    </div>
                        
                    <div class="grid-x grid-padding-x">
                        <div class="large-12 cell">
                            <input type="submit" class="button float-right" id="addSalesperson" name="addSalesperson" value="Create salesperon">
                        </div>
                    </div>
                    
                </form>                
            </div>
        </div>
        
        <!-- JQUERY FIRST -->
		
		<script type="text/javascript" src="js/vendor/jquery.js"></script>
		
		<!-- OTHER SCRIPTS -->
		
		<script type="text/javascript" src="js/vendor/foundation.min.js"></script>
        <script src="js/foundation/foundation.alert.js"></script>
		<script type="text/javascript" src="js/app.js"></script>		
	</body>
</html>

<?php 
    $conn->close();
?>