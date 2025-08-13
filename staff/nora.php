<?php
require '../require/db.php';
require '../require/common.php';
require '../require/common_function.php';

$user_res = selectData("users", $mysqli, "WHERE role != 'customer' AND role != 'admin'", "*", "ORDER BY role ASC");
$promotions_sql = "SELECT * FROM `promotions` WHERE start_date <= CURDATE() AND end_date >= CURDATE()";
$promotions = $mysqli->query($promotions_sql);
?>
<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Portfolio — Side by Side</title>
    <style>
        :root {
            --bg: #f7f7fb;
            --card: #ffffff;
            --accent: #6b8cff;
            --muted: #6b7280;
            --radius: 12px;
            font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: var(--bg);
            color: #111;
        }

        .container {
            width: 800px;
            margin: 30px auto;
            padding: 24px
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px
        }

        header h1 {
            margin: 0;
            font-size: 1.4rem
        }

        header p {
            margin: 0;
            color: var(--muted)
        }

        /* Project card */
        .project {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: 0 6px 20px rgba(16, 24, 40, 0.06);
            overflow: hidden;
            width: 800px;
            display: flex;
            gap: 0;
            align-items: stretch;
            margin-bottom: 18px
        }

        /* Content area */
        .project .content {
            flex: 1 1 55%;
            padding: 22px
        }

        .project .meta {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 10px
        }

        .badge {
            background: rgba(107, 140, 255, 0.12);
            color: var(--accent);
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.8rem
        }

        .title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0 0 8px
        }

        .desc {
            color: var(--muted);
            line-height: 1.45;
            margin: 0 0 14px
        }

        .tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 14px
        }

        .tag {
            font-size: 0.78rem;
            color: #334155;
            background: #f1f5f9;
            padding: 6px 8px;
            border-radius: 8px
        }

        .actions {
            display: flex;
            gap: 10px
        }

        .btn {
            border: 0;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer
        }

        .btn.secondary {
            background: #eef2ff;
            color: var(--accent)
        }

        .btn.ghost {
            background: transparent;
            border: 1px solid #e6e9f2;
            color: #172554
        }

        /* Small tweaks */
        .projects-grid {
            display: flex;
            flex-direction: column
        }
    </style>
</head>
<a href="../home.php" style="
    display: inline-block;
    margin-left: 30px;
    margin-top: 50px;
    padding: 8px 16px;
    border-radius: 5px;
    background-color: #eee;
    color: #111;
    text-decoration: none;
    font-weight: 600;
    border: none;
    cursor: pointer;
">
    နောက်သို့
</a>
<h2 style="margin-left:250px; font-weight:bold;">ကိုယ်ရေးအကျဥ်းချုပ်...</h2>

<body>
    <div class="container">
        <main class="projects-grid">
            <?php
            if ($user_res->num_rows > 0) {
                while ($data = $user_res->fetch_assoc()) {
                    if ($data['name'] === "နိုရာ") {  // name ကိုစစ်မယ်
            ?>
                        <article class="project">
                            <!-- ပုံဘက် -->
                            <div class="media">
                                <img src="../uplode/<?= htmlspecialchars($data['image']) ?>" alt="<?= htmlspecialchars($data['name']) ?>" style="width:300px; height:400px;">
                            </div>

                            <!-- စာဘက် -->
                            <div class="content">
                                <h2 class="title">အမည် - <?= htmlspecialchars($data['name']) ?></h2>
                                <div class="meta">
                                    <span style="font-weight:bold;">လုပ်ငန်းကျွမ်းကျင်မှု - <?= htmlspecialchars($data['position']) ?></span>
                                </div>
                                <p class="desc"><?= nl2br(htmlspecialchars($data['description'])) ?></p>
                            </div>

                        </article>

            <?php
                    } // end if name check
                }
            }
            ?>
        </main>


    </div>

</body>

</html>