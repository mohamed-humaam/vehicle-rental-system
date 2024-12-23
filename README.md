# Vehicle Rental System

A web-based vehicle rental management system built with PHP, MySQL, and XAMPP.

## Features

- User Authentication (Admin/User roles)
- Vehicle Management
- Customer Management
- Booking System
- Payment Tracking
- Responsive Design

## Prerequisites

- XAMPP (with Apache and MySQL)
- PHP 7.4 or higher
- VS Code with extensions:
  - PHP IntelliSense
  - PHP Server

## Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/mohamed-humaam/vehicle-rental-system.git
   cd vehicle-rental-system
   ```

2. **Database Setup**
   - Start XAMPP Control Panel
   - Start Apache and MySQL services
   - Access MySQL:
     
     **For Windows:**
     ```bash
     cd C:\xampp\mysql\bin
     mysql -u root -p
     ```
     
     **For Mac:**
     ```bash
     /Applications/XAMPP/bin/mysql -u root -p
     ```
     
     **For Linux:**
     ```bash
     sudo /opt/lampp/bin/mysql -u root -p
     ```
   
   - Import the database schema:
     ```sql
     CREATE DATABASE vehicle_rental_system;
     USE vehicle_rental_system;
     
     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(100),
         email VARCHAR(100) UNIQUE,
         password VARCHAR(255),
         role ENUM('admin', 'user') DEFAULT 'user'
     );

     CREATE TABLE vehicles (
         id INT AUTO_INCREMENT PRIMARY KEY,
         photo VARCHAR(255),
         name VARCHAR(100),
         type VARCHAR(50),
         daily_price DECIMAL(10, 2),
         availability BOOLEAN DEFAULT 1
     );

     CREATE TABLE customers (
         id INT AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(100),
         email VARCHAR(100) UNIQUE,
         phone VARCHAR(15)
     );

     CREATE TABLE bookings (
         id INT AUTO_INCREMENT PRIMARY KEY,
         customer_id INT NOT NULL,
         vehicle_id INT NOT NULL,
         start_date DATE NOT NULL,
         end_date DATE NOT NULL,
         total_amount DECIMAL(10, 2) NOT NULL,
         status ENUM('pending', 'booked', 'cancelled') DEFAULT 'booked',
         FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
         FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
     );

     CREATE TABLE payments (
         id INT AUTO_INCREMENT PRIMARY KEY,
         booking_id INT NOT NULL,
         payment_date DATE NOT NULL,
         amount DECIMAL(10, 2) NOT NULL,
         FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
     );
     ```

3. **VS Code Setup**
   - Open VS Code
   - Install required extensions:
     - PHP IntelliSense
     - PHP Server
   - Open the project folder in VS Code

4. **Running the Application**
   - Navigate to `modules/login/login.php`
   - Right-click on the file
   - Select "PHP Server: Serve Project"
   - The application will open in your default browser

## Project Structure

```
vehicle-rental-system/
├── includes/
│   ├── config.php
│   ├── footer.php
│   └── header.php/
├── modules/
│   ├── login/
│   ├── customers/
│   ├── vehicles/
│   ├── bookings/
│   └── payments/
├── assets/
│   ├── css/
│   └── js/
├── uploads/
│   └── vehicles/
├── database.sql
├── index.php
├── LICENSE
└── README.md
```

## Usage

1. Access the application through the login page
2. Default admin credentials (if any)
3. Navigate through different modules:
   - Vehicle Management
   - Customer Management
   - Booking System
   - Payment Tracking

## Contributing

1. Fork the repository
2. Create a new branch
3. Make your changes
4. Submit a pull request