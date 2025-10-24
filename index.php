<?php
$minDate = date('Y-m-d'); // untuk atribut min pada input date
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pemesanan Tiket - Travel Sederhana</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container" id="main">
    <h1>Pemesanan Tiket Travel</h1>

    <form id="bookingForm" action="booking.php" method="post" novalidate aria-describedby="formNote">
      <p id="formNote" class="muted">Isi data di bawah untuk memesan. Tombol "Pesan Sekarang" aktif setelah semua input valid.</p>

      <div class="grid">
        <fieldset>
          <legend>Data Pemesan</legend>

          <label for="name">Nama lengkap
            <input type="text" id="name" name="name" required aria-required="true" autocomplete="name" />
            <span class="error" id="err-name" aria-live="polite"></span>
          </label>

          <label for="email">Email
            <input type="email" id="email" name="email" required aria-required="true" autocomplete="email" />
            <span class="error" id="err-email" aria-live="polite"></span>
          </label>
        </fieldset>

        <fieldset>
          <legend>Detail Perjalanan</legend>

          <label for="from">Dari
            <input type="text" id="from" name="from" required />
            <span class="error" id="err-from" aria-live="polite"></span>
          </label>

          <label for="to">Ke
            <input type="text" id="to" name="to" required />
            <span class="error" id="err-to" aria-live="polite"></span>
          </label>

          <label for="date">Tanggal berangkat
            <input type="date" id="date" name="date" min="<?php echo $minDate; ?>" required />
            <span class="error" id="err-date" aria-live="polite"></span>
          </label>

          <div class="row">
            <label for="passengers">Jumlah penumpang
              <input type="number" id="passengers" name="passengers" value="1" min="1" required />
              <span class="error" id="err-passengers" aria-live="polite"></span>
            </label>

            <label for="class">Kelas
              <select id="class" name="class" required>
                <option value="Economy">Economy</option>
                <option value="Business">Business</option>
                <option value="First">First</option>
              </select>
              <span class="error" id="err-class" aria-live="polite"></span>
            </label>
          </div>
        </fieldset>
      </div>

      <div class="actions">
        <button type="button" id="previewBtn" class="primary">Pesan Sekarang</button>
        <button type="reset" class="secondary">Atur Ulang</button>
      </div>

      <div id="status" aria-live="polite" class="muted small"></div>
    </form>

    <footer>
      <small>Contoh antarmuka responsif &amp; sederhana — demo lokal saja</small>
    </footer>
  </main>

  <div id="confirmModal" class="modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="confirmTitle">
    <div class="modal-content">
      <h2 id="confirmTitle">Konfirmasi Pesanan</h2>
      <div id="confirmBody" class="confirm-body"></div>
      <div class="modal-actions">
        <button id="confirmSubmit" class="primary">Konfirmasi & Kirim</button>
        <button id="confirmCancel" class="secondary">Batal</button>
      </div>
    </div>
  </div>
  <script>href = "script.js"</script>
</body>
</html>