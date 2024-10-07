<?php
    include("head.php");
    include("configure.php");
    
    
    $input_year = isset($_GET['bill_year']) ? $_GET['bill_year'] : convertToEngYear(date("Y"));
    $input_month = isset($_GET['bill_month']) ? $_GET['bill_month'] : convertTo2Digit(date("m"));
    $action = isset($_GET['action']) ? $_GET['action'] : "create";
    
    if($action=="add"){
 
        $bill_year = isset($_GET['bill_year']) ? $_GET['bill_year'] : convertToEngYear(date("Y"));
        $bill_month = isset($_GET['bill_month']) ? $_GET['bill_month'] : convertTo2Digit(date("m"));
        
        echo "<br><div class='center'>";
        echo "Bill processing for room no for month $bill_month $bill_year";
        echo "<br>x records is inserted to database";
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
                    			if($i==$input_month){
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
                    			if($i==convertToThaiYear($input_year)){
                    				echo "<OPTION VALUE='".$i."' SELECTED>".$i;
                    				//$rep_year=$i;
                    			}else{
                    				echo "<OPTION VALUE='".$i."'>".$i;
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
                    <td class="textbox_center"><input type="number" id="wmeter_before" name="wmeter_before" disabled readonly></td>
                    <td class="textbox_center"><input type="number" id="wmeter_current" name="wmeter_current" oninput="calculateWaterCost()" required></td>
                    <td class="textbox_center"><input type="text" id="water_unit" size="10" name="water_unit" disabled readonly></td>
                    <td class="textbox_center"><input type="number" id="water_unitprice" size="10" name="water_unitprice" disabled readonly value="<?php echo getExpenseRate("0201");?>"></td>
                    <td class="textbox_center"><input type="text" id="water_cost" size="10" name="water_cost" disabled readonly></td>
                </tr>
                <tr>            
                    <td>ค่าไฟ</td>        
                	<td class="textbox_center"><input type="number" id="emeter_before" name="emeter_before" disabled readonly></td>
                    <td class="textbox_center"><input type="number" id="emeter_current" name="emeter_current" oninput="calculateElectricCost()" required></td>
                    <td class="textbox_center"><input type="text" id="electric_unit" size="10" name="electric_unit" disabled readonly></td>
                    <td class="textbox_center"><input type="number" id="electric_unitprice" size="10" name="electric_unitprice" disabled readonly value="<?php echo getExpenseRate("0301");?>"></td>
                    <td class="textbox_center"><input type="text" id="electric_cost" size="10" name="electric_cost" disabled readonly></td>

                </tr>
                <tr>
                    <td>ค่าเช่าห้อง</td>
                    <td colspan="4" align="right">&nbsp;</td>
                    <td class="textbox_center"><input type="text" id="room_cost" size="10" name="room_cost" disabled readonly value=""></td>
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
                    <td class="textbox_center"><input type="text" id="total" size="10" name="total" disabled readonly></td>
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
        }        
    </script>
    
    
    
<?php

    include("bottom.php");
?>
