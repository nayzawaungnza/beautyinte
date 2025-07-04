<?php
$host = 'localhost';
$username = 'root';
$password = '';

$mysqli = new mysqli($host, $username, $password);
if ($mysqli->connect_errno) {
    echo "Failed to connect to Mysql: " . $mysqli->connect_error;
    exit();
}
create_database($mysqli);
function create_database($mysqli)
{
    $sql = "CREATE DATABASE IF NOT EXISTS `beauty_salon`
        DEFAULT CHARACTER SET utf8mb4
        COLLATE utf8mb4_general_ci";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}
function select_db($mysqli)
{
    if ($mysqli->select_db("beauty_salon")) {
        return true;
    }
    return false;
}
select_db($mysqli);
create_table($mysqli);
function create_table($mysqli)
{
    //users
    $user_sql = "CREATE TABLE IF NOT EXISTS `users`
                (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(200) NOT NULL,
                role ENUM('admin','staff') NOT NULL,
                description TEXT NULL,
                phone VARCHAR(50) NOT NULL,
                gender ENUM('male','female') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
    if ($mysqli->query($user_sql) === false) return false;
    //Customer
    $customer_sql = "CREATE TABLE IF NOT EXISTS `customers`
                (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                phone VARCHAR(50) NOT NULL,
                password VARCHAR(200) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
    if ($mysqli->query($customer_sql) === false) return false;
    //Services
    $service_sql = "CREATE TABLE IF NOT EXISTS `services`
                (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT NULL,
                price INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
    if ($mysqli->query($service_sql) === false) return false;
    //Products
    $product_sql = "CREATE TABLE IF NOT EXISTS `products`
                (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT NULL,
                price INT NOT NULL,
                img TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
    if ($mysqli->query($product_sql) === false) return false;
    //Product sales
    $product_sales_sql = "CREATE TABLE IF NOT EXISTS `product_sales`
                     ( 
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     product_id INT NOT NULL,
                     customer_id INT NOT NULL,
                     qty INT NOT NULL,
                     total_price INT NOT NULL,
                     sale_date DATE NULL,
                     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                     FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                     FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
                     )";
    if ($mysqli->query($product_sales_sql) === false) return false;
    //Product quantity
    $product_qty_sql = "CREATE TABLE IF NOT EXISTS `product_qty`
                  ( 
                   id INT AUTO_INCREMENT PRIMARY KEY,
                   product_id INT NOT NULL,
                   qty INT NOT NULL,
                   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                   updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                   FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
                   )";
    if ($mysqli->query($product_qty_sql) === false) return false;
    //Appointment
    $appointment_sql = "CREATE TABLE IF NOT EXISTS `appointments`
                   (
                   id INT AUTO_INCREMENT PRIMARY KEY,
                   customer_id INT NOT NULL,
                   service_id INT NOT NULL,
                   staff_id INT NOT NULL,
                   appointment_date DATE NULL,
                   appointment_time TIME NULL,
                   status INT NOT NULL,
                   comment TEXT NULL,
                   request TEXT NULL,
                   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                   updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                   FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
                   FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
                   FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE CASCADE
                   )";
    if ($mysqli->query($appointment_sql) === false) return false;
    //Payments
    $payment_sql = "CREATE TABLE IF NOT EXISTS `payments`
                   (
                   id INT AUTO_INCREMENT PRIMARY KEY,
                   appointment_id INT NOT NULL,
                   amount INT NOT NULL,
                   payment_method ENUM('k-pay','wave-pay')NOT NULL,
                   payment_date DATE NULL,
                   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                   updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                   FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
                   )";
    if ($mysqli->query($payment_sql) === false) return false;
}
