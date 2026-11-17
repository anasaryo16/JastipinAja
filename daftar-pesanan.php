<?php
declare(strict_types=1);

try {
    require __DIR__ . '/koneksi.php';
    $pesanan = $pdo->query(
        'SELECT id, nama_pemesan, no_wa, nama_produk, jumlah, total_bayar
         FROM pesanan
         ORDER BY id DESC'
    )->fetchAll();
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    $error = 'Data pesanan belum dapat dimuat. Periksa koneksi database.';
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Pesanan - Jastipin Aja</title>
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
    <section aria-labelledby="judul-daftar">
      <h2 id="judul-daftar">Daftar Pesanan</h2>
      <?php if (isset($error)): ?>
        <p class="pesan-error"><?= h($error) ?></p>
      <?php elseif ($pesanan === []): ?>
        <p>Belum ada pesanan.</p>
      <?php else: ?>
        <div class="tabel-wrapper">
          <table>
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Nama Pemesan</th>
                <th scope="col">No. WhatsApp</th>
                <th scope="col">Produk</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Total Bayar</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pesanan as $item): ?>
                <tr>
                  <td><?= h((string) $item['id']) ?></td>
                  <td><?= h($item['nama_pemesan']) ?></td>
                  <td><?= h($item['no_wa']) ?></td>
                  <td><?= h($item['nama_produk']) ?></td>
                  <td><?= h((string) $item['jumlah']) ?></td>
                  <td>Rp<?= h(number_format((float) $item['total_bayar'], 0, ',', '.')) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
      <p><a href="index.html#pesan">Buat pesanan baru</a></p>
    </section>
  </main>
</body>
</html>
