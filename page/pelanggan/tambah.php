<?php 
// Tempatkan kode PHP yang relevan di sini, termasuk inisialisasi variabel $pesan_error
$pesan_error_nama = $pesan_error_alamat = $pesan_error_nomortelepon = $pesan_error = "";
$nama = $alamat = $pelanggantelp = $jk = "";

// jika tambah ditekan
if (isset($_POST['tambah'])) {
  // ambil data dari form
  $nama = htmlentities(strip_tags(trim($_POST["pelanggannama"])));
  $jk = htmlentities(strip_tags(trim($_POST["jk"])));
  $alamat = htmlentities(strip_tags(trim($_POST["alamat"])));
  $pelanggantelp = htmlentities(strip_tags(trim($_POST["pelanggantelp"])));

  // Validasi nama hanya mengandung huruf
  if (!preg_match("/^[a-zA-Z\s]*$/", $nama)) {
    $pesan_error_nama .= "Nama hanya boleh mengandung huruf.<br>";
  }

  // Validasi alamat tidak boleh lebih dari 100 karakter
  if (strlen($alamat) > 100) {
    $pesan_error_alamat .= "Alamat tidak boleh lebih dari 100 karakter.<br>";
  }

  // Validasi nomor telepon
  if (strlen($pelanggantelp) !== 12 || !ctype_digit($pelanggantelp)) {
    $pesan_error_nomortelepon .= "Nomor telepon harus terdiri dari 12 digit angka.<br>";
  }

  // Jika tidak ada pesan error, input data ke database
  if ($pesan_error_nama === "" && $pesan_error_alamat === "" && $pesan_error_nomortelepon === "") {
    $query = mysqli_query($conn, "INSERT INTO `tb_pelanggan` (`pelangganid`, `pelanggannama`, `pelangganjk`, `pelangganalamat`, `pelanggantelp`) VALUES ('', '$nama', '$jk', '$alamat', '$pelanggantelp')");
    
    if ($query) {
      echo "
        <script>
          alert('Data dengan Nama $nama berhasil ditambahkan');
          window.location.href = '?page=pelanggan';
        </script>
      ";
    } else {
      $pesan_error .= "Data gagal disimpan!<br>";
    }
  }
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
              <li class="breadcrumb-item active">Data Pelanggan</li>
              <li class="breadcrumb-item active">Tambah Pelanggan</li>
            </ol>
          </div>
          <h4 class="page-title">Tambah Pelanggan</h4>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <?php if ($pesan_error !== "") : ?>
          <div class="alert alert-danger" role="alert">
            <?= $pesan_error; ?>
          </div>
        <?php endif; ?>

        <form action="" method="post">
          <div class="card m-b-100">
            <div class="card-body">
              <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Nama Pelanggan</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" id="nama" name="pelanggannama" placeholder="Masukkan nama pelanggan" value="<?= $nama; ?>" required autofocus/>
                  <?php if ($pesan_error_nama !== "") : ?>
                    <div class="error" style="color: red; font-size: 12px;"><?= $pesan_error_nama; ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                <div class="col-md-9">
                  <div class="form-check-inline my-1">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="customRadio4" name="jk" class="custom-control-input" value="Laki - laki" <?= ($jk == "Laki - laki") ? 'checked' : '' ?>>
                      <label class="custom-control-label" for="customRadio4">Laki - laki</label>
                    </div>
                  </div>
                  <div class="form-check-inline my-1">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="customRadio5" name="jk" class="custom-control-input" value="Perempuan" <?= ($jk == "Perempuan") ? 'checked' : '' ?>>
                      <label class="custom-control-label" for="customRadio5">Perempuan</label>
                    </div>
                  </div>
                </div>
              </div> <!--end row-->         

              <div class="form-group row">
                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="alamat" name="alamat" cols="20" rows="5" placeholder="Masukkan alamat" required><?= $alamat; ?></textarea>
                  <?php if ($pesan_error_alamat !== "") : ?>
                    <div class="error" style="color: red; font-size: 12px;"><?= $pesan_error_alamat; ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="telp" class="col-sm-2 col-form-label">Telp</label>
                <div class="col-sm-10">
                  <input class="form-control" type="number" id="telp" name="pelanggantelp" placeholder="Masukkan No.Telp" value="<?= $pelanggantelp; ?>" required/>
                  <?php if ($pesan_error_nomortelepon !== "") : ?>
                    <div class="error" style="color: red; font-size: 12px;"><?= $pesan_error_nomortelepon; ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
              <a href="?page=pelanggan" class="btn btn-warning">Kembali</a>
            </div>
          </div>
        </form>
      </div>
      <!-- end col -->
    </div>
    <!-- end row -->
  </div>
</div>
