<?php
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/services/NoteServices.php';
require_once __DIR__ . '/app/services/CurrencyService.php';

Session::start();
if (!Session::isLoggedIn()) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$user = Session::user();
$noteService = new NoteService();
$currencyService = new CurrencyService();

$summary = $noteService->summary();
$notes   = $noteService->allByUser();

$currency = $_SESSION['currency'] ?? 'USD';
$rate     = $currencyService->rateToIdr($currency);
$symbol   = $currencyService->symbol($currency);
$decimals = $currency === 'JPY' ? 0 : 2;

$flags = [
    'USD' => '🇺🇸',
    'MYR' => '🇲🇾',
    'JPY' => '🇯🇵',
    'SGD' => '🇸🇬',
    'EUR' => '🇪🇺',
];
$currentFlag = $flags[$currency] ?? '🌍';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — <?= APP_NAME ?></title>
<link rel="stylesheet" href="assets/css/dashboard.css">
<style>
.note-card{position:relative}
.note-dropdown{
    position:absolute;
    right:12px;
    top:100%;
    display:none;
    flex-direction:column;
    background:#fff;
    border-radius:8px;
    box-shadow:0 6px 16px rgba(0,0,0,.12);
    z-index:9999;
}
.note-dropdown button{
    background:none;
    border:0;
    padding:10px 14px;
    text-align:left;
    cursor:pointer;
}
.note-dropdown button:hover{background:#f2f2f2}
</style>
</head>
<body>

<header class="dash-top">
    <div class="dash-brand"><?= APP_NAME ?></div>
    <div class="profile">
        <button class="profile-btn">
    <?= strtoupper(substr($user['email'], 0, 1)) ?>
</button>
       <div class="profile-menu">
    <div class="profile-avatar">
        <span><?= strtoupper(substr($user['email'], 0, 1)) ?></span>
    </div>

    <div class="profile-info">
        <small><?= htmlspecialchars($user['email']) ?></small>
    </div>

    <div class="profile-divider"></div>

    <a href="<?= BASE_URL ?>/logout.php">Logout</a>
</div>
</header>

<main class="dash-container">

<section class="dash-summary">
<?php foreach ([
    ['Saldo',$summary['balance'],'saldo'],
    ['Pemasukan',$summary['income'],'masuk'],
    ['Pengeluaran',$summary['expense'],'keluar'],
] as [$label,$val,$cls]): ?>
<div class="card <?= $cls ?>">
    <span><?= $label ?></span>
    <h2>Rp <?= number_format($val,0,'.','.') ?></h2>
    <span class="usd">
        <?= $currency ?> <?= $symbol ?><?= number_format(
            $currencyService->fromIdr($val,$currency),
            $decimals
        ) ?>
    </span>
</div>
<?php endforeach; ?>
</section>

<section class="dash-actions">
    <a href="<?= BASE_URL ?>/add-note.php" class="btn primary">+ Tambah Catatan</a>
    <a href="<?= BASE_URL ?>/timer.php" class="btn secondary">⏱ Timer</a>
    <a href="<?= BASE_URL ?>/grafik.php" class="btn secondary">📊 Grafik</a>

    <div class="card currency-card">
        <div class="flag-selector" id="flagSelector">
            <button class="flag-btn"><?= $currentFlag ?></button>
            <div class="flag-menu">
                <?php foreach ($flags as $code => $flag): if ($code !== $currency): ?>
                    <a href="set-currency.php?currency=<?= $code ?>"><?= $flag ?></a>
                <?php endif; endforeach; ?>
            </div>
        </div>

        <div class="currency-rate">
            <small>1 <?= $currency ?> =</small>
            <strong>Rp <?= number_format($rate,0,'.','.') ?></strong>
        </div>
    </div>
</section>

<section class="dash-notes">
<?php if (!$notes): ?>
<p>Belum ada aktivitas.</p>
<?php else: foreach (array_reverse($notes) as $note): ?>
<div class="note-card <?= $note['type'] ?>" data-id="<?= $note['id'] ?>">
    <div class="note-info">
        <strong><?= htmlspecialchars($note['title']) ?></strong>
        <small><?= $note['type']==='income'?'Pemasukan':'Pengeluaran' ?></small>
        <span class="note-time">
            <?= date('d M Y H:i', strtotime($note['created_at'])) ?>
        </span>
    </div>

    <div class="note-amount">
        Rp <?= number_format($note['amount_idr'],0,'.','.') ?>
        <span class="usd">
            <?= $currency ?> <?= $symbol ?><?= number_format(
                $currencyService->fromIdr($note['amount_idr'],$currency),
                $decimals
            ) ?>
        </span>
    </div>

    <div class="note-dropdown">
        <button class="edit-btn">Edit</button>
        <button class="delete-btn">Hapus</button>
    </div>
</div>
<?php endforeach; endif; ?>
</section>

</main>

<script>
const profile = document.querySelector('.profile');
const profileBtn = profile.querySelector('.profile-btn');
const profileMenu = profile.querySelector('.profile-menu');

profileBtn.addEventListener('click', e => {
    e.stopPropagation();
    profile.classList.toggle('open');
});
                    
profileMenu.addEventListener('click', e => {
    e.stopPropagation();
});

document.addEventListener('click', () => {
    profile.classList.remove('open');
});

    
const fs = document.getElementById('flagSelector');
fs.querySelector('.flag-btn').onclick = e => {
    e.stopPropagation();
    fs.classList.toggle('open');
};
document.addEventListener('click', () => fs.classList.remove('open'));

    
const dashNotes = document.querySelector('.dash-notes');

dashNotes.addEventListener('click', e => {
    const card = e.target.closest('.note-card');
    if (!card) return;

    e.stopPropagation();
    const dropdown = card.querySelector('.note-dropdown');

    if (e.target.classList.contains('edit-btn')) {
        const titleEl  = card.querySelector('.note-info strong');
        const amountEl = card.querySelector('.note-amount');

        const newTitle = prompt('Edit keterangan:', titleEl.textContent);
        if (newTitle === null) return;

        const newAmount = prompt(
            'Edit jumlah IDR:',
            parseInt(amountEl.textContent.replace(/\D/g,''))
        );
        if (newAmount === null) return;

        fetch('<?= BASE_URL ?>/edit-note.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: `id=${card.dataset.id}&title=${encodeURIComponent(newTitle)}&amount_idr=${newAmount}`
        }).then(() => location.reload());

        dropdown.style.display = 'none';
        return;
    }

    if (e.target.classList.contains('delete-btn')) {
        if (!confirm('Yakin ingin menghapus catatan ini?')) return;

        fetch('<?= BASE_URL ?>/delete-note.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: `id=${card.dataset.id}`
        }).then(() => card.remove());

        return;
    }

    document.querySelectorAll('.note-dropdown')
        .forEach(d => d !== dropdown && (d.style.display = 'none'));

    dropdown.style.display =
        dropdown.style.display === 'flex' ? 'none' : 'flex';
});

document.addEventListener('click', () => {
    document.querySelectorAll('.note-dropdown')
        .forEach(d => d.style.display = 'none');
});
</script>

</body>
</html>