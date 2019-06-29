drop database if exists tuna_ates_koc;
create database tuna_ates_koc;

use `tuna_ates_koc`;

create table District(
    District_ID int NOT NULL,
    District_Name varchar(25) NOT NULL,
	PRIMARY KEY (District_ID)
);
    
create table City(
    City_ID int  NOT NULL,
    City_Name varchar(25) NOT NULL,
    District_ID int,
	PRIMARY KEY(City_ID),
	FOREIGN KEY(District_ID) REFERENCES District(District_ID)
);
    
create table Market(
    Market_ID int NOT NULL AUTO_INCREMENT,
    Market_Name varchar(25) NOT NULL,
	PRIMARY KEY(Market_ID)
);

create table Salesman(
    Salesman_ID int NOT NULL AUTO_INCREMENT,
    Salesman_Name varchar(75) NOT NULL,
	PRIMARY KEY(Salesman_ID)
);
create table City_Market_Salesman(
    City_ID int,
	Market_ID int,
    Salesman_ID int,
    FOREIGN KEY(Market_ID) REFERENCES Market(Market_ID),
	FOREIGN KEY(City_ID) REFERENCES City(City_ID),
	FOREIGN KEY(Salesman_ID) REFERENCES Salesman(Salesman_ID)
);

create table Customer(
    Customer_ID int NOT NULL AUTO_INCREMENT,
    Customer_Name varchar(75) NOT NULL,
	PRIMARY KEY(Customer_ID)
);

create table Product(
    Product_ID int NOT NULL,
    Product_Name varchar(30) NOT NULL,
    Product_Price float,
	PRIMARY KEY(Product_ID)
);

create table Sale(
	Sale_ID int NOT NULL AUTO_INCREMENT,
    Customer_ID int NOT NULL,
    Salesman_ID int NOT NULL ,
    Product_ID int NOT NULL,
	Sale_Date date NOT NULL,
    PRIMARY KEY(Sale_ID),
    FOREIGN KEY(Customer_ID) REFERENCES Customer(Customer_ID),
    FOREIGN KEY(Salesman_ID) REFERENCES Salesman(Salesman_ID),
    FOREIGN KEY(Product_ID) REFERENCES Product(Product_ID)
);