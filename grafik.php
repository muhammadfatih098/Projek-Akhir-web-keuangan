<?php
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/services/NoteServices.php';

Session::start();
if (!Session::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$noteService = new NoteService();
$notes = $noteService->allByUser();

$month = $_GET['month'] ?? date('Y-m');

$income  = 0;
$expense = 0;

foreach ($notes as $n) {
    if (date('Y-m', strtotime($n['created_at'])) !== $month) continue;

    if ($n['type'] === 'income') {
        $income += $n['amount_idr'];
    } else {
        $expense += $n['amount_idr'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grafik Bulanan — <?= APP_NAME ?></title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    margin:0;
    background:#f4f6fb;
    font-family:system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
}
.container{
    max-width:960px;
    margin:40px auto;
    padding:0 20px;
}
.card{
    background:#fff;
    border-radius:18px;
    padding:28px;
    box-shadow:0 12px 32px rgba(0,0,0,.08);
}
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:24px;
}
.header h2{
    margin:0;
    font-size:22px;
}
.controls{
    display:flex;
    gap:12px;
    align-items:center;
}
input[type=month]{
    padding:8px 12px;
    border-radius:10px;
    border:1px solid #ddd;
    font-size:14px;
}
a.back{
    text-decoration:none;
    padding:8px 16px;
    border-radius:10px;
    background:#eee;
    color:#000;
    font-size:14px;
}
.summary{
    display:flex;
    gap:18px;
    margin-bottom:28px;
}
.summary div{
    flex:1;
    padding:16px;
    border-radius:14px;
    background:#f7f8fc;
}
.summary small{color:#666}
.summary strong{
    display:block;
    font-size:20px;
    margin-top:4px;
}
</style>
</head>
<body>

<div class="container">
    <div class="card">

        <div class="header">
            <h2>Grafik Bulanan</h2>
            <div class="controls">
                <form method="get" id="monthForm">
                    <input
                        type="month"
                        name="month"
                        value="<?= htmlspecialchars($month) ?>"
                        onchange="this.form.submit()"
                    >
                </form>
                <a href="dashboard.php" class="back">← Dashboard</a>
            </div>
        </div>

        <div class="summary">
            <div>
                <small>Pemasukan</small>
                <strong style="color:#1e90ff">
                    Rp <?= number_format($income,0,'.','.') ?>
                </strong>
            </div>
            <div>
                <small>Pengeluaran</small>
                <strong style="color:#e74c3c">
                    Rp <?= number_format($expense,0,'.','.') ?>
                </strong>
            </div>
        </div>

        <canvas id="chart" height="120"></canvas>
    </div>
</div>

<script>
const ctx = document.getElementById('chart').getContext('2d');

const gradientIncome = ctx.createLinearGradient(0,0,0,300);
gradientIncome.addColorStop(0,'#6fb1ff');
gradientIncome.addColorStop(1,'#1e90ff');

const gradientExpense = ctx.createLinearGradient(0,0,0,300);
gradientExpense.addColorStop(0,'#ff9a9a');
gradientExpense.addColorStop(1,'#e74c3c');

new Chart(ctx,{
    type:'bar',
    data:{
        labels:['<?= $month ?>'],
        datasets:[
            {
                label:'Pemasukan',
                data:[<?= $income ?>],
                backgroundColor:gradientIncome,
                borderRadius:14,
                categoryPercentage:0.6,
                barPercentage:0.4
            },
            {
                label:'Pengeluaran',
                data:[<?= $expense ?>],
                backgroundColor:gradientExpense,
                borderRadius:14,
                categoryPercentage:0.6,
                barPercentage:0.4
            }
        ]
    },
    options:{
        plugins:{
            legend:{
                position:'bottom',
                labels:{usePointStyle:true,padding:20}
            },
            tooltip:{
                callbacks:{
                    label:c =>
                        c.dataset.label +
                        ': Rp ' +
                        c.raw.toLocaleString('id-ID')
                }
            }
        },
        scales:{
            x:{grid:{display:false}},
            y:{
                beginAtZero:true,
                ticks:{
                    callback:v=>'Rp '+v.toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>

</body>
</html>