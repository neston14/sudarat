<?php
    include("configure.php");
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // 检查连接
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $bill_month = $_POST['bill_month'];
    $bill_year = $_POST['bill_year'];
    $room_no = $_POST['inputText'];
    
    $sql = "select water.room_no as room_no,water.new_unit as wmeter,electric.new_unit as emeter,water.old_unit as oldwmeter,electric.old_unit as oldemeter from (SELECT * FROM billing_expense where expense_id ='0201' and room_no='$room_no' and bill_month = '$bill_month' and bill_year = '$bill_year') water left join (SELECT * FROM billing_expense where expense_id ='0301' and room_no='$room_no' and bill_month = '$bill_month' and bill_year = '$bill_year') electric on electric.room_no = water.room_no";
    $stmt = $conn->prepare($sql);
    //$stmt->bind_param("s", $inputText, $inputText);
    
    // 执行查询
    $stmt->execute();
    $result = $stmt->get_result();

    
    
    // 处理查询结果
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $results = [
            (string)$row["wmeter"] ?? "",
            (string)$row["emeter"] ?? "",
            (string)$row["oldwmeter"] ?? "",
            (string)$row["oldemeter"] ?? ""
        ];
        echo json_encode($results);
    } else {
        echo json_encode(["", "","", ""]);
    }
    // 关闭连接
    $stmt->close();
    $conn->close();
?>