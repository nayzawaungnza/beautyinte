<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';

// Get payment ID from URL
$payment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$payment_id) {
    header("Location: " . $admin_base_url . "payment_list.php");
    exit;
}

// Fetch payment details with related information
$payment_sql = "SELECT p.*, 
                a.appointment_date, a.appointment_time,a.selected_service_names,
                c.name as customer_name, c.phone as customer_phone, s.price as service_price,
                st.name as staff_name,
                pm.name as payment_method_name
                FROM payments p
                LEFT JOIN appointments a ON p.appointment_id = a.id
                LEFT JOIN users c ON a.customer_id = c.id
                LEFT JOIN services s ON a.service_id = s.id
                LEFT JOIN users st ON a.staff_id = st.id
                LEFT JOIN payment_method pm ON p.payment_method_id = pm.id
                WHERE p.id = $payment_id";

$payment_result = $mysqli->query($payment_sql);

if (!$payment_result || $payment_result->num_rows == 0) {
    header("Location: " . $admin_base_url . "payment_list.php");
    exit;
}

$payment = $payment_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ငွေပေးချေမှုဘောင်ချာ - <?= htmlspecialchars($payment['customer_name']) ?></title>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 20px;
            }

            .voucher {
                box-shadow: none;
                border: 1px solid #000;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .voucher {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .content {
            padding: 25px;
        }

        .voucher-number {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .voucher-number h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }

        .voucher-number p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section h4 {
            color: #333;
            margin: 0 0 10px 0;
            font-size: 16px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            min-width: 120px;
        }

        .info-value {
            color: #333;
            text-align: right;
            flex: 1;
        }

        .amount-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .amount-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .footer p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }

        .print-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 20px auto;
            display: block;
            transition: background 0.3s;
        }

        .print-btn:hover {
            background: #5a6fd8;
        }

        .back-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }

        .back-btn:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }

        .qr-code img {
            width: 80px;
            height: 80px;
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">

        <a href="<?= $admin_base_url ?>payment_list.php?id=<?= $payment_id ?>" class="back-btn">← ငွေပေးချေမှုစာရင်းသို့</a>

        <button onclick="window.print()" class="print-btn">🖨️ ဘောင်ချာပုံနှိပ်ရန်</button>
    </div>

    <div class="voucher">
        <div class="header">
            <h1>Beauty Salon</h1>
            <p>ငွေပေးချေမှုဘောင်ချာ</p>
        </div>

        <div class="content">
            <div class="voucher-number">
                <h3>ဘောင်ချာ #<?= str_pad($payment['id'], 6, '0', STR_PAD_LEFT) ?></h3>
                <p><?= date('d/m/Y H:i', strtotime($payment['payment_date'])) ?></p>
            </div>

            <div class="info-section">
                <h4>ဖောက်သည်အချက်အလက်</h4>
                <div class="info-row">
                    <span class="info-label">အမည်:</span>
                    <span class="info-value"><?= htmlspecialchars($payment['customer_name']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">ဖုန်း:</span>
                    <span class="info-value"><?= htmlspecialchars($payment['customer_phone']) ?></span>
                </div>
            </div>

            <?php if ($payment['appointment_date']): ?>
                <div class="info-section">
                    <h4>ချိန်းဆိုချက်အသေးစိတ်</h4>
                    <div class="info-row">
                        <span class="info-label">ဝန်ဆောင်မှု:</span>
                        <span class="info-value"><?= htmlspecialchars($payment['selected_service_names']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ဝန်ထမ်း:</span>
                        <span class="info-value"><?= htmlspecialchars($payment['staff_name']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ရက်စွဲ:</span>
                        <span class="info-value"><?= date('d/m/Y', strtotime($payment['appointment_date'])) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">အချိန်:</span>
                        <span class="info-value"><?= date('g:i A', strtotime($payment['appointment_time'])) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="info-section">
                <h4>ငွေပေးချေမှုအချက်အလက်</h4>
                <div class="info-row">
                    <span class="info-label">နည်းလမ်း:</span>
                    <span class="info-value"><?= htmlspecialchars($payment['payment_method_name']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">ရက်စွဲ:</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($payment['appointment_date'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">အကောင့်:</span>
                    <span class="info-value">Admin</span>
                </div>
                <div class="info-row">
                    <span class="info-label">ဖုန်း:</span>
                    <span class="info-value">09457688317</span>
                </div>
            </div>

            <div class="amount-section">
                <div class="amount-label">စုစုပေါင်းငွေပမာဏ</div>
                <div class="amount-value"><?= number_format($payment['amount']) ?> ကျပ်</div>
            </div>
        </div>

        <div class="footer">
            <p><strong>Beauty Salon ကို ရွေးချယ်တဲ့အတွက် ကျေးဇူးတင်ပါတယ်!</strong></p>
            <p>ကျေးဇူးပြု၍ ဤဘောင်ချာကို မှတ်တမ်းအတွက် သိမ်းဆည်းထားပါ</p>

        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>

</html>