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

$sql = "select District_Name from District ;";
$result = mysqli_query($link,$sql) or die("11");
$counter=0;
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    echo "Select District<br>";
  // echo "<form action='ShowCityByDistrict.php' method='post'>";
    echo "<form  method='post'>";
    echo '<select name="districtname">';
    while($row = mysqli_fetch_array($result)) {
        echo "<option value='" . $row["District_Name"] . "'>";
        echo $row["District_Name"];
        echo "</option>";
    }
    echo '</select>';
    echo '<input type="submit" value="Submit" name="District">';
    echo "</form>";
} else {
    echo "0 results";
}
if (isset($_POST['District'])) {
    $district_name = mysqli_real_escape_string($link, $_POST['districtname']);
    $sql = "select District_ID from district where District_Name='$district_name';";
    $result = mysqli_query($link, $sql);
    $district_id=mysqli_fetch_array($result);
    $sql = "select City_Name from City where District_ID=$district_id[0];";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<br>Select City<br>";
        echo "<form method='post'>";
        echo '<select name="cityname">';
        while ($row = mysqli_fetch_array($result)) {
            echo "<option value='" . $row["City_Name"] . "'>";
            echo $row["City_Name"];
            echo "</option>";
        }
        echo '</select>';
        echo '<input type="submit" value="Submit" name="City">';
        echo "</form>";
    } else {
        echo "0 results";
    }
}
if (isset($_POST['City'])) {
    $city_name = mysqli_real_escape_string($link, $_POST['cityname']);
    $sql = "select City_ID from city where City_Name='$city_name';";
    $result = mysqli_query($link, $sql);
    $city_id=mysqli_fetch_array($result);
    $sql="SELECT DISTINCT(market.Market_Name) FROM market
left join city_market_salesman on market.Market_ID=city_market_salesman.Market_ID
where city_market_salesman.City_ID='".$city_id[0]."';";
    $result = mysqli_query($link, $sql);
    $i=0;
    $market_name_array=array(5);
    $market_id_array=array(5);
        while ($row = mysqli_fetch_array($result)) {
            $market_name_array[$i]=$row["Market_Name"];
            $i++;
        }
    $sql="SELECT DISTINCT(market.Market_ID) FROM market
left join city_market_salesman on market.Market_ID=city_market_salesman.Market_ID
where city_market_salesman.City_ID='".$city_id[0]."';";
         $result = mysqli_query($link, $sql);
        $i=0;
    while ($row = mysqli_fetch_array($result)) {
        $market_id_array[$i]=$row[0];
        $i++;
    }
    echo "<h2>Market sales in $city_name detailed</h2><br>";
    echo "<table border='2'>";
    echo "<tr><td>City</td><td>Market_Name</td><td>SOLD_PRODUCT</td></tr>";
    $i=0;
    $total_sales=0;
    $x_array=array(5);
    while($i<5) {
        $sql="SELECT COUNT(sale.sale_id) from sale 
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
where city_market_salesman.City_ID='".$city_id[0]."' and city_market_salesman.Market_ID='".$market_id_array[$i] ."'";
        $result = mysqli_query($link, $sql);
        $sold_product=mysqli_fetch_array($result);
        echo "<tr>";
        echo "<td>" .$city_name. "</td><td>" . $market_name_array[$i]. "</td><td>".$sold_product[0]."</td>";
        echo "</tr>";
        $total_sales=$sold_product[0]+$total_sales;
        $i++;
    }
    echo "</table><br>";
    $i=0;
    echo "<h2>Market sales in $city_name</h2><br>";
    echo "<div class=\"container\">";
    while($i<5) {
        $sql="SELECT COUNT(sale.sale_id) from sale 
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
where city_market_salesman.City_ID='".$city_id[0]."' and city_market_salesman.Market_ID='".$market_id_array[$i] ."'";
        $result = mysqli_query($link, $sql);
        $sold_product=mysqli_fetch_array($result);
        $x=100*$sold_product[0]/$total_sales;
        $x=$x."%";
        echo $x;
        echo "<div class=\"progress\">
  <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"$x\" 
  aria-valuemin=\"0\" aria-valuemax=$total_sales style=\"width:$x\">
    $market_name_array[$i] 
  </div>
</div>";
        $i++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
