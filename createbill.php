<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include("head.php");
    include("configure.php");
    
    
    $bill_year = isset($_POST['bill_year']) ? $_POST['bill_year'] : convertToEngYear(date("Y"));
    $bill_month = isset($_POST['bill_month']) ? $_POST['bill_month'] : convertTo2Digit(date("m"));
    $action = isset($_GET['action']) ? $_GET['action'] : "create";
    
    
    if($action=="add"){
        
        $room_no = isset($_POST['room_no']) ? $_POST['room_no'] : "";
        $wmeter_before = isset($_POST['wmeter_before']) ? $_POST['wmeter_before'] : "";
        $wmeter_current = isset($_POST['wmeter_current']) ? $_POST['wmeter_current'] : "";
//        $water_unit = isset($_POST['water_unit']) ? $_POST['water_unit'] : "";
//        $water_unitprice = isset($_POST['water_unitprice']) ? $_POST['water_unitprice'] : "";
        $water_cost = isset($_POST['water_cost']) ? $_POST['water_cost'] : "";
        
        $emeter_before = isset($_POST['emeter_before']) ? $_POST['emeter_before'] : "";
        $emeter_current = isset($_POST['emeter_current']) ? $_POST['emeter_current'] : "";
//        $electric_unit = isset($_POST['electric_unit']) ? $_POST['electric_unit'] : "";
//        $electric_unitprice = isset($_POST['electric_unitprice']) ? $_POST['electric_unitprice'] : "";
        $electric_cost = isset($_POST['electric_cost']) ? $_POST['electric_cost'] : "";
        
        
        $room_cost = isset($_POST['room_cost']) ? $_POST['room_cost'] : "";
        $other_cost = isset($_POST['other_cost']) ? $_POST['other_cost'] : 0;
        $total = isset($_POST['total']) ? $_POST['total'] : "";
       
        $bill_date=convertToEngYear(date("Y")).date("-m-d");
        
        $check_bill_table_sql="SELECT * FROM bill_table where ROOM_NO = '$room_no' and BILL_MONTH = '$bill_month' and BILL_YEAR = '$bill_year'";
        $check_water_sql="SELECT * FROM billing_expense where ROOM_NO = '$room_no' and BILL_MONTH = '$bill_month' and BILL_YEAR = '$bill_year' and expense_id='0201'";
        $check_electric_sql="SELECT * FROM billing_expense where ROOM_NO = '$room_no' and BILL_MONTH = '$bill_month' and BILL_YEAR = '$bill_year' and expense_id='0301'";

        $insert_bill_table_sql=<<<EOF
        INSERT INTO bill_table
        (`room_no`,`bill_year`,`bill_month`,`bill_date`,`old_water`,`new_water`,`water_price`,`old_electric`,`new_electric`,`electric_price`,`other_price`,`room_price`)
        VALUES('$room_no','$bill_year','$bill_month','$bill_date',$wmeter_before,$wmeter_current,$water_cost,$emeter_before,$emeter_current,$electric_cost,$other_cost,$room_cost);
        EOF;
        $insert_water_sql=<<<EOF
        INSERT INTO billing_expense
        (`room_no`,`expense_id`,`bill_year`,`bill_month`,`bill_date`,`old_unit`,`new_unit`)
        VALUES('$room_no','0201','$bill_year','$bill_month','$bill_date',$wmeter_before,$wmeter_current);
        EOF;
        $insert_electric_sql=<<<EOF
        INSERT INTO billing_expense
        (`room_no`,`expense_id`,`bill_year`,`bill_month`,`bill_date`,`old_unit`,`new_unit`)
        VALUES('$room_no','0301','$bill_year','$bill_month','$bill_date',$emeter_before,$emeter_current);
        EOF;
        
        $update_bill_table_sql=<<<EOF
        UPDATE bill_table
        SET
        `old_water` = $wmeter_before,
        `new_water` = $wmeter_current,
        `water_price` = $water_cost,
        `old_electric` = $wmeter_current,
        `new_electric` = $emeter_current,
        `electric_price` = $electric_cost,
        `other_price` = $other_cost,
        `room_price` = $room_cost
        WHERE `room_no` = '$room_no' AND `bill_month` = '$bill_month' AND `bill_year` = '$bill_year';
        EOF;
        $update_water_sql=<<<EOF
        UPDATE billing_expense
        SET
        `old_unit` = $wmeter_before,
        `new_unit` = $wmeter_current
        WHERE `room_no` = '$room_no' AND `expense_id` = '0201' AND `bill_month` = '$bill_month' AND `bill_year` = '$bill_year';
        EOF;
        $update_electric_sql=<<<EOF
        UPDATE billing_expense
        SET
        `old_unit` = $emeter_before,
        `new_unit` = $emeter_current
        WHERE `room_no` = '$room_no' AND `expense_id` = '0301' AND `bill_month` = '$bill_month' AND `bill_year` = '$bill_year';
        EOF;
        
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        
        $stmt = $conn->prepare($check_bill_table_sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $existing_bill_table = true;
        } else {
            $existing_bill_table = false;
        }
        
        $stmt = $conn->prepare($check_water_sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $existing_water = true;
        } else {
            $existing_water = false;
        }
        
        $stmt = $conn->prepare($check_electric_sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $existing_electrice = true;
        } else {
            $existing_electrice = false;
        }

        
        echo "<br><div class='center'>";
        echo "Bill processing room no. $room_no for month $bill_month $bill_year";
        echo "<br>";
        if($existing_bill_table){
            $stmt = $conn->prepare($update_bill_table_sql);
            if ($stmt->execute()) {
                echo "Bill Record updated successfully";
            } else {
                echo "Error: " . $update_bill_table_sql . "<br>" . $conn->error;
            }
        }else{
            $stmt = $conn->prepare($insert_bill_table_sql);
            if ($stmt->execute()) {
                echo "Bill Record inserted successfully";
            } else {
                echo "Error: " . $insert_bill_table_sql . "<br>" . $conn->error;
            }
        }
        if($existing_water){
            $stmt = $conn->prepare($update_water_sql);
            if ($stmt->execute()) {
                echo "<br>Water Record updated successfully";
            } else {
                echo "Error: " . $update_water_sql . "<br>" . $conn->error;
            }
        }else{
            $stmt = $conn->prepare($insert_water_sql);
            if ($stmt->execute()) {
                echo "<br>Water Record inserted successfully";
            } else {
                echo "Error: " . $insert_water_sql . "<br>" . $conn->error;
            }
        }
        if($existing_electrice){
            $stmt = $conn->prepare($update_electric_sql);
            if ($stmt->execute()) {
                echo "<br>Electric Record updated successfully";
            } else {
                echo "Error: " . $update_electric_sql . "<br>" . $conn->error;
            }
        }else{
            $stmt = $conn->prepare($insert_electric_sql);
            if ($stmt->execute()) {
                echo "<br>Electric Record inserted successfully";
            } else {
                echo "Error: " . $insert_electric_sql . "<br>" . $conn->error;
            }
        }
        echo "</div><br>";
        
    }
    
?>

	<table class="tg" style="width:100%"><tr><td align="center"><br><br>
    	<form method="post" action="createbill.php?action=add">
    	<table style="width:800px">
    		<tr>
            	<td style="width:75%" align="right">
                  	<SELECT class="custom-select" id="bill_month" NAME="bill_month">
                    <?php
                    		for($i=1;$i<=12;$i++){
                    			if($i==$bill_month){
                    				echo "<OPTION VALUE='".convertTo2Digit($i)."' SELECTED>".monthToText(convertTo2Digit($i));
                    				//$rep_month=convertTo2Digit($i);
                    			}else{
                    				echo "<OPTION VALUE='".convertTo2Digit($i)."'>".monthToText(convertTo2Digit($i));
                    			}
                    		}
                    ?>
                    </SELECT>     	
            	</td>
            	<td style="width:25%" align="right">
                    <SELECT class="custom-select" id="bill_year" NAME="bill_year">
                    <?php
                    		$currentYear=convertToThaiYear(date('Y'));
                    		for($i=$currentYear;$i>=$currentYear-5;$i--){
                    			if($i==convertToThaiYear($bill_year)){
                    			    echo "<OPTION VALUE='".convertToEngYear($i)."' SELECTED>".$i;
                    				//$rep_year=$i;
                    			}else{
                    			    echo "<OPTION VALUE='".convertToEngYear($i)."'>".$i;
                    			}
                    		}
                    ?>
                    </SELECT>            	
            	</td>
        	</tr>
        	<tr><td colspan="2">&nbsp;</td></tr>	
      		<tr>
            	<th style="width:85%" align="right">ห้องเช่า : </th>
            	<td style="width:15%"><input size="10" type="text" id="room_no" name="room_no" required autofocus onblur="queryDatabase()"></td>
        	</tr>
    		<tr>
    			<td colspan="2"><hr></td>
    		</tr>    		
    	</table>
        <table style="width:800px">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>จดเดือนที่แล้ว</th>
                    <th>จดเดือนนี้</th>
                    <th>จำนวนหน่วย</th>
                    <th>ราคาต่อหน่วย</th>
                    <th>รวม (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ค่าน้ำ</td>
                    <td class="textbox_center"><input type="number" id="wmeter_before" name="wmeter_before" readonly tabindex="-1"></td>
                    <td class="textbox_center"><input type="number" id="wmeter_current" name="wmeter_current" onblur="calculateWaterCost()" required></td>
                    <td class="textbox_center"><input type="text" id="water_unit" size="10" name="water_unit" tabindex="-1" readonly></td>
                    <td class="textbox_center"><input type="number" id="water_unitprice" size="10" name="water_unitprice" tabindex="-1" readonly value="<?php echo getExpenseRate("0201");?>"></td>
                    <td class="textbox_center"><input type="text" id="water_cost" size="10" name="water_cost" tabindex="-1" readonly></td>
                </tr>
                <tr>            
                    <td>ค่าไฟ</td>        
                	<td class="textbox_center"><input type="number" id="emeter_before" name="emeter_before" tabindex="-1" readonly></td>
                    <td class="textbox_center"><input type="number" id="emeter_current" name="emeter_current" onblur="calculateElectricCost()" required></td>
                    <td class="textbox_center"><input type="text" id="electric_unit" size="10" name="electric_unit" tabindex="-1" readonly></td>
                    <td class="textbox_center"><input type="number" id="electric_unitprice" size="10" name="electric_unitprice" tabindex="-1" readonly value="<?php echo getExpenseRate("0301");?>"></td>
                    <td class="textbox_center"><input type="text" id="electric_cost" size="10" name="electric_cost" tabindex="-1" readonly></td>

                </tr>
                <tr>
                    <td>ค่าเช่าห้อง</td>
                    <td colspan="4" align="right">&nbsp;</td>
                    <td class="textbox_center"><input type="text" id="room_cost" size="10" name="room_cost" tabindex="-1" readonly value=""></td>
                </tr>
                <tr>
                    <td> <input type="checkbox" id="enabledOtherCost" onclick="toggleTextBox()">ค่าโทรศัพท์ และ อื่นๆ</td>
                    <td colspan="4" align="right">&nbsp;</td>
                    <td class="textbox_center">
	                    <input type="text" id="other_cost" size="10" name="other_cost" disabled oninput="calculateTotal()" value="0">
                    </td>
                </tr>
                <tr>
                    <td colspan="5" align="right">รวมค่าเช่า</td>
                    <td class="textbox_center"><input type="text" id="total" size="10" name="total" tabindex="-1" readonly></td>
                </tr>                
                <tr>
                    <td colspan="5" align="right">&nbsp;</td>
                    <td class="textbox_center"><input type="submit" value="บันทึก"></td>
                </tr>
            </tbody>
        </table>
    	</form>
    <br><br></td></tr></table>
    
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function calculateWaterCost() {
            var meterBefore = document.getElementById('wmeter_before').value;
            var meterCurrent = document.getElementById('wmeter_current').value;
            var water_unitprice = document.getElementById('water_unitprice').value;
            if(meterBefore>meterCurrent){
            	var units = parseInt(meterCurrent) + (10000-parseInt(meterBefore));
            }else{
            	var units = parseInt(meterCurrent) - parseInt(meterBefore);
            }
            var waterCost = units * water_unitprice;
            document.getElementById('water_cost').value = waterCost;
            document.getElementById('water_unit').value = units;
            
            var room_cost = document.getElementById('room_cost').value;
            var other_cost = document.getElementById('other_cost').value;
            var electric_cost = document.getElementById('electric_cost').value;
            var total= parseInt(room_cost) + parseInt(other_cost) + parseInt(electric_cost) + parseInt(waterCost);
            document.getElementById('total').value = total;
        }
        function calculateElectricCost() {
            var meterBefore = document.getElementById('emeter_before').value;
            var meterCurrent = document.getElementById('emeter_current').value;
            var electric_unitprice = document.getElementById('electric_unitprice').value;
            if(meterBefore>meterCurrent){
            	var units = parseInt(meterCurrent) + (10000-parseInt(meterBefore));
            }else{
            	var units = parseInt(meterCurrent) - parseInt(meterBefore);
            }
            var electricCost = units * electric_unitprice;
            document.getElementById('electric_cost').value = electricCost;
            document.getElementById('electric_unit').value = units;
            
            var room_cost = document.getElementById('room_cost').value;
            var other_cost = document.getElementById('other_cost').value;
            var water_cost = document.getElementById('water_cost').value;
            var total= parseInt(room_cost) + parseInt(other_cost) + parseInt(electricCost) + parseInt(water_cost);
            document.getElementById('total').value = total;
        }
        function calculateTotal() {
            var room_cost = document.getElementById('room_cost').value;
            var other_cost = document.getElementById('other_cost').value;
            var water_cost = document.getElementById('water_cost').value;
            var electric_cost = document.getElementById('electric_cost').value;
            var total= parseInt(room_cost) + parseInt(other_cost) + parseInt(electric_cost) + parseInt(water_cost);
            document.getElementById('total').value = total;
        }
        function toggleTextBox() {
            var checkbox = document.getElementById("enabledOtherCost");
            var textbox = document.getElementById("other_cost");
            textbox.disabled = !checkbox.checked;
        }
        function queryDatabase() {
            var inputText = document.getElementById("room_no").value;
            if (inputText) {
                $.ajax({
                    url: 'getoldmeter.php',
                    type: 'POST',
                    data: { inputText: inputText },
                    success: function(response) {
	                    response = JSON.parse(response);
                        document.getElementById("room_cost").value = response[0] || "No result";
                        document.getElementById("wmeter_before").value = response[1] || "No result";
                        document.getElementById("emeter_before").value = response[2] || "No result";
        				console.log(response);
                    },
                    error: function() {
                      	document.getElementById("room_cost").value = "0";
                        document.getElementById("wmeter_before").value = "0";
                        document.getElementById("emeter_before").value = "0";
                        
                    }
                });
            }
            var bill_month = document.getElementById("bill_month").value;
            var bill_year = document.getElementById("bill_year").value;
            if (bill_month&&bill_year) {
                $.ajax({
                    url: 'checkmeter.php',
                    type: 'POST',
                    data: { 
                    	inputText: inputText,
                    	bill_year: bill_year,
                    	bill_month: bill_month
                     },
                    success: function(response) {
	                    response = JSON.parse(response);
                        document.getElementById("wmeter_current").value = response[0] || "";
                        document.getElementById("emeter_current").value = response[1] || "";
                        document.getElementById("wmeter_before").value = response[2] || "";
                        document.getElementById("emeter_before").value = response[3] || "";
        				console.log(response);
                    },
                    error: function() {
                        document.getElementById("wmeter_current").value = "";
                        document.getElementById("emeter_current").value = "";
                        document.getElementById("wmeter_before").value = "";
                        document.getElementById("emeter_before").value = "";
                        
                    }
                });
            }
        }        
    </script>
    
    
    
<?php

    include("bottom.php");
?>
