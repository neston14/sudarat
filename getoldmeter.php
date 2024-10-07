<?php
    include("configure.php");
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // 检查连接
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // 获取 POST 数据
    $inputText = $_POST['inputText'];
    $room_no_int=(int)$inputText;
    if ($room_no_int>100 && $room_no_int<200){
        $exp_code="0101";
    }elseif ($room_no_int>200 && $room_no_int<300){
        $exp_code="0102";
    }elseif ($room_no_int>300 && $room_no_int<400){
        $exp_code="0103";        
    }else{
        $exp_code="0101";
    }
    // 准备 SQL 查询        
    $sqlgr = "SELECT price FROM expense_table WHERE id='".$exp_code."'";
    $resultgr = $conn->query($sqlgr);
    
    if ($resultgr->num_rows > 0) {
        $rowgr = $resultgr->fetch_assoc();
        $valuegr = $rowgr['price'];
    } else {
        $valuegr = "1200";
    }
    
    
    $sql = "select water.room_no as room_no,water.new_unit as wmeter,electric.new_unit as emeter from (SELECT * FROM billing_expense where expense_id ='0201' and room_no='".$inputText."' order by bill_year desc,bill_month desc limit 1) water left join (SELECT * FROM billing_expense where expense_id ='0301' and room_no='".$inputText."' order by bill_year desc,bill_month desc limit 1) electric on electric.room_no = water.room_no";
    $stmt = $conn->prepare($sql);
    //$stmt->bind_param("s", $inputText, $inputText);
    
    // 执行查询
    $stmt->execute();
    $result = $stmt->get_result();

    
    
    // 处理查询结果
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $results = [
            $valuegr ?? "No result",
            (string)$row["wmeter"] ?? "No result",
            (string)$row["emeter"] ?? "No result"
        ];
        echo json_encode($results);
    } else {
        echo json_encode(["0", "0", "0"]);
    }
    // 关闭连接
    $stmt->close();
    $conn->close();
?>