<?php
require_once 'db.php';

function insert($mysqli, $query)
{
    if (!$mysqli->query($query)) {
        echo "Insert error: " . $mysqli->error . "\n";
    }
}

// function seed_users($mysqli)
// {
//     $roles = ['admin', 'staff', 'customer'];
//     $genders = ['male', 'female'];
//     for ($i = 1; $i <= 10; $i++) {
//         $name = "User $i";
//         $email = "user$i@example.com";
//         $password = md5("password");
//         $role = $roles[array_rand($roles)];
//         $phone = "012345678$i";
//         $position = "barber";
//         $salary = "100000";
//         $gender = $genders[array_rand($genders)];
//         insert($mysqli, "INSERT INTO users (name, email, password, role, phone, position, salary,gender) 
//                          VALUES ('$name', '$email', '$password', '$role', '$phone', '$position','$salary','$gender')");
//     }
// }

function seed_service_categories($mysqli)
{
    for ($i = 1; $i <= 10; $i++) {
        insert($mysqli, "INSERT INTO service_categories (name) VALUES ('Service Category $i')");
    }
}

function seed_product_categories($mysqli)
{
    for ($i = 1; $i <= 10; $i++) {
        $desc = "Description for product category $i";
        insert($mysqli, "INSERT INTO product_categories (name) VALUES ('Product Category $i')");
    }
}

function seed_services($mysqli)
{
    for ($i = 1; $i <= 10; $i++) {
        $name = "Service $i";
        $desc = "Service description $i";
        $price = rand(50, 200);
        $category_id = rand(1, 10);
        insert($mysqli, "INSERT INTO services (name, description, price, category_id) 
                         VALUES ('$name', '$desc', $price, $category_id)");
    }
}

function seed_products($mysqli)
{
    for ($i = 1; $i <= 10; $i++) {
        $name = "Product $i";
        $desc = "Product description $i";
        $price = rand(20, 100);
        $category_id = rand(1, 10);
        insert($mysqli, "INSERT INTO products (name, description, price, img, category_id) 
                         VALUES ('$name', '$desc', $price, 'img$i.jpg', $category_id)");
    }
}

// function seed_payment_methods($mysqli)
// {
//     for ($i = 1; $i <= 10; $i++) {
//         insert($mysqli, "INSERT INTO payment_method (name, status) VALUES ('Method $i', 1)");
//     }
// }

function seed_promotions($mysqli)
{
    for ($i = 1; $i <= 10; $i++) {
        $name = "Promo $i";
        $percent = rand(5, 30);
        $desc = "Promotion description $i";
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime("+$i days"));
        insert($mysqli, "INSERT INTO promotions (package_name, percentage, description, start_date, end_date) 
                         VALUES ('$name', $percent, '$desc', '$start', '$end')");
    }
}

function seed_product_sales($mysqli)
{
    for ($i = 1; $i <= 10; $i++) {
        $product_id = rand(1, 10);
        $promotion_id = rand(1, 10);
        $payment_method_id = rand(1, 10);
        $qty = rand(1, 5);
        $price = rand(20, 100);
        $total = $price * $qty;
        $sale_date = date('Y-m-d', strtotime("-$i days"));
        insert($mysqli, "INSERT INTO product_sales (product_id, promotion_id, payment_method_id, qty, total_price, sale_date) 
                         VALUES ($product_id, $promotion_id, $payment_method_id, $qty, $total, '$sale_date')");
    }
}

function seed_product_qty($mysqli)
{
    for ($i = 1; $i <= 10; $i++) {
        $product_id = rand(1, 10);
        $qty = rand(10, 50);
        insert($mysqli, "INSERT INTO product_qty (product_id, qty) VALUES ($product_id, $qty)");
    }
}

function seed_appointments($mysqli)
{
    for ($i = 1; $i <= 10; $i++) {
        $customer_id = rand(1, 10);
        $service_id = rand(1, 10);
        $staff_id = rand(1, 10);
        $date = date('Y-m-d', strtotime("+$i days"));
        $time = date('H:i:s', strtotime("09:00 +$i minutes"));
        $status = rand(0, 2);
        insert($mysqli, "INSERT INTO appointments (customer_id, service_id, staff_id, appointment_date, appointment_time, status) 
                         VALUES ($customer_id, $service_id, $staff_id, '$date', '$time', $status)");
    }
}

function seed_payments($mysqli)
{
    for ($i = 1; $i <= 10; $i++) {
        $appointment_id = $i;
        $promotion_id = rand(1, 10);
        $amount = rand(100, 300);
        $method_id = rand(1, 10);
        $date = date('Y-m-d', strtotime("+$i days"));
        insert($mysqli, "INSERT INTO payments (appointment_id, promotion_id, amount, payment_method_id, payment_date) 
                         VALUES ($appointment_id, $promotion_id, $amount, $method_id, '$date')");
    }
}

// Run all seeders
// seed_users($mysqli);
seed_service_categories($mysqli);
seed_product_categories($mysqli);
seed_services($mysqli);
seed_products($mysqli);
seed_promotions($mysqli);
seed_product_sales($mysqli);
seed_product_qty($mysqli);
seed_appointments($mysqli);
seed_payments($mysqli);

echo "Database seeding completed.\n";
