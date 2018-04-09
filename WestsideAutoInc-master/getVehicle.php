<?php
    $q = intval($_GET['q']);
    $conn = new mysqli("localhost", "root", "", "WestsideAutoIncDB");

    $vehiclesql="SELECT * FROM Vehicle NATURAL JOIN Purchase NATURAL JOIN Buyer WHERE VehicleID = '".$q."'";
    $result = mysqli_query($conn, $vehiclesql);    

    /* CUSTOMER INPUTS BASED ON THE SELECTION FROM THE CUSTOMER TABLE */

    while($row = mysqli_fetch_array($result)) {
        echo "<form class='data' action='vehicle.php?q=" . $q . "' method='post'>
            <div class='grid-x grid-padding-x align-middle'>    
                <div class='large-12 cell'>
                    <hr>
                </div>
                
                <div class='large-6 cell'>
                    <h5>" . $row['Year'] . " " . $row['Make'] . " " . $row['Model'] . ", " . $row['Color'] . "<h5>
                </div>
                
                <div class='large-6 cell'>
                    <input type='hidden' id='q' name='q' value=" . $q . ">
                    <input type='submit' class='button float-right' id='updateVehicle' name='updateVehicle' value='Update vehicle info'>
                </div>

                <div class='large-1 cell'>
                    <label for='year' class='text-right middle'>Year</label>
                </div>
                <div class='large-5 cell'>
                    <input type='number' 
                           name='year' 
                           id='year' 
                           placeholder='2008'
                           oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                           maxlength='4'
                           value='" . $row['Year'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='make' class='text-right middle'>Make</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='make' 
                           id='make' 
                           maxlength='50'
                           placeholder='Volkswagen'
                           value='" . $row['Make'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='model' class='text-right middle'>Model</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='model' 
                           id='model' 
                           maxlength='50'
                           placeholder='Golf'
                           value='" . $row['Model'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='mileage' class='text-right middle'>Mileage</label>
                </div>
                <div class='large-5 cell'>
                    <div class='input-group'>
                        <input class='input-group-field' 
                               type='number'
                               name='mileage' 
                               placeholder='15000'
                               oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                               maxlength='7'
                               value='" . $row['Mileage'] . "'
                               required>
                        <span class='input-group-label'>km</span>
                    </div>
                </div>

                <div class='large-1 cell'>
                    <label for='color' class='text-right middle'>Color</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='color'
                           placeholder='Red' 
                           maxlength='25'
                           value='" . $row['Color'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='interiorColor' class='text-right middle'>Interior color</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='interiorColor'
                           placeholder='Tan' 
                           maxlength='25'
                           value='" . $row['InteriorColor'] . "'
                           required>
                </div>

                <div class='large-1 cell'>
                    <label for='style' class='text-right middle'>Style</label>
                </div>
                <div class='large-5 cell'>
                    <select name='style'>
                        <option value='Convertible'>Convertible</option>
                        <option value='Coupe'>Coupe</option>
                        <option value='Crossover'>Crossover</option>
                        <option value='Hatchback'>Hatchback</option>
                        <option value='MPV'>MPV</option>
                        <option value='Sedan'>Sedan</option>
                        <option value='SUV'>SUV</option>
                        <option value='Station-Wagon'>Station wagon</option>
                        <option value='Truck'>Truck</option>
                        <option value='Van'>Van</option>
                        <option value='Other'>Other</option>
                    </select>
                </div>  
                
                <div class='large-1 cell'>
                    <label for='condition' class='text-right middle'>Condition</label>
                </div>
                <div class='large-5 cell'>
                    <input type='text' 
                           name='condition'
                           placeholder='Mint' 
                           maxlength='20'
                           value='" . $row['Condition'] . "'
                           required>
                </div>
                
                <div class='large-1 cell'>
                    <label for='bookPrice' class='text-right middle'>Book price</label>
                </div>
                <div class='large-5 cell'>
                    <div class='input-group'>
                        <span class='input-group-label'>$</span>
                        <input class='input-group-field'
                               name='bookPrice'
                               type='number'
                               oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                               maxlength='9'
                               placeholder='3549.00' 
                               value='" . $row['BookPrice'] . "'
                               required>
                    </div>
                </div>

                <div class='large-1 cell'>
                    <label for='pricePaid' class='text-right middle'>Price paid</label>
                </div>
                <div class='large-5 cell'>
                    <div class='input-group'>
                        <span class='input-group-label'>$</span>
                        <input class='input-group-field' 
                               name='pricePaid' 
                               type='number'
                               oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                               maxlength='9'
                               placeholder='2950.00'
                               value='" . $row['PricePaid'] . "'
                               required>
                    </div>
                </div>
                
                <div class='large-1 cell'>
                    <label for='listingPrice' class='text-right middle'>Listing price</label>
                </div>
                <div class='large-5 cell'>
                    <div class='input-group'>
                        <span class='input-group-label'>$</span>
                        <input class='input-group-field' 
                               name='listingPrice' 
                               type='number'
                               oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                               maxlength='9'
                               placeholder='5650.00'
                               value='" . $row['ListingPrice'] . "'
                               required>
                    </div>
                </div>
                
            </div>
        </form>
        
        <div class='grid-x grid-padding-x align-middle'>    
            <div class='large-12 cell'>
                <hr>
            </div>

            <div class='large-12 cell'>
                <h5>Purchase info<h5>
            </div>
            
            <div class='large-1 cell'>
                <label for='buyer' class='text-right middle'>Purchased by</label>
            </div>
            
            <div class='large-5 cell'>
                <input type='text' name='buyer' value='" . $row['LastName'] . ", " . $row['FirstName'] . "' disabled>
            </div>

            <div class='large-1 cell'>
                <label for='date' class='text-right middle'>Date</label>
            </div>
            <div class='large-2 cell'>
                <input type='date' name='date' value='" . $row['Date'] . "' disabled>
            </div>

            <div class='large-1 cell'>
                <label for='auction' class='text-right middle'>Auction</label>
            </div>
            <div class='large-2 cell'>";
            
            if ($row['IsAuction'] == 1)
                echo "<input type='checkbox' name='auction' checked disabled>";
            else
                echo "<input type='checkbox' name='auction' disabled>";
        
            echo "</div>

            <div class='large-1 cell'>
                <label for='seller' class='text-right middle'>Seller</label>
            </div>
            <div class='large-5 cell'>
                <input type='text' name='seller' placeholder='John Doe' value='" . $row['Seller'] . "'disabled>
            </div>

            <div class='large-1 cell'>
                <label for='location' class='text-right middle'>Location</label>
            </div>
            <div class='large-5 cell'>
                <input type='text' name='location' placeholder='National Auto Outlet' value='" . $row['Location'] . "'disabled>
            </div>
        </div>";
    }

    $repairsql="SELECT * FROM Repair WHERE VehicleID = '".$q."'";
    $repairresult = mysqli_query($conn, $repairsql); 
    $repairNo = 1;

    while($repairrow = mysqli_fetch_array($repairresult)) {
        echo "<form class='data' action='vehicle.php?q=" . $q . "' method='post'>
                <div class='repairTemplate grid-x grid-padding-x align-middle'>
                    <div class='large-12 cell'>
                        <hr>
                    </div> 

                    <div class='large-6 cell'>
                        <h5>Repair #" . $repairNo . "<h5>
                    </div>

                    <div class='large-6 cell'>
                        <input type='hidden' id='q' name='q' value='" . $q . "'>
                        <input type='hidden' id='r' name='r' value='" . $repairrow['RepairID'] . "'>
                        <input type='submit' class='button float-right' id='updateRepair' name='updateRepair' value='Update repair #" . $repairNo . "'>
                    </div>

                    <div class='large-1 cell'>
                        <label for='estCost' class='text-right middle'>Est. cost</label>
                    </div>
                    <div class='large-5 cell'>
                        <div class='input-group'>
                            <span class='input-group-label'>$</span>
                            <input class='input-group-field' 
                                   name='estCost' 
                                   type='number' 
                                   oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                   maxlength='9'
                                   placeholder='400.00' 
                                   value='" . $repairrow['EstCost'] . "'
                                   disabled>
                        </div>
                    </div>

                    <div class='large-1 cell'>
                        <label for='actualCost' class='text-right middle'>Actual cost</label>
                    </div>
                    <div class='large-5 cell'>
                        <div class='input-group'>
                            <span class='input-group-label'>$</span>
                            <input class='input-group-field' 
                                   name='actualCost' 
                                   type='number' 
                                   oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'
                                   maxlength='9'
                                   value='" . $repairrow['ActualCost'] . "'
                                   placeholder='300.00' 
                                   required>
                        </div>
                    </div>

                    <div class='large-1 cell'>
                        <label for='problem' class='text-right middle'>Problem</label>
                    </div>                        
                    <div class='large-11 cell'>
                        <textarea name='problem' 
                                  placeholder='The problem is...' 
                                  maxlength='200'
                                  required>" . $repairrow['Problem'] . "</textarea>
                    </div>
                </div>
            </form>";
        $repairNo++;
    }
    
    $warrantysql="SELECT * FROM coverage NATURAL JOIN warrantyitem WHERE SaleID = (SELECT SaleID FROM sale WHERE VehicleID = ".$q.")";
    if (!$warrantyresult = mysqli_query($conn, $warrantysql))
      {
        echo("Error description: " . mysqli_error($conn));
      }

    $warrantyNo = 1;

    while($warrantyrow = mysqli_fetch_array($warrantyresult)) {
        echo "<form class='data' action='vehicle.php' method='post'>
                <div class='warrantyTemplate grid-x grid-padding-x align-middle'>
                    <div class='large-12 cell'>
                        <hr>
                    </div> 

                    <div class='large-6 cell'>
                        <h5>Warranty #" . $warrantyNo . "<h5>
                    </div>

                    <div class='large-6 cell'>
                        <input type='hidden' id='q' name='q' value='" . $q . "'>
                        <input type='hidden' id='r' name='r' value='" . $warrantyrow['CoverageID'] . "'>
                     <!--    <input type='submit' class='button float-right' id='updateWarranty' name='updateWarranty' value='Update repair #" . $warrantyNo . "'> -->
                    </div>

                    <div class='large-1 cell'>
                        <label for='type' class='text-right middle'>Type</label>
                    </div>
                    <div class='large-5 cell'>
                        <div class='input-group'>
                            <input class='input-group-field' 
                                   name='type' 
                                   type='text' 
                                   value='" . $warrantyrow['Type'] . "'
                                   placeholder='--' 
                                   readonly>
                        </div>
                    </div>
                    <div class='large-1 cell'>
                        <label for='enddate' class='text-right middle'>End date</label>
                    </div>
                    <div class='large-5 cell'>
                        <div class='input-group'>
                            <input class='input-group-field' 
                                   name='enddate' 
                                   type='date' 
                                   value='" . $warrantyrow['EndDate'] . "'
                                   placeholder='--' 
                                   readonly>
                        </div>
                    </div>
                    <div class='large-1 cell'>
                        <label for='cost' class='text-right middle'>Cost</label>
                    </div>
                    <div class='large-5 cell'>
                        <div class='input-group'>
                            <span class='input-group-label'>$</span>
                            <input class='input-group-field' 
                                   name='cost' 
                                   type='number' 
                                   value='" . $warrantyrow['Cost'] . "'
                                   placeholder='--' 
                                   readonly>
                        </div>
                    </div>
                    
                    <div class='large-1 cell'>
                        <label for='deductible' class='text-right middle'>Deductible</label>
                    </div>
                    <div class='large-5 cell'>
                        <div class='input-group'>
                            <span class='input-group-label'>$</span>
                            <input class='input-group-field' 
                                   name='deductible' 
                                   type='number' 
                                   value='" . $warrantyrow['Deductible'] . "'
                                   placeholder='--' 
                                   readonly>
                        </div>
                    </div>
                    <div class='large-1 cell'>
                        <label for='description' class='text-right middle'>Description</label>
                    </div>                        
                    <div class='large-11 cell'>
                        <textarea name='description' 
                                  maxlength='200'
                                  readonly>" . $warrantyrow['Description'] . "</textarea>
                    </div>
                </div>
            </form>";
        $warrantyNo++;
    }
    

    mysqli_close($conn);
?>               