<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include("head.php");
    include("configure.php");
    
    
    $input_year = isset($_POST['bill_year']) ? $_POST['bill_year'] : convertToEngYear(date("Y"));
    $input_month = isset($_POST['bill_month']) ? $_POST['bill_month'] : convertTo2Digit(date("m")-1);
    
    $sql="SELECT * FROM rasikawa_apartment.bill_table where bill_year='".$input_year."' and bill_month='".$input_month."' order by room_no;";
    
    echo "<br><div class='center'>";
    echo "Apartment invoice for ".monthToText($input_month)." ".convertToThaiYear($input_year);
    echo "</div><br>";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
?>

	<table border="1" class="tg" style="width:100%">
	<tr><td align="center">
	<br><br>
    	<form method="post" action="print.php">
    	<table style="width:1000px">
    		<tr>
            	<td align="right">
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
                    <SELECT class="custom-select" id="bill_year" NAME="bill_year">
                    <?php
                    		$currentYear=convertToThaiYear(date('Y'));
                    		for($i=$currentYear;$i>=$currentYear-5;$i--){
                    			if($i==convertToThaiYear($input_year)){
                    			    echo "<OPTION VALUE='".convertToEngYear($i)."' SELECTED>".$i;
                    				//$rep_year=$i;
                    			}else{
                    			    echo "<OPTION VALUE='".convertToEngYear($i)."'>".$i;
                    			}
                    		}
                    ?>
                    </SELECT>
            		<input class="btn" formaction="showbill.php" type="submit" value="Show Bill">
            	</td>
        	</tr>
        	<tr><td colspan="2">&nbsp;</td></tr>
        	
    		<tr>
    			<td colspan="2"><hr></td>
    		</tr>    		
    	</table>
        <table border="0" style="width:1000px">
            <thead>
                <tr bgcolor='yellow'>
                    <th>ห้อง</th>
                    <th>น้ำเดือนที่แล้ว</th>
                    <th>น้ำจดเดือนนี้</th>
                    <th>ค่าน้ำ</th>
                    <th>ไฟฟ้าเดือนที่แล้ว</th>
                    <th>ไฟฟ้าจดเดือนนี้</th>
                    <th>ค่าไฟฟ้า</th>
                    <th>ค่าเช่าห้อง</th>
                    <th>ค่าอื่นๆ(ถ้ามี)</th>
                    <th>รวม (บาท)</th>
                </tr>
            </thead>
            <tbody>
<?php

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                
                $total=(double)$row['water_price']+(double)$row['electric_price']+(double)$row['other_price']+(double)$row['room_price'];
                
                echo "<tr>";
                echo "<td align='center'>".$row['room_no']."</td>";
                echo "<td align='center'>".$row['old_water']."</td>";
                echo "<td align='center'>".$row['new_water']."</td>";
                echo "<td align='center'>".$row['water_price']."</td>";
                echo "<td align='center'>".$row['old_electric']."</td>";
                echo "<td align='center'>".$row['new_electric']."</td>";
                echo "<td align='center'>".$row['electric_price']."</td>";
                echo "<td align='center'>".$row['other_price']."</td>";
                echo "<td align='center'>".$row['room_price']."</td>";
                echo "<td align='center'>$total</td>";
                echo "</tr>";
            }
        }

?>                                
                <tr>
                    <td colspan="10" align="right">&nbsp;</td>
                </tr> 
                <tr>
                    <td colspan="9" align="right">&nbsp;</td>
                    <td id="action" class="textbox_center"><input type="submit" value="Print A5">&nbsp;<input type="submit" value="Summmary"></td>
                </tr> 
                <tr>
                    <td colspan="10" align="right">&nbsp;</td>
                </tr>                    
            </tbody>
        </table>
    	</form>
    </td></tr></table>
    
<?php

    include("bottom.php");
?>
