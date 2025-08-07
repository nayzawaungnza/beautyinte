<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT customers.name AS customer_name, services.name AS service_name, staff.name AS staff_name,
        appointments.appointment_date AS app_date, appointments.appointment_time AS app_time,
        appointments.status, appointments.comment, appointments.request
        FROM appointments
        INNER JOIN users AS customers ON customers.id = appointments.customer_id
        INNER JOIN users AS staff ON staff.id = appointments.staff_id
        INNER JOIN services ON services.id = appointments.service_id";

if ($search !== '') {
    $search_escaped = $mysqli->real_escape_string($search);
    $sql .= " WHERE customers.name LIKE '%$search_escaped%'
              OR services.name LIKE '%$search_escaped%'
              OR staff.name LIKE '%$search_escaped%'
              OR appointments.appointment_date LIKE '%$search_escaped%'";
}

$sql .= " ORDER BY appointments.id DESC";

$result = $mysqli->query($sql);

// Set CSV headers for download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=appointments.csv');

// Open output stream
$output = fopen('php://output', 'w');

// CSV Column headers
fputcsv($output, ['Customer Name', 'Service Name', 'Staff Name', 'Date', 'Time', 'Status', 'Comment', 'Request']);

// Write rows
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = match ((int)$row['status']) {
            0 => 'စောင့်နေသည်',
            1 => 'ပြီးဆုံးသည်',
            3 => 'လက်ခံသည်',
            default => 'ငြင်းပယ်သည်',
        };
        fputcsv($output, [
            $row['customer_name'],
            $row['service_name'],
            $row['staff_name'],
            $row['app_date'],
            $row['app_time'],
            $status,
            $row['comment'],
            $row['request']
        ]);
    }
}

fclose($output);
exit;
