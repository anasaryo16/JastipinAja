<?php
declare(strict_types=1);

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Metode permintaan tidak diizinkan.');
}

$namaPemesan = trim((string) ($_POST['nama_pemesan'] ?? ''));
$noWa = trim((string) ($_POST['no_wa'] ?? ''));
$namaProduk = trim((string) ($_POST['nama_produk'] ?? ''));
$jumlahInput = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT);

if (
    $namaPemesan === ''
    || mb_strlen($namaPemesan) > 100
    || $noWa === ''
    || mb_strlen($noWa) > 30
    || $namaProduk === ''
    || $jumlahInput === false
    || $jumlahInput === null
    || $jumlahInput < 1
) {
    http_response_code(400);
    exit('Data pesanan tidak valid. Silakan kembali dan periksa isian Anda.');
}

try {
    require __DIR__ . '/koneksi.php';

    $productQuery = $pdo->prepare(
        'SELECT nama_produk, harga FROM produk WHERE nama_produk = :nama_produk'
    );
    $productQuery->execute(['nama_produk' => $namaProduk]);
    $produk = $productQuery->fetch();

    if ($produk === false) {
        error_log('Katalog produk tidak sinkron untuk pesanan: ' . $namaProduk);
        http_response_code(500);
        exit('Produk belum tersedia di database. Sinkronkan database toko_kopi dengan menjalankan toko_kopi.sql, lalu muat ulang halaman dan coba lagi.');
    }

    $subtotal = (int) round((float) $produk['harga']) * $jumlahInput;
    if ($subtotal > 1_000_000) {
        $totalBayar = (int) round($subtotal * 0.85);
    } elseif ($subtotal > 500_000) {
        $totalBayar = (int) round($subtotal * 0.92);
    } else {
        $totalBayar = $subtotal;
    }

    $orderQuery = $pdo->prepare(
        'INSERT INTO pesanan (nama_pemesan, no_wa, nama_produk, jumlah, total_bayar)
         VALUES (:nama_pemesan, :no_wa, :nama_produk, :jumlah, :total_bayar)'
    );
    $orderQuery->execute([
        'nama_pemesan' => $namaPemesan,
        'no_wa' => $noWa,
        'nama_produk' => $produk['nama_produk'],
        'jumlah' => $jumlahInput,
        'total_bayar' => $totalBayar,
    ]);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    exit('Pesanan belum dapat disimpan. Periksa koneksi database, lalu coba lagi.');
}

$totalTampil = 'Rp' . number_format($totalBayar, 0, ',', '.');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Diterima - Jastipin Aja</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Jastipin Aja</h1>
    <nav aria-label="Navigasi utama">
      <a href="index.html">Beranda</a>
      <a href="daftar-pesanan.php">Daftar Pesanan</a>
    </nav>
  </header>
  <main>
    <section class="konfirmasi">
      <h2>Pesanan Berhasil</h2>
      <p>Terima kasih, <?= h($namaPemesan) ?>. Pesanan Anda sudah kami terima.</p>
      <p>Total bayar setelah diskon: <strong><?= h($totalTampil) ?></strong></p>
      <p><a href="index.html">Kembali ke Jastipin Aja</a></p>
    </section>
  </main>
</body>
</html>
