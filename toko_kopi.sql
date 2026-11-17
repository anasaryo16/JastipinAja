CREATE DATABASE IF NOT EXISTS toko_kopi
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE toko_kopi;

CREATE TABLE IF NOT EXISTS produk (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama_produk VARCHAR(100) NOT NULL,
  harga DECIMAL(12, 2) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_produk_nama (nama_produk)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pesanan (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama_pemesan VARCHAR(100) NOT NULL,
  no_wa VARCHAR(30) NOT NULL,
  nama_produk VARCHAR(100) NOT NULL,
  jumlah INT UNSIGNED NOT NULL,
  total_bayar DECIMAL(12, 2) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

DELETE FROM produk
WHERE nama_produk IN (
  'Keripik Seblak Cikruh',
  'Sambal Matah Bu Made',
  'Sambal Matah Original',
  'Sambal Embe Bali',
  'Sambal Bongkot',
  'Madu Hutan 250 ml',
  'Madu Hutan 500 ml',
  'Madu Sarang',
  'Kopi Gayo Arunika',
  'Madu Hutan Sumbawa'
);

INSERT INTO produk (nama_produk, harga) VALUES
  ('Ayam Geybok Original', 25000.00),
  ('Ayam Geybok Sambal Ijo', 28000.00),
  ('Ayam Geybok Keju', 30000.00),
  ('Nasi Goreng Seafood', 35000.00),
  ('Nasi Goreng Seafood Pedas', 38000.00),
  ('Nasi Goreng Seafood Spesial', 42000.00),
  ('Biji Kopi Gayo 250 g', 85000.00),
  ('Drip Bag Gayo (5 pcs)', 45000.00),
  ('Kopi Gayo Bubuk 200 g', 70000.00),
  ('Pouch Batik Mini', 45000.00),
  ('Pouch Batik Lurik', 68000.00),
  ('Tas Selempang Batik', 95000.00),
  ('Dimsum Ayam Original', 25000.00),
  ('Dimsum Udang', 30000.00),
  ('Dimsum Mentai', 35000.00),
  ('Bakpia Kacang Hijau', 50000.00),
  ('Bakpia Cokelat', 55000.00),
  ('Bakpia Keju', 58000.00),
  ('Masker Medis (10 pcs)', 15000.00),
  ('Plester Luka (10 pcs)', 10000.00),
  ('Hand Sanitizer 100 ml', 18000.00)
ON DUPLICATE KEY UPDATE harga = VALUES(harga);
