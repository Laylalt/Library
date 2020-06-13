<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $today = strtotime(date("Y-m-d")) - (6*60*60); ////ADELANTADO 6 HRS
        $due_date = strtotime("2020-06-12");
        //echo ($today - $due_date)/60/60/24;
        echo date('Y-m-d', strtotime("+7 days"));
    ?>
</body>
</html>