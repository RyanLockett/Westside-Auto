<div class="vehicleTemplate">
    <div class="grid-x grid-padding-x align-middle">
        <div class="large-12 cell">
                <hr>
        </div>
        <div class="large-1 cell">
            <label for="make" class="text-right middle">Make</label>
        </div>
        <div class="large-5 cell">
            <input type="text" name="make" placeholder="Volkswagen" required>
        </div>

        <div class="large-1 cell">
            <label for="model" class="text-right middle">Model</label>
        </div>
        <div class="large-5 cell">
            <input type="text" name="model" placeholder="Golf" required>
        </div>

        <div class="large-1 cell">
            <label for="year" class="text-right middle">Year</label>
        </div>
        <div class="large-5 cell">
            <input type="number" name="year" placeholder="2008" required>
        </div>

        <div class="large-1 cell">
            <label for="style" class="text-right middle">Style</label>
        </div>
        <div class="large-5 cell">
            <select name="style">
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
            <label for="color" class="text-right middle">Color</label>
        </div>
        <div class="large-5 cell">
            <input type="text" name="color" placeholder="Red" required>
        </div>

        <div class="large-1 cell">
            <label for="interiorColor" class="text-right middle">Interior color</label>
        </div>
        <div class="large-5 cell">
            <input type="text" name="interiorColor" placeholder="Tan" required>
        </div>

        <div class="large-1 cell">
            <label for="mileage" class="text-right middle">Mileage</label>
        </div>
        <div class="large-5 cell">
            <div class="input-group">
                <input class="input-group-field" type="number" name="mileage" placeholder="15000" required>
                <span class="input-group-label">km</span>
            </div>
        </div>

        <div class="large-1 cell">
            <label for="condition" class="text-right middle">Condition</label>
        </div>
        <div class="large-5 cell">
            <input type="text" name="condition" placeholder="Mint" required>
        </div>

        <div class="large-1 cell">
            <label for="bookPrice" class="text-right middle">Book price</label>
        </div>
        <div class="large-5 cell">
            <div class="input-group">
                <span class="input-group-label">$</span>
                <input class="input-group-field" name="bookPrice" type="number" placeholder="3549.00" required>
            </div>
        </div>

        <div class="large-1 cell">
            <label for="pricePaid" class="text-right middle">Price paid</label>
        </div>
        <div class="large-5 cell">
            <div class="input-group">
                <span class="input-group-label">$</span>
                <input class="input-group-field" name="pricePaid" type="number" placeholder="2950.00" required>
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
        <div class="large-5 cell">
            <div class="input-group">
                <span class="input-group-label">$</span>
                <input class="input-group-field" name="estCost" type="number" placeholder="400.00" required>
            </div>
        </div>

        <div class="large-1 cell">
            <label for="actualCost" class="text-right middle">Actual cost</label>
        </div>
        <div class="large-5 cell">
            <div class="input-group">
                <span class="input-group-label">$</span>
                <input class="input-group-field" name="actualCost" type="number" placeholder="300.00" required>
            </div>
        </div>

        <div class="large-1 cell">
            <label for="problem" class="text-right middle">Problem</label>
        </div>                        
        <div class="large-11 cell">
            <textarea name="problem" placeholder="The problem is..." required></textarea>
        </div>
    </div>     


    <div id="addRepairDiv" class="grid-x grid-padding-x">
        <!--ADD REPAIR BUTTON-->
        <div class="large-12 cell">
            <input type="button" class="button float-right" id="addRepairButton" name="addRepairButton" value="Add repair" />
        </div>
    </div>

</div><!--/vehicletemplate-->