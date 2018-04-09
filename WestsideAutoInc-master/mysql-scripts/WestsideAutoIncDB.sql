/* Nora White, Matthew Rose, Ryan Lockett */

DROP DATABASE if exists WestsideAutoIncDB;
CREATE DATABASE WestsideAutoIncDB;

USE WestsideAutoIncDB;

CREATE TABLE Buyer (
	BuyerID		INT(6) AUTO_INCREMENT,
	FirstName	VARCHAR(25) NOT NULL,
	LastName	VARCHAR(25) NOT NULL,
	Phone		BIGINT(11) NOT NULL,
	PRIMARY KEY (BuyerID)
);

CREATE TABLE Salesperson (
	SalespersonID	INT(6) AUTO_INCREMENT,
	FirstName		VARCHAR(25) NOT NULL,
	LastName		VARCHAR(25) NOT NULL,
	Phone			BIGINT(11) NOT NULL,
	PRIMARY KEY 	(SalespersonID)
);

CREATE TABLE WarrantyItem (
	WarrantyItemID	INT(6) AUTO_INCREMENT,
	Type			VARCHAR(50) NOT NULL,
	Description		VARCHAR(200) NOT NULL,
	PRIMARY KEY 	(WarrantyItemID)
);

CREATE TABLE Customer (
	CustomerID	INT(6) AUTO_INCREMENT,
	FirstName	VARCHAR(50) NOT NULL,
	LastName	VARCHAR(50) NOT NULL,
	Gender		VARCHAR(20) NOT NULL,
	Birthday	DATE NOT NULL,
	TaxID		BIGINT(10) NOT NULL,
	Phone		BIGINT(11) NOT NULL,
	Address		VARCHAR(50) NOT NULL,
	City		VARCHAR(20) NOT NULL,
	State		VARCHAR(20) NOT NULL,
	Zip			VARCHAR(6) NOT NULL,
	PRIMARY KEY (CustomerID)
);

CREATE TABLE Payment (
	PaymentID		INT(6) AUTO_INCREMENT,
	CustomerID		INT(6) NOT NULL,
	ExpectedDate	DATE NOT NULL,
	PaidDate		DATE NOT NULL,
	AmountDue		FLOAT(8,2) NOT NULL,
	AmountPaid		FLOAT(8,2) NOT NULL,
	BankAccount		BIGINT(20) NOT NULL,
	PRIMARY KEY (PaymentID),
	FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

CREATE TABLE EmploymentHistory (
	EmploymentHistoryID	INT(6) AUTO_INCREMENT,
	CustomerID			INT(6) NOT NULL,
	Employer 			VARCHAR(50) NOT NULL,
	Title				VARCHAR(50) NOT NULL,
	Supervisor			VARCHAR(50) NOT NULL,
	Phone				BIGINT(11) NOT NULL,
	Address				VARCHAR(100) NOT NULL,
	StartDate			DATE NOT NULL,
	PRIMARY KEY (EmploymentHistoryID),
	FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

CREATE TABLE Purchase (
	PurchaseID	INT(6) AUTO_INCREMENT,
	BuyerID		INT(6) NOT NULL,
	Date 		DATE NOT NULL,
	Location	VARCHAR(50) NOT NULL,
	Seller		VARCHAR(50) NOT NULL,
	IsAuction		BOOLEAN NOT NULL,
	PRIMARY KEY (PurchaseID),
	FOREIGN KEY (BuyerID) REFERENCES Buyer(BuyerID)
);

CREATE TABLE Vehicle (
	VehicleID		INT(6) AUTO_INCREMENT,
	PurchaseID		INT(6) NOT NULL,
	Make			VARCHAR(50) NOT NULL,
	Model			VARCHAR(50) NOT NULL,
	Year			INT(4) NOT NULL,
    Style			VARCHAR(20) NOT NULL,
	InteriorColor	VARCHAR(25) NOT NULL,
	Color			VARCHAR(25) NOT NULL,
	Mileage			INT(7) NOT NULL,
	`Condition`		VARCHAR(20) NOT NULL,
	BookPrice		FLOAT(8,2) NOT NULL,
	PricePaid		FLOAT(8,2) NOT NULL,
    ListingPrice    FLOAT(8,2),
    IsSold			BOOLEAN NOT NULL,
	PRIMARY KEY (VehicleID),
	FOREIGN KEY (PurchaseID) REFERENCES Purchase(PurchaseID)
);

CREATE TABLE Sale (
	SaleID 			INT(6) AUTO_INCREMENT,
	SalespersonID	INT(6) NOT NULL,
	CustomerID		INT(6) NOT NULL,
	VehicleID		INT(6) NOT NULL,
	Date 			DATE NOT NULL,
	TotalDue		FLOAT(8,2) NOT NULL,
	DownPayment		FLOAT(8,2) NOT NULL,
	FinanceAmount	FLOAT(8,2) NOT NULL,
	Commission		FLOAT(8,2) NOT NULL,
	PRIMARY KEY (SaleID),
	FOREIGN KEY (SalespersonID) REFERENCES Salesperson(SalespersonID),
	FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
	FOREIGN KEY (VehicleID) REFERENCES Vehicle(VehicleID)
);

CREATE TABLE Coverage (
	CoverageID		INT(6) AUTO_INCREMENT,
	WarrantyItemID	INT(6) NOT NULL,
	SaleID 			INT(6) NOT NULL,
	EndDate			DATE NOT NULL,
	Cost 			FLOAT(8,2) NOT NULL,
	Deductible		FLOAT(8,2) NOT NULL,
	PRIMARY KEY (CoverageID),
	FOREIGN KEY (WarrantyItemID) REFERENCES WarrantyItem(WarrantyItemID),
	FOREIGN KEY (SaleID) REFERENCES Sale(SaleID)
);

CREATE TABLE Repair (
	RepairID	INT(6) AUTO_INCREMENT,
	VehicleID	INT(6) NOT NULL,
	Problem		VARCHAR(200) NOT NULL,
	EstCost		FLOAT(8,2) NOT NULL,
	ActualCost	FLOAT(8,2),
	PRIMARY KEY (RepairID),
	FOREIGN KEY (VehicleID) REFERENCES Vehicle(VehicleID)
);