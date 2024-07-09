<?php 
// jika tombol tambah ditekan
if (isset($_POST['tambah'])) {
  $jenis_laundry = htmlentities(strip_tags(trim($_POST["jenis_laundry"])));
  $lama_proses = htmlentities(strip_tags(trim($_POST["lama_proses"])));
  $tarif = htmlentities(strip_tags(trim($_POST["tarif"])));
  $pesan_error_jenis = $pesan_error_proses = $pesan_error_tarif = "";

  // Validasi jenis laundry (hanya huruf dan spasi, maksimal 20 karakter)
  if (!preg_match("/^[A-Za-z\s]{1,20}$/", $jenis_laundry)) {
    $pesan_error_jenis = "Jenis laundry hanya boleh berisi huruf dan spasi, maksimal 20 karakter.";
  }

  // Validasi tarif (maksimal 100000)
  if (!is_numeric($tarif) || $tarif <= 0 || $tarif > 100000) {
    $pesan_error_tarif = "Tarif harus berupa angka yang lebih besar dari Rp.0 dan tidak boleh lebih dari Rp.100.000.";
  }

  // Validasi lama proses (harus angka dan maksimal 30 hari)
  if (!is_numeric($lama_proses) || $lama_proses <= 1 || $lama_proses > 30) {
    $pesan_error_proses = "Lama proses harus berupa angka yang lebih besar dari 1 Hari dan tidak boleh lebih dari 30 Hari.";
  }

  // mengecek apakah ada jenis laundry yg sama
  $query_jenis = mysqli_query($conn, "SELECT * FROM tb_jenis WHERE jenis_laundry = '$jenis_laundry'");
  $result_jenis = mysqli_num_rows($query_jenis);
  if ($result_jenis > 0) {
    $pesan_error_jenis = "Jenis <b>$jenis_laundry</b> sudah ada.";
  }

  // jika tidak ada error
  if ($pesan_error_jenis == "" && $pesan_error_proses == "" && $pesan_error_tarif == "") {
    $query = mysqli_query($conn, "INSERT INTO `tb_jenis` (`kd_jenis`, `jenis_laundry`, `lama_proses`, `tarif`) VALUES ('', '$jenis_laundry', '$lama_proses', '$tarif')");
    if ($query) {
      echo "
      <script>
        alert('Data dengan jenis $jenis_laundry berhasil ditambahkan');
        window.location.href = '?page=jenis';
      </script>
      ";

    // jika ada error
    } else {
      $pesan_error = "Data gagal disimpan!";
    }
    
  } else {
    $pesan_error = "Data gagal disimpan!";
  }

} else {
  $pesan_error = "";
  $jenis_laundry = "";
  $lama_proses = "";
  $tarif = "";
}

?>

<div class="page-content-wrapper">
<div class="container-fluid">

  <div class="row">
      <div class="col-sm-12">
          <div class="page-title-box">
              <div class="btn-group float-right">
                  <ol class="breadcrumb hide-phone p-0 m-0">
                      <li class="breadcrumb-item"><a href="index.php">Laundry</a></li>
                      <li class="breadcrumb-item active">Data Jenis Laundry</li>
                      <li class="breadcrumb-item active">Tambah Jenis Laundry</li>
                  </ol>
              </div>
              <h4 class="page-title">Tambah Jenis Laundry</h4>
          </div>
      </div>
  </div>

  <div class="row">
      <div class="col-12">

          <form action="" method="post">
          <div class="card m-b-100">
            <div class="card-body">

              <div class="form-group row">
                <label for="jenis_laundry" class="col-sm-2 col-form-label">Jenis Layanan Laundry</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" id="jenis_laundry" name="jenis_laundry" placeholder="Masukkan jenis laundry" value="<?= $jenis_laundry; ?>" required autofocus/>
                  <?php if ($pesan_error_jenis !== "") : ?>
                    <div class="error" style="color: red; font-size: 12px;"><?= $pesan_error_jenis; ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="lama_proses" class="col-sm-2 col-form-label">Lama Proses (hari)</label>
                <div class="col-sm-10">
                  <input class="form-control" type="number" id="lama_proses" name="lama_proses" placeholder="Masukkan lama proses" value="<?= $lama_proses; ?>" required/>
                  <?php if ($pesan_error_proses !== "") : ?>
                    <div class="error" style="color: red; font-size: 12px;"><?= $pesan_error_proses; ?></div>
                  <?php endif; ?>
                </div>
              </div>     

              <div class="form-group row">
                <label for="tarif" class="col-sm-2 col-form-label">Tarif (Per Kg)</label>
                <div class="col-sm-10">
                  <input class="form-control" type="number" id="tarif" name="tarif" placeholder="Masukkan tarif" value="<?= $tarif; ?>" required/>
                  <?php if ($pesan_error_tarif !== "") : ?>
                    <div class="error" style="color: red; font-size: 12px;"><?= $pesan_error_tarif; ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
              <a href="?page=jenis" class="btn btn-warning">Kembali</a>
            </div>
          </div>
        </form>
      </div>
      <!-- end col -->
    </div>
    <!-- end row -->
  </div>
</div>
<br>
