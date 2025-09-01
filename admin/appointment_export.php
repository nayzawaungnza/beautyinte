<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';

// FPDF library
require '../lib/fpdf.php';

// --- Helpers --- //
function getStatusTextEnglish($status)
{
    switch ((int)$status) {
        case 0:
            return "Pending";
        case 1:
            return "Completed";
        case 3:
            return "Accepted";
        default:
            return "Rejected";
    }
}

function translateServiceName($serviceNames)
{
    $services = array_map('trim', explode(',', $serviceNames));
    $map = [
        "ရိုးရိုးလျှော်" => "Simple Shampoo",
        "တစ်ကိုယ်လုံးနှိပ်လျော်" => "Full Body Wash",
        "အပေါ်ပိုင်းနှိပ်လျော်" => "Upper Body Wash",
        "လျှော်ညှပ်(အတို)" => "Hair Wash (short)",
        "လျှော်ညှပ်(အရှည်)" => "Hair Wash (long)",
        "Rebounding(ပုခုံးမထိ)" => "Rebounding (short)",
        "Rebounding(ကျောလယ်)" => "Rebounding (middle)",
        "Rebounding(အရှည်)" => "Rebounding (long)",
        "ဆံပင်ဖြောင့်(ပုခုံးမထိ)" => "Straight Hair (short)",
        "ဆံပင်ဖြောင့်(ကျောလယ်)" => "Straight Hair (middle)",
        "ဆံပင်ဖြောင့်(အရှည်)" => "Straight Hair (long)",
        "digital (အတို)" => "Digital (short)",
        "digital (ကျောလယ်)" => "Digital (middle)",
        "digital (အရှည်)" => "Digital (long)",
        "Cool (အတို)" => "Cool (short)",
        "Cool(ကျောလယ်)" => "Cool (middle)",
        "Cool (အရှည်)" => "Cool (long)",
        "ရိုးရိုးကာလာ( ပခုံးမထိ )" => "Simple Color (short)",
        "ရိုးရိုးကာလာ( ကျောလယ်)" => "Simple Color (middle)",
        "ရိုးရိုးကာလာ( အရှည်)" => "Simple Color (long)",
        "Crazy Color or Highlight( ပခုံးမထိ)" => "Crazy Color or Highlight (short)",
        "Crazy Color or Highlight( ကျောလယ်)" => "Crazy Color or Highlight (middle)",
        "Crazy Color or Highlight( အရှည်)" => "Crazy Color or Highlight (long)",
        "လက်သည်း(cleaning၊ဂျယ်ဆိုး)" => "Nail (cleaning,gel polish)",
        "လက်သည်းဆက်ခြင်း" => "Nail Extension",
        "လက်သည်း(အတုကပ်)" => "Press on Nail",
        "လက်သည်း(3D ပုံစံ)" => "3D Nail Design",
        "လက်သည်း(အဖြူအစရာနှင့်elegantရောင်ခြယ်ခြင်း)" => "Elegant Nail Design",
    ];
    $translated = [];
    foreach ($services as $service) {
        $translated[] = $map[$service] ?? $service;
    }
    return implode(', ', $translated);
}

function translateStaffName($staffName)
{
    $map = [
        "ဇာဇာ" => "Zar Zar",
        "အေးအေး" => "Aye Aye",
        "ထက်ထက်" => "Htet Htet",
        "နိုရာ" => "Nora",
        "နွေးနွေး" => "Nway Nway",
        "စုစု" => "Su Su",
        "နိုနို" => "No No",
        "ဖြူဖြူ" => "Phyu Phyu",
    ];
    return $map[$staffName] ?? $staffName;
}

function translateCustomerName($customerName)
{
    $map = [
        "ဇူးဇူး" => "Zue Zue",
        "နုနု" => "Nu Nu",
        "အိမွန်" => "Ei Mon",
        "မျိုးမျိုး" => "Myo Myo",
        "ကြူကြူ" => "Kyu Kyu",
        "သီတာ" => "Thidar",
        "ချောချော" => "Chaw Chaw",
        "ဖူးဖူး" => "Phue Phue",
        "သဲသဲ" => "Thae Thae",
        "ထွေးရီ" => "Htwe Yee",
        "ဝေဝေ" => "Wai Wai",
        "လှလှ" => "Hla Hla",
        "ချောစု" => "Chaw Su",
    ];
    return $map[$customerName] ?? $customerName;
}

function toLatin1($s)
{
    $t = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', (string)$s);
    return $t !== false ? $t : preg_replace('/[^\x20-\x7E]/', '?', (string)$s);
}

function fit($s, $maxChars)
{
    $s = trim((string)$s);
    if (mb_strlen($s, 'UTF-8') <= $maxChars) return $s;
    return mb_substr($s, 0, $maxChars - 1, 'UTF-8') . '…';
}

// --- Filter: Only appointments created today ---
$today = date('Y-m-d');
$where = " WHERE DATE(appointments.created_at) = '$today' ";

// Optional search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $esc = $mysqli->real_escape_string($search);
    $where .= " AND (customers.name LIKE '%$esc%'
                   OR staff.name LIKE '%$esc%'
                   OR appointments.selected_service_names LIKE '%$esc%')";
}

// --- Query appointments ---
$sql = "SELECT appointments.id, customers.name AS customer_name, staff.name AS staff_name,
        appointments.created_at AS app_date, appointments.appointment_time AS app_time,
        appointments.status, appointments.selected_service_names
        FROM appointments
        INNER JOIN users AS customers ON customers.id = appointments.customer_id
        INNER JOIN users AS staff ON staff.id = appointments.staff_id
        $where
        ORDER BY appointments.id DESC";

$res = $mysqli->query($sql);

// --- Query payments for today ---
$paymentSql = "SELECT appointment_id, amount FROM payments WHERE DATE(created_at) = '$today'";
$paymentsRes = $mysqli->query($paymentSql);
$paymentsArr = [];
$totalAmount = 0;
if ($paymentsRes && $paymentsRes->num_rows > 0) {
    while ($p = $paymentsRes->fetch_assoc()) {
        $paymentsArr[$p['appointment_id']][] = $p['amount'];
        $totalAmount += $p['amount'];
    }
}

// --- PDF Generation ---
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 16, toLatin1("Today's Appointments Report"), 0, 1, 'C');
$pdf->Ln(2);

// Table Header
$pdf->SetFont('Arial', 'B', 10);
$w = [10, 36, 52, 28, 22, 16, 20, 20];
$headers = ['No', 'Customer Name', 'Services', 'Staff', 'Date', 'Time', 'Status', 'Amount'];
foreach ($headers as $i => $h) {
    $pdf->Cell($w[$i], 8, toLatin1($h), 1, 0, 'C');
}
$pdf->Ln();

// Table Body
$pdf->SetFont('Arial', '', 9);
$i = 1;
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $dateOnly = date('Y-m-d', strtotime($row['app_date']));
        $appointmentId = $row['id'];
        $amountText = '';
        if (isset($paymentsArr[$appointmentId])) {
            $amountText = implode(',', $paymentsArr[$appointmentId]);
        }
        $cells = [
            $i++,
            fit(translateCustomerName($row['customer_name']), 20),
            fit(translateServiceName($row['selected_service_names']), 60),
            fit(translateStaffName($row['staff_name']), 22),
            $dateOnly,
            $row['app_time'],
            getStatusTextEnglish($row['status']),
            $amountText
        ];
        foreach ($cells as $idx => $val) {
            $pdf->Cell($w[$idx], 7, toLatin1((string)$val), 1);
        }
        $pdf->Ln();
    }
    // Total amount row
    $pdf->Cell(array_sum($w) - $w[7], 7, toLatin1('Total Amount'), 1, 0, 'R');
    $pdf->Cell($w[7], 7, toLatin1($totalAmount), 1, 1, 'C');
} else {
    $pdf->Cell(0, 10, toLatin1('No appointments created today.'), 1, 1, 'C');
}

$pdf->Output('D', 'appointments_today.pdf');
exit;
