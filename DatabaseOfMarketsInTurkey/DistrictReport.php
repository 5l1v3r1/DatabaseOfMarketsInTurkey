<?php
$user = 'root';
$password = 'root';
$db = 'tuna_ates_koc';
$host = 'localhost';
$port = 3307;
$link = mysqli_init();
$success = mysqli_real_connect(
    $link,
    $host,
    $user,
    $password,
    $db,
    $port
);
$sql = "SELECT district.District_Name,COUNT(sale.Sale_ID) from sale
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
left join city on city_market_salesman.City_ID=city.City_ID
left join district on city.District_ID=district.District_ID
group by district.District_ID;";
$result = mysqli_query($link,$sql);
$district_name=array(7);
$district_sales=array(7);
$i=0;
$total_sales=0;
while ($row = mysqli_fetch_array($result)) {
    $district_name[$i] = $row[0];
    $district_sales[$i]=$row[1];
    $total_sales=$total_sales+$row[1];
    $i++;
}
$j=0;
    echo "<h2>All sales divided to Districts Detailed</h2><br>";
    echo "<table border='1'>";
    echo "<tr><td>DISTRICT_NAME</td><td>TOTAL_SALES</td></tr>";
    while ($j<$i) {
        echo "<tr>";
        echo "<td>" .   $district_name[$j] . "</td><td>" . $district_sales[$j] . "</td>";
        echo "</tr>";
        $j++;
    }
    echo "</table>";
$dataPoints = array(
    array("label"=>$district_name[0], "y"=>100*$district_sales[0]/$total_sales),
    array("label"=>$district_name[1],"y"=>100*$district_sales[1]/$total_sales),
    array("label"=>$district_name[2], "y"=>100*$district_sales[2]/$total_sales),
    array("label"=>$district_name[3], "y"=>100*$district_sales[3]/$total_sales),
    array("label"=>$district_name[4], "y"=>100*$district_sales[4]/$total_sales),
    array("label"=>$district_name[5],"y"=>100*$district_sales[5]/$total_sales),
    array("label"=>$district_name[6],"y"=>100*$district_sales[6]/$total_sales))
    ?>
<!DOCTYPE HTML>
<html>
<head>
    <script>
        window.onload = function() {


            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "All sales divided into Districts"
                },
                subtitles: [{
                    text: "April 01 2019 - May 01 2019"
                }],
                data: [{
                    type: "pie",
                    yValueFormatString: "#,##0.00\"%\"",
                    indexLabel: "{label} ({y})",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
