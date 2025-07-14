<?php
require_once 'db.php';

function insert_if_not_exists($mysqli, $table, $where, $insert_sql)
{
    $check = $mysqli->query("SELECT 1 FROM $table WHERE $where LIMIT 1");
    if ($check && $check->num_rows == 0) {
        $mysqli->query($insert_sql);
    }
}

// Users
insert_if_not_exists(
    $mysqli,
    'users',
    "email='admin@example.com'",
    "INSERT INTO users (name, email, password, role, phone, gender) VALUES ('Admin', 'admin@example.com', '" . md5('admin123') . "', 'admin', '0912345678', 'male')"
);
insert_if_not_exists(
    $mysqli,
    'users',
    "email='staff@example.com'",
    "INSERT INTO users (name, email, password, role, phone, gender) VALUES ('Staff', 'staff@example.com', '" . md5('staff123') . "', 'staff', '0998765432', 'female')"
);

// Customers
insert_if_not_exists(
    $mysqli,
    'customers',
    "phone='0911111111'",
    "INSERT INTO customers (name, phone, password) VALUES ('Customer One', '0911111111', '" . md5('cust123') . "')"
);
insert_if_not_exists(
    $mysqli,
    'customers',
    "phone='0922222222'",
    "INSERT INTO customers (name, phone, password) VALUES ('Customer Two', '0922222222', '" . md5('cust456') . "')"
);

// Payment Methods
insert_if_not_exists(
    $mysqli,
    'payment_method',
    "name='K-Pay'",
    "INSERT INTO payment_method (name, status) VALUES ('K-Pay', 1)"
);
insert_if_not_exists(
    $mysqli,
    'payment_method',
    "name='Wave-Pay'",
    "INSERT INTO payment_method (name, status) VALUES ('Wave-Pay', 1)"
);
insert_if_not_exists(
    $mysqli,
    'payment_method',
    "name='Cash'",
    "INSERT INTO payment_method (name, status) VALUES ('Cash', 1)"
);

// Promotions
insert_if_not_exists(
    $mysqli,
    'promotions',
    "package_name='Summer Sale'",
    "INSERT INTO promotions (package_name, percentage, description, start_date, end_date) VALUES ('Summer Sale', 10, '10% off for summer', '2024-06-01', '2024-06-30')"
);
insert_if_not_exists(
    $mysqli,
    'promotions',
    "package_name='Rainy Discount'",
    "INSERT INTO promotions (package_name, percentage, description, start_date, end_date) VALUES ('Rainy Discount', 15, '15% off for rainy season', '2024-07-01', '2024-07-31')"
);

// Products
insert_if_not_exists(
    $mysqli,
    'products',
    "name='Shampoo'",
    "INSERT INTO products (name, description, price, img) VALUES ('Shampoo', 'Hair cleaning product', 5000, 'default.png')"
);
insert_if_not_exists(
    $mysqli,
    'products',
    "name='Conditioner'",
    "INSERT INTO products (name, description, price, img) VALUES ('Conditioner', 'Hair softening product', 6000, 'default.png')"
);
insert_if_not_exists(
    $mysqli,
    'products',
    "name='Hair Oil'",
    "INSERT INTO products (name, description, price, img) VALUES ('Hair Oil', 'Nourishing hair oil', 7000, 'default.png')"
);

// Output
?>
<!DOCTYPE html>
<html>

<head>
    <title>Seeder</title>
</head>

<body>
    <h2>Seeder executed. Sample data inserted if not already present.</h2>
</body>

</html>