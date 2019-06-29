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
$sql = "select Market_Name from Market ;";
$result = mysqli_query($link,$sql) or die("11");
$counter=0;
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    echo "Select Market<br>";
    // echo "<form action='ShowCityByDistrict.php' method='post'>";
    echo "<form  method='get'>";
    echo '<select name="marketname">';
    while($row = mysqli_fetch_array($result)) {
        echo "<option value='" . $row["Market_Name"] . "'>";
        echo $row["Market_Name"];
        echo "</option>";
    }
    echo '</select>';
    echo '<input type="submit" value="Submit" name="Market">';
    echo "</form>";
} else {
    echo "0 results";
}
if(isset($_GET['Market'])) {
    echo "<form  method='post'>";
    echo "<br>For seeing information click buttons<br>";
    echo '<input type="submit" value="Product" name="Product">';
    echo '<input type="submit" value="Salesman" name="Salesman">';
    echo '<input type="submit" value="SalesmanDetailed" name="SalesmanDetailed">';
    echo '<input type="submit" value="Invoice" name="Invoice">';
    echo "</form>";
}
if(isset($_POST['Product'])) {
    $market_name = mysqli_real_escape_string($link, $_GET['marketname']);
    $sql = "select Market_ID from Market where Market_Name='$market_name';";
    $result = mysqli_query($link, $sql) or die("11");
    $market_id = mysqli_fetch_array($result);
    $sql = "SELECT product.Product_Name as PRODUCT_NAME,COUNT(sale.Product_ID) as SOLD_PRODUCT from sale 
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
left join product on sale.Product_ID=product.Product_ID
where city_market_salesman.Market_ID='" . $market_id[0] . "'
group by sale.Product_ID;";
    $result = mysqli_query($link, $sql);
    $products=array(200);
    $sold_count=array(200);
    $i=0;
    $max=0;
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        echo "<h2>Number of Products Sold in $market_name Detailed</h2><br>";
        echo "<table border='2'>";
        echo "<tr><td>Product_Name</td><td>SOLD_PRODUCT</td></tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row["PRODUCT_NAME"] . "</td><td>" . $row["SOLD_PRODUCT"] . "</td>";
            echo "</tr>";
            $products[$i]=$row["PRODUCT_NAME"];
            $sold_count[$i]=$row["SOLD_PRODUCT"];
            if($sold_count[$i]>$max){
                $max=$sold_count[$i];
            }
            $i++;
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    $j=$i;
    $i=0;
    echo "<h2>Market sales in $market_name</h2><br>";
    echo "<div class=\"container\">";
    while($i<$j){
        $x=100*$sold_count[$i]/$max; // Maximumu baz aldim grafigi yaparken
        $x=$x."%";
        echo $x;
        echo "<div class=\"progress\">
  <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\"  aria-valuenow=\"$x\" 
  aria-valuemin=\"0\" aria-valuemax=$j style=\"width:$x\">
    $products[$i] 
  </div>
</div>";
        $i++;
    }
}
if(isset($_POST['Salesman'])) {
    $market_name = mysqli_real_escape_string($link, $_GET['marketname']);
    $sql = "select Market_ID from Market where Market_Name='$market_name';";
    $result = mysqli_query($link, $sql) or die("11");
    $market_id = mysqli_fetch_array($result);
    $sql = "SELECT salesman.Salesman_Name as SALESMAN_NAME, COUNT(sale.Sale_ID) as SOLD_PRODUCT
from sale
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
where city_market_salesman.Market_ID='".$market_id[0]."'
group by salesman.Salesman_Name;";
    $result = mysqli_query($link, $sql);
    $i=0;
    $salesmans=array();
    $sold_count=array();
    $max=0;
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>How many items sold by the salesman in $market_name detailed</h2><br>";
        echo "<table border='2'>";
        echo "<tr><td>Salesman_name</td><td>SOLD_PRODUCT</td></tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row["SALESMAN_NAME"] . "</td><td>" . $row["SOLD_PRODUCT"] . "</td>";
            echo "</tr>";
            $salesmans[$i]=$row["SALESMAN_NAME"];
            $sold_count[$i]=$row["SOLD_PRODUCT"];
            if($sold_count[$i]>$max){
                $max=$sold_count[$i];
            }
            $i++;
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    $j=$i;
    $i=0;
    echo "<h2>How many items sold by the salesmans in $market_name </h2><br>";
    echo "<div class=\"container\" >";
    while($i<$j){
        $x=100*$sold_count[$i]/$max; // Maximumu baz aldim grafigi yaparken
        $x=$x."%";
        echo $x;
        echo "<div class=\"progress\">
  <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\"  aria-valuenow=\"$x\" 
  aria-valuemin=\"0\" aria-valuemax=$max style=\"width:$x\">
    $salesmans[$i]
  </div>
</div>";
        $i++;
    }
}
if(isset($_POST['SalesmanDetailed'])){
    $market_name = mysqli_real_escape_string($link, $_GET['marketname']);
    $sql = "select Market_ID from Market where Market_Name='$market_name';";
    $result = mysqli_query($link, $sql) or die("11");
    $market_id = mysqli_fetch_array($result);
    $sql = "select DISTINCT(salesman.Salesman_Name) as SALESMAN_NAME from salesman
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
where city_market_salesman.Market_ID='".$market_id[0]."';";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        echo "Select Salesman<br>";
        // echo "<form action='ShowCityByDistrict.php' method='post'>";
        echo "<form  method='get'>";
        echo '<select name="salesmanname">';
        while($row = mysqli_fetch_array($result)) {
            echo "<option value='" . $row["SALESMAN_NAME"] . "'>";
            echo $row["SALESMAN_NAME"];
            echo "</option>";
        }
        echo '</select>';
        echo '<input type="submit" value="Submit" name="Salesman2">';
        echo "</form>";
    } else {
        echo "0 results";
    }
}
if(isset($_GET['Salesman2'])){
    $salesman_name=mysqli_real_escape_string($link, $_GET['salesmanname']);
    $sql = "select Salesman_ID from Salesman where Salesman_Name='$salesman_name';";
    $result = mysqli_query($link, $sql);
    $salesman_id=mysqli_fetch_array($result);
    $sql="select sale.Sale_ID as SALE_ID,city.City_NAME as CITY_NAME,salesman.Salesman_Name as SALESMAN_NAME,customer.Customer_Name as CUSTOMER_NAME,product.Product_Name as PRODUCT_NAME,CONCAT(ROUND(product.Product_Price,2),'TL') as PRODUCT_PRICE,DATE_FORMAT(sale.Sale_Date,\"%d/%m/%Y\") as SALE_DATE
from sale
left join product on sale.Product_ID=product.Product_ID
left join customer on sale.Customer_ID=customer.Customer_ID
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
left join city on city_market_salesman.City_ID=city.City_ID
where sale.Salesman_ID='".$salesman_id[0]."'
UNION
select 'TOTAL' as empty_column1,'' as empty_column3,'' as empty_column2,'' as empty_column3,'' empty_column4,CONCAT(ROUND( SUM(product.Product_Price),2),'TL') ,'' as empty_column5
from sale
left join product on sale.Product_ID=product.Product_ID
left join customer on sale.Customer_ID=customer.Customer_ID
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
where sale.Salesman_ID='".$salesman_id[0]."';";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        $i=0;
        $counter2=0;
        $sold_price=array();
        $sold_product=array();
        echo "<h2>Detailed information about salesman $salesman_name</h2><br>";
        echo "<table border='2'>";
        echo "<tr><td>Sale_ID</td><td>City_Name</td><td>Salesman_Name</td><td>Customer_Name</td><td>Product_Name</td><td>Product_Prıce</td><td>Sale_Date</td></tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row["SALE_ID"] . "</td><td>" . $row["CITY_NAME"] . "</td><td>" . $row["SALESMAN_NAME"] . "</td><td>" . $row["CUSTOMER_NAME"] . "</td><td>" . $row["PRODUCT_NAME"] . "</td><td>" . $row["PRODUCT_PRICE"] . "</td><td>" . $row["SALE_DATE"] . "</td>";
            echo "</tr>";
            $customer_name[$i]=$row["CUSTOMER_NAME"];
            $sold_product[$i]=$row["PRODUCT_PRICE"];
            $i++;
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    $counter2=$sold_product[$i-1];
    $j=$i;
    $i=0;
    echo "<h2>Information about $salesman_name</h2><br>";
    echo "<div class=\"container\">";
    while($i<$j-1){
        $x=100*$sold_product[$i]/$counter2;
        $x=$x."%";
        echo $x;
        echo "<div class=\"progress\">
  <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\"  aria-valuenow=\"$x\" 
  aria-valuemin=\"0\" aria-valuemax=$j style=\"width:$x\">
    $customer_name[$i]
  </div>
</div>";
        $i++;
    }

}
if(isset($_POST['Invoice'])) {
    $market_name = mysqli_real_escape_string($link, $_GET['marketname']);
    session_start();
    $_SESSION['market_name'] =  $market_name;
    session_write_close();
    $sql = "select Market_ID from Market where Market_Name='$market_name';";
    $result = mysqli_query($link, $sql) or die("11");
    $market_id = mysqli_fetch_array($result);
    $sql = "select customer.Customer_Name as CUSTOMER_NAME from customer 
left join sale on customer.Customer_ID=sale.Customer_ID
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
where city_market_salesman.Market_ID='".$market_id[0]."';";
    $result = mysqli_query($link,$sql) or die("11");
    if (mysqli_num_rows($result) > 0) {
        echo "Select Customer<br>";
        // echo "<form action='ShowCityByDistrict.php' method='post'>";
        echo "<form  method='get'>";
        echo '<select name="customername">';
        while($row = mysqli_fetch_array($result)) {
            echo "<option value='" . $row["CUSTOMER_NAME"] . "'>";
            echo $row["CUSTOMER_NAME"];
            echo "</option>";
        }
        echo '</select>';
        echo '<input type="submit" value="Submit" name="Customer">';
        echo "</form>";
    } else {
        echo "0 results";
    }
}
if(isset($_GET['Customer'])) {
    session_start();
    $market_name=$_SESSION['market_name'];
    session_write_close();
    $sql = "select Market_ID from Market where Market_Name='$market_name';";
    $customer_name=mysqli_real_escape_string($link, $_GET['customername']);
    $sql="select customer.Customer_Name as CUSTOMER_NAME,product.Product_Name as PRODUCT_NAME,CONCAT(product.Product_Price,'TL') as PRODUCT_PRICE,DATE_FORMAT(sale.Sale_Date,\"%d/%m/%Y\") as SALE_DATE
from customer
left join sale on customer.Customer_ID=sale.Customer_ID
left join product on sale.Product_ID=product.Product_ID
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
left join market on city_market_salesman.Market_ID=market.Market_ID
where customer.Customer_Name='".$customer_name."' and Market.Market_Name='".$market_name."'
UNION
select 'TOTAL' as empty_column1,'' as empty_column2,CONCAT(ROUND( SUM(product.Product_Price),2),'TL') ,'' as empty_column4
from customer
left join sale on customer.Customer_ID=sale.Customer_ID
left join product on sale.Product_ID=product.Product_ID
left join salesman on sale.Salesman_ID=salesman.Salesman_ID
left join city_market_salesman on salesman.Salesman_ID=city_market_salesman.Salesman_ID
left join market on city_market_salesman.Market_ID=market.Market_ID
where customer.Customer_Name='".$customer_name."' and Market.Market_Name='".$market_name."';";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        $i=0;
        $counter2=0;
        $sold_price=array();
        $sold_product=array();
        echo "<h2>Detailed information about customer $customer_name</h2><br>";
        echo "<table border='2'>";
        echo "<tr><td>Customer_Name</td><td>Product_Name</td><td>Product_Price</td><td>Sale_Date</td></tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row["CUSTOMER_NAME"] . "</td><td>" . $row["PRODUCT_NAME"] . "</td><td>" . $row["PRODUCT_PRICE"] . "</td><td>" . $row["SALE_DATE"] . "</td>";
            echo "</tr>";
            $sold_price[$i]=$row["PRODUCT_PRICE"];
            $sold_product[$i]=$row["PRODUCT_NAME"];
            $i++;
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    $counter2=$sold_price[$i-1];
    $j=$i;
    $i=0;
    echo "<h2>Information about $customer_name</h2><br>";
    echo "<div class=\"container\">";
    while($i<$j-1){
        $x=100*$sold_price[$i]/$counter2;
        $x=$x."%";
        echo $x;
        echo "<div class=\"progress\">
  <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\"  aria-valuenow=\"$x\" 
  aria-valuemin=\"0\" aria-valuemax=$j style=\"width:$x\">
    $sold_product[$i]
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
