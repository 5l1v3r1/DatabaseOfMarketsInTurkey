<?php
ini_set('max_execution_time',400); // Php storm execution time default dusuk oyuzden arttirmak gerekiyor
$user = 'root';
$password = 'root'; // MAMP kullandigim icin sifre root
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

/*$servername = "localhost"; //3306yi secerek default olarak baglanilmadigindan bu kisim calismadi bilgisayarimda ama labda denedigimde calisti
$username = "root";
$password = "mysql";

$success = mysqli_connect($servername, $username, $password);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "DROP DATABASE IF EXISTS tuna_ates_koc;";
mysqli_query($link,$sql);


$sql = "CREATE DATABASE tuna_ates_koc;";
mysqli_query($link,$sql);

mysqli_select_db($link,"tuna_ates_koc");

$sql = "create table District(
    District_ID int NOT NULL,
    District_Name varchar(25) NOT NULL,
	PRIMARY KEY (District_ID)
);";
$result = mysqli_query($link,$sql);

$sql = "create table City(
    City_ID int  NOT NULL,
    City_Name varchar(25) NOT NULL,
    District_ID int,
	PRIMARY KEY(City_ID),
	FOREIGN KEY(District_ID) REFERENCES District(District_ID)
);";
$result = mysqli_query($link,$sql);

$sql = "create table Market(
    Market_ID int NOT NULL AUTO_INCREMENT,
    Market_Name varchar(25) NOT NULL,
	PRIMARY KEY(Market_ID)
);";
$result = mysqli_query($link,$sql);

$sql = "create table Salesman(
    Salesman_ID int NOT NULL AUTO_INCREMENT,
    Salesman_Name varchar(75) NOT NULL,
	PRIMARY KEY(Salesman_ID)
);";
$result = mysqli_query($link,$sql);

$sql = "create table City_Market_Salesman(
    City_ID int,
	Market_ID int,
    Salesman_ID int,
    FOREIGN KEY(Market_ID) REFERENCES Market(Market_ID),
	FOREIGN KEY(City_ID) REFERENCES City(City_ID),
	FOREIGN KEY(Salesman_ID) REFERENCES Salesman(Salesman_ID)
);";
$result = mysqli_query($link,$sql);

$sql = "create table Customer(
    Customer_ID int NOT NULL AUTO_INCREMENT,
    Customer_Name varchar(75) NOT NULL,
	PRIMARY KEY(Customer_ID)
);";
$result = mysqli_query($link,$sql);

$sql = "create table Product(
    Product_ID int NOT NULL,
    Product_Name varchar(30) NOT NULL,
    Product_Price float,
	PRIMARY KEY(Product_ID)
);";
$result = mysqli_query($link,$sql);

$sql = "create table Sale(
	Sale_ID int NOT NULL AUTO_INCREMENT,
    Customer_ID int NOT NULL,
    Salesman_ID int NOT NULL ,
    Product_ID int NOT NULL,
	Sale_Date date NOT NULL,
    PRIMARY KEY(Sale_ID),
    FOREIGN KEY(Customer_ID) REFERENCES Customer(Customer_ID),
    FOREIGN KEY(Salesman_ID) REFERENCES Salesman(Salesman_ID),
    FOREIGN KEY(Product_ID) REFERENCES Product(Product_ID)
);";
$result = mysqli_query($link,$sql);*/


$filename = "csv/district.csv";
if(!file_exists($filename) || !is_readable($filename))
    return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{

    while (($row = fgetcsv($handle, 100, ';')) !== FALSE)
    {
        if(!$header)
            $header = $row;
        else{
            $sql = "INSERT INTO `district` (`District_ID`,`District_Name`) values ($row[1],'$row[0]')";
            $result = mysqli_query($link,$sql);
        }
    }
    // echo '</table>';
    fclose($handle);
}
$filename = "csv/market.csv";
if(!file_exists($filename) || !is_readable($filename))
    return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
    while (($row = fgetcsv($handle, 100, ';')) !== FALSE)
    {
        if(!$header)
            $header = $row;
        else{
            //echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><tr/>';
            $sql = "INSERT INTO `market` (Market_Name) values ('$row[0]')";
            $result = mysqli_query($link,$sql);
        }
    }
    // echo '</table>';
    fclose($handle);
}
$filename = "csv/city.csv";
if(!file_exists($filename) || !is_readable($filename))
    return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
    while (($row = fgetcsv($handle, 100, ';')) !== FALSE)
    {
        if(!$header) {
            $header = $row;
        }
        else{
            $sql = "INSERT INTO `city` (`City_ID`,`City_Name`,`District_ID`) values ($row[0],'$row[1]',$row[2])";
            $result = mysqli_query($link,$sql);
        }
    }
    fclose($handle);
}



$filename = "csv/product.csv"; // calisiyor ama 100 product var 200 olmalı
if(!file_exists($filename) || !is_readable($filename))
    return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
    //echo '<table border=1>';
    // echo '<tr><td>City_ID</td><td>City</td><td>District_ID</td><tr/>';
    while (($row = fgetcsv($handle, 200, ';')) !== FALSE)
    {
        if(!$header)
            $header = $row;
        else{
            //echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><tr/>';
            $sql = "INSERT INTO `product` (`Product_ID`,`Product_Name`,`Product_Price`) values ($row[0],'$row[1]',$row[2])";
            $result = mysqli_query($link,$sql);
        }
    }
    // echo '</table>';
    fclose($handle);
}
$name=array(500);
$surname=array(500);
$customer_name=array(1620);
$salesman_name=array(1215);
$filename = "csv/name.csv";


if(!file_exists($filename) || !is_readable($filename))
    return FALSE;

$header = NULL;
$i=-1;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
    while (($row = fgetcsv($handle, 500, ';')) !== FALSE)
    {
        if(!$header)
            $header = $row;
        else{
           $name[$i]=$row[0];
        }
        $i++;
    }
    fclose($handle);
}
$filename = "csv/surname.csv";


if(!file_exists($filename) || !is_readable($filename))
    return FALSE;

$header = NULL;
$i=-1;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
    while (($row = fgetcsv($handle, 500, ';')) !== FALSE)
    {
        if(!$header)
            $header = $row;
        else{
            $surname[$i]=$row[0];
        }
        $i++;
    }
    fclose($handle);
}

$i=0;

$copynames="";
while($i<1620){
    $random=rand(0,499);
    $random2=rand(0,499);
    $copynames=$name[$random]." ".$surname[$random2];
    if(in_array($copynames,$customer_name))
    {
        $i--;
    }
    else
    {
       $customer_name[$i]=$copynames;
    }
    $i++;
}
$i=0;
while($i<1620){
    $sql = "INSERT INTO `customer` (`Customer_Name`) values ('$customer_name[$i]')";
    $result = mysqli_query($link,$sql);
    $i++;
}
$i=0;
while($i<1215){
    $random=rand(0,499);
    $random2=rand(0,499);
    $copy=$name[$random]." ".$surname[$random2];
    if(in_array($copy,$salesman_name))
    {
        $i--;
    }
    else
    {
        $salesman_name[$i]=$copy;
    }
    $i++;
}
$i=0;
while($i<1215){
    $sql = "INSERT INTO `salesman` (`Salesman_Name`) values ('$salesman_name[$i]')";
    $result = mysqli_query($link,$sql);
    $i++;
}
$city_market=array(405);
$i=1;
$k=0;
while($i<82){
    $j=0;
    while($j<5){
        $random=rand(1,10);
        $copy=$i." ".$random;
        if(in_array($copy,$city_market))
        {
            $j--;
            $k--;
        }
        else
        {
            $city_market[$k]=$copy;
        }
        $j++;
        $k++;
    }
    $i++;
}
$city_market_salesman=array(1215);
$selected_salesman=array(1215);
$k=0;
$i=0;
while($i<405){
        $j=0;
    while($j<3){
        $copy=rand(1,1215);
        if(in_array($copy,$selected_salesman))
        {
            $j--;
            $k--;
        }
        else
        {
            $selected_salesman[$k]=$copy;
            $city_market_salesman[$k]=$city_market[$i]." ".$copy;
        }
        $k++;
        $j++;
    }
    $i++;
}
$i=0;
while($i<1215){
    $pieces = explode(" ", $city_market_salesman[$i]);
    $sql = "INSERT INTO `city_market_salesman` (`City_ID`,`Market_ID`,`Salesman_ID`) values ($pieces[0],$pieces[1],$pieces[2])";
    $result = mysqli_query($link,$sql);
    $i++;
}
$customer_product=array(8100);
$selected_salesman2=array(1215);
$k=0;
$i=1;
while($i<1621){
    $j=0;
    $random=rand(0,5); //At most 5
    while($j<$random){
        $random2=rand(1,200);
        $random3=rand(1,1216);
        $timestamp = rand( strtotime("April 01 2019"), strtotime("May 01 2019") );
        $random_date = date("Y-m-d", $timestamp );
        $customer_product[$k]=$i." ".$random2." ".$random_date." ".$random3;
        $j++;
        $k++;
    }
    $i++;
}

$i=0;
while($i<$k)
{
    $pieces = explode(" ", $customer_product[$i]);
    $sql = "INSERT INTO `sale` (`Customer_ID`,`Product_ID`,`Sale_Date`,`Salesman_ID`) values ($pieces[0],$pieces[1],'$pieces[2]',$pieces[3])";
    $result = mysqli_query($link,$sql);
    $i++;
}

echo"<form action=\"MainMenu.php\" method=\"post\">
  Your database is ready. For go to MainMenu click button!<br>
  <input type=\"submit\" value=\"Submit\">
</form> ";
?>