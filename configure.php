<?php

    $servername = "sudarat-apartment.com";
    $username = "rasikawa_apartment";
    $password = "LEpooh2901#";
    $dbname = "rasikawa_apartment";

    
    function getExpenseRate($expenseID){
        $servername = "sudarat-apartment.com";
        $username = "rasikawa_apartment";
        $password = "LEpooh2901#";
        $dbname = "rasikawa_apartment";
        
        $price=0;
        
        $connget = new mysqli($servername, $username, $password, $dbname);
        if ($connget->connect_error) {
            die("Connection failed: " . $connget->connect_error);
        }
        $sql = "SELECT * FROM expense_table where id='".$expenseID."'";
        $result = $connget->query($sql);
        while($row = $result->fetch_assoc()) {
            $price=$row['price'];
        }
        return $price;
    }
    function convertTo2Digit($temp){
        if($temp<10){
            $return_text="0".intval($temp);
        }else if($temp>=10){
            $return_text=$temp;
        }
        return $return_text;
        
    }
    function convertToThaiYear($temp){
        if($temp<2500){
            $return_text=$temp+543;
        }else{
            $return_text=$temp;
        }
        return $return_text;
    }
    function convertToEngYear($temp){
        if($temp>2500){
            $return_text=$temp-543;
        }else{
            $return_text=$temp;
        }
        return $return_text;
    }
    
    function monthToText($temp){
        switch($temp) {
            case '01':
                $return_month = 'มกราคม';
                break;
            case '02':
                $return_month = 'กุมภาพันธ์';
                break;
            case '03':
                $return_month = 'มีนาคม';
                break;
            case '04':
                $return_month = 'เมษายน';
                break;
            case '05':
                $return_month = 'พฤษภาคม';
                break;
            case '06':
                $return_month = 'มิถุนายน';
                break;
            case '07':
                $return_month = 'กรกฏาคม';
                break;
            case '08':
                $return_month = 'สิงหาคม';
                break;
            case '09':
                $return_month = 'กันยายน';
                break;
            case '10':
                $return_month = 'ตุลาคม';
                break;
            case '11':
                $return_month = 'พฤศจิกายน';
                break;
            case '12':
                $return_month = 'ธันวาคม';
                break;
        }
        
        return $return_month;
    }
?>