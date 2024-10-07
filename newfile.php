<?php


if($_action=="add"){
    isset($_POST['room_no']) ? $room_no = $_POST['room_no'] : $room_no = "";
    isset($_POST['rep_month']) ? $BILL_MONTH = $_POST['rep_month'] : $BILL_MONTH = "";
    isset($_POST['rep_year']) ? $BILL_YEAR = $_POST['rep_year'] : $BILL_YEAR = "";
    $ROOM_FLOOR_EXPENSE_ID=checkRoomExpenseID($ROOM_NO);
    $BILL_DATE=date("Y-m-d");
    $ROOM_PRICE_EXPENSE_TOTAL = "";
    $ROOM_PRICE_EXPENSE_RATE = "";
    $WATER_UNIT_OLD_UNIT = "";
    $WATER_UNIT_NEW_UNIT = "";
    $WATER_UNIT_EXPENSE_TOTAL = "";
    $WATER_UNIT_EXPENSE_RATE = "";
    $ELEC_UNIT_OLD_UNIT = "";
    $ELEC_UNIT_NEW_UNIT = "";
    $ELEC_UNIT_EXPENSE_TOTAL = "";
    $ELEC_UNIT_EXPENSE_RATE = "";
    
    $STATUS = "SUCCESSFULLY : Data insert.";
    
    foreach ($_POST as $param_name => $param_val) {
        $param_val=str_replace(",","",$param_val);
        $param_name=str_replace("@","_",$param_name);
        if (strpos($param_name, 'ROOM_PRICE_EXPENSE_TOTAL') != false) {
            $ROOM_PRICE_EXPENSE_TOTAL=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }else if (strpos($param_name, 'ROOM_PRICE_EXPENSE_RATE') != false) {
            $ROOM_PRICE_EXPENSE_RATE=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }else if (strpos($param_name, 'WATER_UNIT_OLD_UNIT') != false) {
            $WATER_UNIT_OLD_UNIT=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }else if (strpos($param_name, 'WATER_UNIT_NEW_UNIT') != false) {
            $WATER_UNIT_NEW_UNIT=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }else if (strpos($param_name, 'WATER_UNIT_EXPENSE_TOTAL') != false) {
            $WATER_UNIT_EXPENSE_TOTAL=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }else if (strpos($param_name, 'WATER_UNIT_EXPENSE_RATE') != false) {
            $WATER_UNIT_EXPENSE_RATE=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }else if (strpos($param_name, 'ELEC_UNIT_OLD_UNIT') != false) {
            $ELEC_UNIT_OLD_UNIT=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }else if (strpos($param_name, 'ELEC_UNIT_NEW_UNIT') != false) {
            $ELEC_UNIT_NEW_UNIT=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }else if (strpos($param_name, 'ELEC_UNIT_EXPENSE_TOTAL') != false) {
            $ELEC_UNIT_EXPENSE_TOTAL=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }else if (strpos($param_name, 'ELEC_UNIT_EXPENSE_RATE') != false) {
            $ELEC_UNIT_EXPENSE_RATE=$param_val;
            //echo "$param_name ===============> $param_val <BR>";
        }
        
    }
    
    $con = mysqli_connect($DB_HOSTNAME,$DB_USERNAME,$DB_PASSWORD);
    if(!$con){echo "Not connect";}
    mysqli_select_db($con,$DB_NAME);
    mysqli_query($con,"SET NAMES tis620");
    $INSERT_COUNT=0;
    $UPDATE_COUNT=0;
    
    $CHECK_SQL="SELECT * FROM billing_expense where ROOM_NO = '$ROOM_NO' and EXPENSE_ID = '0201' and BILL_MONTH = '$BILL_MONTH' and BILL_YEAR = '$BILL_YEAR';";
    if(checkExisting($CHECK_SQL)){
        $UPDATE_COUNT++;
    }else{
        $INSERT_COUNT++;
        $INSERT_BILLING_EXPENNSE="INSERT INTO billing_expense(`ROOM_NO`, `EXPENSE_ID`, `BILL_MONTH`, `BILL_YEAR`, `BILL_DATE`, `OLD_UNIT`, `NEW_UNIT`) VALUES ('$ROOM_NO', '0201', '$BILL_MONTH', '$BILL_YEAR', '$BILL_DATE', '$WATER_UNIT_OLD_UNIT', '$WATER_UNIT_NEW_UNIT')";
        //echo "DEBUG : $INSERT_BILLING_EXPENNSE<br>";
        mysqli_query($con,$INSERT_BILLING_EXPENNSE);
    }
    
    $CHECK_SQL="SELECT * FROM billing_expense where ROOM_NO = '$ROOM_NO' and EXPENSE_ID = '0202' and BILL_MONTH = '$BILL_MONTH' and BILL_YEAR = '$BILL_YEAR';";
    if(checkExisting($CHECK_SQL)){
        $UPDATE_COUNT++;
    }else{
        $INSERT_COUNT++;
        $INSERT_BILLING_EXPENNSE="INSERT INTO billing_expense(`ROOM_NO`, `EXPENSE_ID`, `BILL_MONTH`, `BILL_YEAR`, `BILL_DATE`, `OLD_UNIT`, `NEW_UNIT`) VALUES ('$ROOM_NO', '0202', '$BILL_MONTH', '$BILL_YEAR', '$BILL_DATE', '$ELEC_UNIT_OLD_UNIT', '$ELEC_UNIT_NEW_UNIT')";
        //echo "DEBUG : $INSERT_BILLING_EXPENNSE<br>";
        mysqli_query($con,$INSERT_BILLING_EXPENNSE);
    }
    
    $CHECK_SQL="SELECT * FROM billing_table where ROOM_NO = '$ROOM_NO' and EXPENSE_ID = '$ROOM_FLOOR_EXPENSE_ID' and BILL_MONTH = '$BILL_MONTH' and BILL_YEAR = '$BILL_YEAR';";
    if(checkExisting($CHECK_SQL)){
        $UPDATE_COUNT++;
    }else{
        $INSERT_COUNT++;
        $INSERT_billing_table="INSERT INTO billing_table(`ROOM_NO`, `EXPENSE_ID`, `BILL_MONTH`, `BILL_YEAR`, `BILL_DATE`, `EXPENSE_TOTAL`, `EXPENSE_RATE`) VALUES ('$ROOM_NO', '$ROOM_FLOOR_EXPENSE_ID', '$BILL_MONTH', '$BILL_YEAR', '$BILL_DATE', '$ROOM_PRICE_EXPENSE_TOTAL', '$ROOM_PRICE_EXPENSE_RATE')";
        //echo "DEBUG : $INSERT_billing_table<br>";
        mysqli_query($con,$INSERT_billing_table);
    }
    
    $CHECK_SQL="SELECT * FROM billing_table where ROOM_NO = '$ROOM_NO' and EXPENSE_ID = '0201' and BILL_MONTH = '$BILL_MONTH' and BILL_YEAR = '$BILL_YEAR';";
    if(checkExisting($CHECK_SQL)){
        $UPDATE_COUNT++;
    }else{
        $INSERT_COUNT++;
        $INSERT_billing_table="INSERT INTO billing_table(`ROOM_NO`, `EXPENSE_ID`, `BILL_MONTH`, `BILL_YEAR`, `BILL_DATE`, `EXPENSE_TOTAL`, `EXPENSE_RATE`) VALUES ('$ROOM_NO', '0201', '$BILL_MONTH', '$BILL_YEAR', '$BILL_DATE', '$WATER_UNIT_EXPENSE_TOTAL', '$WATER_UNIT_EXPENSE_RATE')";
        //echo "DEBUG : $INSERT_billing_table<br>";
        mysqli_query($con,$INSERT_billing_table);
    }
    
    $CHECK_SQL="SELECT * FROM billing_table where ROOM_NO = '$ROOM_NO' and EXPENSE_ID = '0202' and BILL_MONTH = '$BILL_MONTH' and BILL_YEAR = '$BILL_YEAR';";
    if(checkExisting($CHECK_SQL)){
        $UPDATE_COUNT++;
    }else{
        $INSERT_COUNT++;
        $INSERT_billing_table="INSERT INTO billing_table(`ROOM_NO`, `EXPENSE_ID`, `BILL_MONTH`, `BILL_YEAR`, `BILL_DATE`, `EXPENSE_TOTAL`, `EXPENSE_RATE`) VALUES ('$ROOM_NO', '0202', '$BILL_MONTH', '$BILL_YEAR', '$BILL_DATE', '$ELEC_UNIT_EXPENSE_TOTAL', '$ELEC_UNIT_EXPENSE_RATE')";
        //echo "DEBUG : $INSERT_billing_table<br>";
        mysqli_query($con,$INSERT_billing_table);
    }
    
    $CHECK_SQL="SELECT * FROM billing_table where ROOM_NO = '$ROOM_NO' and EXPENSE_ID = '0301' and BILL_MONTH = '$BILL_MONTH' and BILL_YEAR = '$BILL_YEAR';";
    if(checkExisting($CHECK_SQL)){
        $UPDATE_COUNT++;
    }else{
        $INSERT_COUNT++;
        $INSERT_billing_table="INSERT INTO billing_table(`ROOM_NO`, `EXPENSE_ID`, `BILL_MONTH`, `BILL_YEAR`, `BILL_DATE`, `EXPENSE_TOTAL`, `EXPENSE_RATE`) VALUES ('$ROOM_NO', '0301', '$BILL_MONTH', '$BILL_YEAR', '$BILL_DATE', '1', '0')";
        //echo "DEBUG : $INSERT_billing_table<br>";
        mysqli_query($con,$INSERT_billing_table);
    }
    
    $STATUS= "TOTAL $INSERT_COUNT RECORD IS INSERTED AND TOTAL $UPDATE_COUNT IS UPDATED!.";
    
    if($STATUS<>""){
        echo "<CENTER><TABLE id='rcorners2' width='700'><TR><TD><CENTER>";
        echo $STATUS;
        echo "</CENTER></TD></TR></TABLE></CENTER><BR><BR>";
    }
}


?>