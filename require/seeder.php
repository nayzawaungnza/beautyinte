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
insert_if_not_exists(
    $mysqli,
    'users',
    "email='staff1@gmail.com'",
    "INSERT INTO users (name, email, password, role, phone, gender) VALUES ('staff1', 'staff1@gmail.com', '" . md5('password') . "', 'staff', '0998765432', 'female')"
);
insert_if_not_exists(
    $mysqli,
    'users',
    "email='admin1@gmail.com'",
    "INSERT INTO users (name, email, password, role, phone, gender) VALUES ('admin1', 'admin1@gmail.com', '" . md5('password') . "', 'customer', '0998765432', 'female')"
);

// Customers
// insert_if_not_exists(
//     $mysqli,
//     'users',
//     "phone='0911111111'",
//     "INSERT INTO users (name, phone, password, role) VALUES ('Customer One', 'customer1@gmail.com','0911111111', '" . md5('cust123') . "', 'customer')"
// );
// insert_if_not_exists(
//     $mysqli,
//     'users',
//     "phone='0922222222'",
//     "INSERT INTO users (name, phone, password, role) VALUES ('Customer Two', 'customer2@gmail.com','0922222222', '" . md5('cust456') . "', 'customer')"
// );

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
    "INSERT INTO promotions (package_name, percentage, description, start_date, end_date) VALUES ('Summer Sale', 10, '10% off for summer', '2025-06-01', '2025-06-30')"
);
insert_if_not_exists(
    $mysqli,
    'promotions',
    "package_name='Rainy Discount'",
    "INSERT INTO promotions (package_name, percentage, description, start_date, end_date) VALUES ('Rainy Discount', 15, '15% off for rainy season', '2025-07-01', '2025-07-31')"
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
//Services
insert_if_not_exists(
    $mysqli,
    'services',
    "name='ဆံပင်ညှပ်ခြင်း'",
    "INSERT INTO services (name, description, price) VALUES ('ဆံပင်ညှပ်ခြင်း', 'သင့်ဆံပင်အမျိုးအစား၊ မျက်နှာပုံသဏ္ဋန်နှင့် နေထိုင်မှုဘောင်အတိုင်းကိုက်ညီစေရန် သီးသန့်အကြံပေးမှုများနှင့်စတင်ပါသည်။', 7000)"
);insert_if_not_exists(
    $mysqli,
    'services',
    "name='ဆံပင်အရောင်ဆိုးခြင်း'",
    "INSERT INTO services (name, description, price) VALUES ('ဆံပင်အရောင်ဆိုးခြင်း', 'ဆံပင်အရောင်ပြောင်းခြင်းဖြင့် သင့်ရဲ့ပုံစံကို သစ်လွင်စွာပြောင်းလဲလိုက်ပါ။', 10000)"
);
insert_if_not_exists(
    $mysqli,
    'services',
    "name='ဆံပင်ဖြောင့်ခြင်း'",
    "INSERT INTO services (name, description, price) VALUES ('ဆံပင်ဖြောင့်ခြင်း', 'သန့်ရှင်းပြီး တောက်ပတည့်တည့်ဖြောင့်ပြောင်တဲ့ ဆံပင်အလှကို ရရှိလိုသူအတွက် ကျွန်ုပ်တို၏ ဆံပင်ဖြောင့်ခြင်း ဝန်ဆောင်မှုသည် အထူးသင့်လျော်ပါသည်။', 80000)"
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