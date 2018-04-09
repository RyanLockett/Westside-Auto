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
		<title>Westside Auto Inc. | Warranty Item</title>
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
                    <li class="active"><a href="warrantyitem.php">Warranty Item</a></li>
                    <li><a href="salesperson.php">Salesperson</a></li>
                    <li><a href="sale.php">Sale</a></li>
                    <li><a href="customer.php">Customer</a></li>
                </ul>
            </div>
        </div>

        <div class="form">
            <div class="grid-container">
                
                <div class="large-12 cell">
                    <div class="title">Add a new warranty item</div>
                    <div class="description">Use this form to create a new warranty item.</div>
                </div>

                <?php
                    if(isset($_POST['addWarrantyItem'])){

                        $type = $_POST['type'];
                        $description = $_POST['description'];

                        $stmt = $conn->prepare("INSERT INTO WarrantyItem (Type, Description) VALUES (?, ?)");
                        $stmt->bind_param("ss", $type, $description);

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
                            <strong>Yay!</strong> - You added a new warranty item!
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div></div>';
                            }

                        $stmt->close();
                    }
                ?>
                
                <form class="data" action="warrantyitem.php" method="post">
                    
                    <div class="grid-x grid-padding-x align-middle">    

                        <div class="small-3 medium-2 large-1 cell">
                            <label for="firstName" class="text-right middle">Type</label>
                        </div>
                        <div class="small-9 medium-10 large-11 cell">
                            <input type="text" 
                                   name="type" 
                                   id="type" 
                                   maxlength="50"
                                   placeholder="Stereo" 
                                   required>
                        </div>

                        <div class="small-3 medium-2 large-1 cell">
                            <label for="description" class="text-right middle">Description</label>
                        </div>
                        <div class="small-9 medium-10 large-11 cell">
                            <textarea id="description" 
                                      name="description" 
                                      maxlength="200"
                                      placeholder="This warranty covers..." 
                                      required></textarea>
                        </div>
                        <div id="textarea_feedback" class="small-12 cell text-right"></div>

                    </div>
                        
                    <div class="grid-x grid-padding-x grid-padding-y">
                        <div class="large-12 cell">
                            <input type="submit" class="button float-right" id="addWarrantyItem" name="addWarrantyItem" value="Create warranty item">
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
        <script type="text/javascript">
            $(document).ready(function() {
                var text_max = 200;
                $('#textarea_feedback').html(text_max + ' characters remaining');

                $('#description').keyup(function() {
                    var text_length = $('#description').val().length;
                    var text_remaining = text_max - text_length;

                    $('#textarea_feedback').html(text_remaining + ' characters remaining');
                });
            });
        </script>
	</body>
</html>

<?php 
    $conn->close();
?>