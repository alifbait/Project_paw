<?php
function safe($v) {
    return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Ambil dan sanitasi
$name = safe($_POST['name'] ?? '');
$email = safe($_POST['email'] ?? '');
$from = safe($_POST['from'] ?? '');
$to = safe($_POST['to'] ?? '');
$date = safe($_POST['date'] ?? '');
$passengers = (int)($_POST['passengers'] ?? 1);
$kelas = safe($_POST['class'] ?? 'Economy');

// Validasi sederhana server-side
$errors = [];
if ($name === '') $errors[] = 'Nama harus diisi.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
if ($from === '') $errors[] = 'Asal harus diisi.';
if ($to === '') $errors[] = 'Tujuan harus diisi.';
if ($date === '') $errors[] = 'Tanggal berangkat harus diisi.';
else {
    // pastikan tanggal tidak di masa lalu
    $today = new DateTime('today');
    $d = DateTime::createFromFormat('Y-m-d', $date);
    if (!$d || $d < $today) $errors[] = 'Tanggal tidak boleh di masa lalu.';
}
if ($passengers < 1) $errors[] = 'Jumlah penumpang minimal 1.';

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Konfirmasi Pemesanan - Travel Sederhana</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main class="container">
    <h1>Hasil Pemesanan</h1>

    <?php if (!empty($errors)): ?>
      <div class="error">
        <h2>Terjadi kesalahan:</h2>
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?php echo $e; ?></li>
          <?php endforeach; ?>
        </ul>
        <p><a href="index.php" class="button">Kembali ke form</a></p>
      </div>
    <?php else: ?>
      <?php
        // Simpan ringkasan sederhana ke file (opsional)
        $entry = [
          'time' => date('c'),
          'name' => $name,
          'email' => $email,
          'from' => $from,
          'to' => $to,
          'date' => $date,
          'passengers' => $passengers,
          'class' => $kelas
        ];
        $line = json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        @file_put_contents('bookings.txt', $line, FILE_APPEND | LOCK_EX);
      ?>

      <div class="summary">
        <p>Terima kasih, <strong><?php echo $name; ?></strong>. Pesanan Anda telah diterima dengan rincian:</p>
        <table>
          <tr><td>Nama</td><td><?php echo $name; ?></td></tr>
          <tr><td>Email</td><td><?php echo $email; ?></td></tr>
          <tr><td>Dari</td><td><?php echo $from; ?></td></tr>
          <tr><td>Ke</td><td><?php echo $to; ?></td></tr>
          <tr><td>Tanggal</td><td><?php echo $date; ?></td></tr>
          <tr><td>Penumpang</td><td><?php echo $passengers; ?></td></tr>
          <tr><td>Kelas</td><td><?php echo $kelas; ?></td></tr>
        </table>

        <p class="note">Ini hanya contoh antarmuka sederhana. Untuk produksi: gunakan database, aturan autentikasi &amp; otorisasi, sanitasi, dan proteksi CSRF.</p>

        <p class="actions">
          <a class="button" href="index.php">Buat Pesanan Baru</a>
        </p>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>