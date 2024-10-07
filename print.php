<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./tcpdf/tcpdf.php');
include("configure.php");

$bill_year = isset($_POST['bill_year']) ? $_POST['bill_year'] : "2024";
$bill_month = isset($_POST['bill_month']) ? $_POST['bill_month'] : "09";


$pdf = new TCPDF("L", PDF_UNIT, 'A5', true, 'UTF-8', false);

//define('PROMPT_REGULAR', TCPDF_FONTS::addTTFfont(dirname(__FILE__).'/fonts/THSarabun.ttf', 'TrueTypeUnicode'));
define('PROMPT_REGULAR', TCPDF_FONTS::addTTFfont(dirname(__FILE__).'/fonts/THSarabun.ttf', 'TrueTypeUnicode'));
define('PROMPT_BOLD', TCPDF_FONTS::addTTFfont(dirname(__FILE__).'/fonts/THSarabun-Bold.ttf', 'TrueTypeUnicode'));

// กำหนดค่าเริ่มต้น
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('เช่าบ้าน บิลค่าเช่า');
$pdf->SetSubject('เช่าบ้าน บิลค่าเช่า');
$pdf->SetKeywords('เช่าบ้าน, บิลค่าเช่า, PDF');
$pdf->SetPrintHeader(false); 
$pdf->setPrintFooter(false);

// กำหนดรูปแบบฟอนต์

$pdf->SetFont(PROMPT_REGULAR, '', 20);


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM bill_table where bill_year='".$bill_year."' and bill_month='".$bill_month."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $room_no=$row['room_no'];
        $old_water=$row['old_water'];
        $new_water=$row["new_water"];
        if((float)$old_water>(float)$new_water){
            $water_unit=(float)$new_water+(10000-(float)$old_water);
        }else{
            $water_unit=(float)$new_water-(float)$old_water;
        }
        $water_price=$row["water_price"];
        $water_unitprice=getExpenseRate("0201");
        $old_electric=$row["old_electric"];
        $new_electric=$row["new_electric"];
        if((float)$old_electric>(float)$new_electric){
            $electric_unit=(float)$new_electric+(10000-(float)$old_electric);
        }else{
            $electric_unit=(float)$new_electric-(float)$old_electric;
        }
        $electric_price=$row["electric_price"];
        $electric_unitprice=getExpenseRate("0301");
        $other_price=$row["other_price"];
        $room_price=$row["room_price"];
        
        $total=(float)$room_price+(float)$other_price+(float)$electric_price+(float)$water_price;
        
        // -----------------------------------------------------------------
        $pdf->AddPage();
        $html=<<<EOF
        <!DOCTYPE html>
        <html lang="th">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title></title>
             <style>
                @font-face { font-family: THSarabun; src: url('fonts/THSarabun.ttf'); } 
                @font-face { font-family: THSarabun; font-weight: bold; src: url('fonts/THSarabun-Bold.ttf');}
                .center {
                  text-align: center;
                  border: 3px solid green;
                }
                .header1 {
                    font-size:18px;
                    font-family: THSarabun,Arial, sans-serif;
                    text-align: center;
                    font-weight: bold;
                    padding-top: 20px;
                    padding-right: 20px;
                    padding-bottom: 20px;
                    padding-left: 20px;
                }
                .header2 {
                    font-size:14px;
                    font-family: THSarabun,Arial, sans-serif;
                    text-align: center;
                    padding-top: 20px;
                    padding-right: 20px;
                    padding-bottom: 20px;
                    padding-left: 20px;
                }
                body {
                    font-family: THSarabun,Arial, sans-serif;
                    margin: 20px;
                }
                .tg  {border-collapse:collapse;border-spacing:0;font-family: THSarabun;}
                .tg tr{border-color:black;border-style:solid;border-width:1px;font-family:THSarabun,Arial, sans-serif;font-size:14px;
                  overflow:hidden;padding:5px 5px;word-break:normal;}
                .tg .tg-0lax{text-align:left;vertical-align:top;}
                th {font-weight: bold;background-color: blue;color: white;}
            </style>
            <body>
        	<table class="tg" style="width:100%">
                        <tr>
                            <td class="header2" colspan="4"></td>
                            <td class="header2">บิลเลขที่</td>
                            <td class="header2">$bill_year$bill_month$room_no</td>
                        </tr>
                        <tr>
                            <td class="header2" colspan="6"><br></td>
                        </tr>
                        <tr>
                            <td class="header1" colspan="6">สุดารัตน์ อพาร์ทเม้นท์ 281/118 ซ.บุญมาก 2, ถ.สรงประภา, ข.สีกัน ข.ดอนเมือง กทม., 10210</td>
                        </tr>
                        <tr>
                            <td class="header2" colspan="6">บิลเรียกเก็บเงินค่าเช่าห้อง ประจำเดือน $bill_month $bill_year</td>
                        </tr>
                        <tr>
                            <td class="header2" colspan="6"><br></td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                            <td>ห้อง : </td>
                            <td>$room_no</td>
                        </tr>
                        <tr><td colspan="6" align="center"><br><br>
                        <table class="tg" border="0">
                
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>จดเดือนที่แล้ว</th>
                                    <th>จดเดือนนี้</th>
                                    <th>จำนวนหน่วย</th>
                                    <th>ราคาต่อหน่วย</th>
                                    <th>รวม (บาท)</th>
                                </tr>
                                <tr>
                                    <th>ค่าน้ำ</th>
                                    <td>$old_water</td>
                                    <td>$new_water</td>
                                    <td>$water_unit</td>
                                    <td>$water_unitprice</td>
                                    <td>$water_price</td>
                                </tr>
                                <tr>
                                    <th>ค่าไฟ</th>
                                    <td>$old_electric</td>
                                    <td>$new_electric</td>
                                    <td>$electric_unit</td>
                                    <td>$electric_unitprice</td>
                                    <td>$electric_price</td>
                                </tr>
                                <tr>
                                    <th>ค่าเช่าห้อง</th>
                                    <td colspan="4" align="right">&nbsp;</td>
                                    <td>$room_price</td>
                                </tr>
                                <tr>
                                    <th>ค่าโทรศัพท์ และ อื่นๆ</th>
                                    <td colspan="4" align="right">&nbsp;</td>
                                    <td>
                	                    $other_price
                                    </td>
                                </tr>
                                <tr>
                                    <th class="header1" colspan="5" align="right">รวมค่าเช่า</th>
                                    <th class="header1">$total</th>
                                </tr> 
                            </tbody>
                        </table>
                        <br><br>
                    </td></tr>
                </table>
        
            </body>
        </html>
        
        EOF;
        $pdf->writeHTML($html, true, false, true, false, '');
        // -----------------------------------------------------------------
    }

}

$conn->close();



$pdf->Output('bill-a5.pdf', 'I');
?>