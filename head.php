<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        @font-face { font-family: THSarabun; src: url('fonts/THSarabun.ttf'); } 
        @font-face { font-family: THSarabun; font-weight: bold; src: url('fonts/THSarabun-Bold.ttf');}
    
        .custom-select {
          /*font-family: THSarabun,Arial;
          font-size:24px;*/
        }
        
        .custom-select select {
          display: none; 
        }
        
        .select-selected {
          background-color: DodgerBlue;
        }
        
      
        .select-selected:after {
          position: absolute;
          content: "";
          top: 14px;
          right: 10px;
          width: 0;
          height: 0;
          border: 6px solid transparent;
          border-color: #fff transparent transparent transparent;
        }
        
        .select-selected.select-arrow-active:after {
          border-color: transparent transparent #fff transparent;
          top: 7px;
        }
        
         .select-items div,.select-selected {
          color: #ffffff;
          padding: 8px 16px;
          border: 1px solid transparent;
          border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
          cursor: pointer;
        }
        
        .select-items {
          position: absolute;
          background-color: DodgerBlue;
          top: 100%;
          left: 0;
          right: 0;
          z-index: 99;
        }
        
        .select-hide {
          display: none;
        }
        
        .select-items div:hover, .same-as-selected {
          background-color: rgba(0, 0, 0, 0.1);
        }
        
        
                    
        .center {
          text-align: center;
          border: 3px solid green;
        }    
         /* Add a black background color to the top navigation */
        .topnav {
          background-color: #333;
          overflow: hidden;
        }
        
        /* Style the links inside the navigation bar */
        .topnav a {
          float: left;
          color: #f2f2f2;
          text-align: center;
          padding: 14px 16px;
          text-decoration: none;
          font-size: 17px;
        }
        
        /* Change the color of links on hover */
        .topnav a:hover {
          background-color: #ddd;
          color: black;
        }
        
        /* Add a color to the active/current link */
        .topnav a.active {
          background-color: #04AA6D;
          color: white;
        }   

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .textbox_center {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="number"] {
            width: 70px;
            padding: 8px;
            box-sizing: border-box;
        }
        input[type="text"] {
            padding: 8px;
            box-sizing: border-box;
            text-align: center;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg tr{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
          overflow:hidden;padding:5px 5px;word-break:normal;}
        .tg .tg-0lax{text-align:left;vertical-align:top}        
    </style>
</head>
<body>
    <div class="topnav">
      <a class="active" href="main.php">Home</a>
      <a href="createbill.php">Submit Invoice</a>
      <a href="showbill.php">Print Invoice</a>
      <a href="#about">Expense</a>
    </div>
    <br><br>