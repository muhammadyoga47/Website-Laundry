<?php
if (isset($_POST['tambah'])) {
  $idlaundry = $_POST['id_laundry'];
  $pelangganid = htmlentities(strip_tags(trim($_POST["pelangganid"])));
  $userid = htmlentities(strip_tags(trim($_POST["userid"])));
  $jenis = htmlentities(strip_tags(trim($_POST["id_jenis"])));
  $tarif = htmlentities(strip_tags(trim($_POST["tarif"])));
  $tgl_selesai = htmlentities(strip_tags(trim($_POST["tgl_selesai"])));
  $jml_kilo = htmlentities(strip_tags(trim($_POST["jml_kilo"])));
  $totalbayar = htmlentities(strip_tags(trim($_POST["totalbayar"])));
  $catatan = htmlentities(strip_tags(trim($_POST["catatan"])));
  $status = htmlentities(strip_tags(trim($_POST["status"])));
  $metode_pembayaran = htmlentities(strip_tags(trim($_POST["metode_pembayaran"])));
  $status_pengambilan = 0;
  $tgl_terima = date('Y-m-d');
  $ket_laporan = 1;
  $pesan_error = "";

  // Handling file upload
  $bukti_pembayaran = "";
  if(isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] == 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['bukti_pembayaran']['name']);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES['bukti_pembayaran']['tmp_name']);

    // Check if image file is a actual image or fake image
    if($check !== false) {
      if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $target_file)) {
        $bukti_pembayaran = $target_file;
      } else {
        $pesan_error .= "File tidak dapat diupload. ";
      }
    } else {
      $pesan_error .= "File bukan gambar. ";
    }
  } else {
    $pesan_error .= "File bukti pembayaran tidak ada atau ada kesalahan saat upload. ";
  }

  // Input ke tb_laundry
  $query = "INSERT INTO `tb_laundry` (`id_laundry`, `pelangganid`, `userid`, `kd_jenis`, `tgl_terima`, `tgl_selesai`, `jml_kilo`, `catatan`, `totalbayar`, `status_pembayaran`, `metode_pembayaran`, `bukti_pembayaran`, `status_pengambilan`) VALUES ('$idlaundry', '$pelangganid', '$userid', '$jenis', '$tgl_terima', '$tgl_selesai', '$jml_kilo', '$catatan', '$totalbayar', '$status', '$metode_pembayaran', '$bukti_pembayaran', '$status_pengambilan')";
  $result = mysqli_query($conn, $query);

  // Jika sudah lunas, maka input data transaksi ke tb_laporan
  if ($status == 1) {
    mysqli_query($conn, "INSERT INTO `tb_laporan` (`id_laporan`, `tgl_laporan`, `ket_laporan`, `catatan`, `id_laundry`, `pemasukan`) VALUES ('', '$tgl_terima', '$ket_laporan', '$catatan', '$idlaundry', '$totalbayar')");
  }

  if ($result) {
    echo "
      <script>
        alert('Transaksi $idlaundry berhasil ditambahkan');
        window.location.href = '?page=laundry';
      </script>
    ";
  } else {
    $pesan_error .= "Data gagal disimpan !";
  }

} else {
  $pesan_error = "";
  $pelangganid = "";
  $jenis = "";
  $tarif = "";
  $tgl_selesai = "";
  $jml_kilo = "";
  $totalbayar = "";
  $catatan = "";
  $status = "";
}
?>

<!-- HTML form -->
<div class="page-content-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-title-box">
          <div class="btn-group float-right">
            <ol class="breadcrumb hide-phone p-0 m-0">
              <li class="breadcrumb-item"><a href="index.php">Laundry</a></li>
              <li class="breadcrumb-item active">Data Transaksi Laundry</li>
              <li class="breadcrumb-item active">Tambah</li>
            </ol>
          </div>
          <h4 class="page-title">Tambah Transaksi Laundry</h4>
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

        <form action="" method="post" enctype="multipart/form-data">
          <div class="card m-b-100">
            <div class="card-body">
              <input type="hidden" name="userid" value="<?= $_SESSION['userid']; ?>">

              <?php 
              $q = mysqli_query($conn, "SELECT MAX(RIGHT(id_laundry,4)) AS kd_max FROM tb_laundry");
              $jml = mysqli_num_rows($q);
              $kd = "";
              if ($jml > 0) {
                while ($result = mysqli_fetch_assoc($q)) {
                  $tmp = ((int)$result['kd_max']) + 1;
                  $kd = sprintf("%04s", $tmp);
                }
              } else {
                $kd = "0001";
              }
              $id_laundry = 'LD-' . $kd;
              ?>
              <input type="hidden" name="id_laundry" value="<?= $id_laundry; ?>">

              <div class="form-group row">
                <label for="pelangganid" class="col-sm-2 col-form-label">Nama Pelanggan</label>
                <div class="col-sm-10">
                  <select name="pelangganid" class="select2 form-control">
                    <?php
                    $query2 = mysqli_query($conn, "SELECT * FROM tb_pelanggan");
                    while ($pelanggan = mysqli_fetch_assoc($query2)) :
                      if ($pelanggan['pelangganid'] == $pelangganid) { ?>
                        <option value="<?= $pelanggan['pelangganid']; ?>" selected><?= $pelanggan['pelanggannama']; ?></option>
                      <?php } else { ?>
                        <option value="<?= $pelanggan['pelangganid']; ?>"><?= $pelanggan['pelanggannama']; ?></option>
                      <?php } ?>
                    <?php endwhile; ?>
                  </select>
                </div>
              </div>        

              <div class="form-group row">
                <label for="id_jenis" class="col-sm-2 col-form-label">Jenis Laundry</label>
                <div class="col-sm-10">
                  <select class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;" name="id_jenis" id="id_jenis" onchange="jenis();">
                    <option>--Pilih jenis---</option>
                    <?php 
                    $query = mysqli_query($conn, "SELECT * FROM tb_jenis");
                    while ($result = mysqli_fetch_assoc($query)) :
                      if ($result['kd_jenis'] == $jenis) { ?>
                        <option value="<?= $result['kd_jenis']; ?>" selected><?= $result['jenis_laundry']; ?></option>
                      <?php } else { ?>
                        <option value="<?= $result['kd_jenis']; ?>"><?= $result['jenis_laundry']; ?></option>
                      <?php } ?>
                    <?php endwhile; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="tarif" class="col-sm-2 col-form-label">Tarif (Hari)</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" id="tarif" name="tarif" value="<?= $tarif; ?>" required readonly/>
                </div>
              </div>

              <div class="form-group row">
                <label for="tgl_selesai" class="col-sm-2 col-form-label">Tgl. Selesai</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" id="tgl_selesai" name="tgl_selesai" value="<?= $tgl_selesai; ?>" required readonly />
                </div>
              </div>

              <div class="form-group row">
                <label for="jml_kilo" class="col-sm-2 col-form-label">Jumlah (Kg)</label>
                <div class="col-sm-10">
                  <input class="form-control" type="number" id="jml_kilo" name="jml_kilo" value="<?= $jml_kilo; ?>" onkeyup="sum();" required/>
                </div>
              </div>

              <div class="form-group row">
                <label for="totalbayar" class="col-sm-2 col-form-label">Total Bayar</label>
                <div class="col-sm-10">
                  <input class="form-control" type="number" value="<?= $totalbayar; ?>" id="totalbayar" name="totalbayar" readonly required/>
                </div>
              </div>

              <div class="form-group row">
                <label for="example-text-input" class="col-sm-2 col-form-label">Catatan</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="example-text-input" name="catatan" cols="20" rows="5" placeholder="Masukkan catatan" required><?= $catatan; ?></textarea>
                </div>
              </div>
              
              <div class="form-group row">
              <label for="metode_pembayaran" class="col-sm-2 col-form-label">Metode Pembayaran</label>
              <div class="col-sm-10">
                <select name="metode_pembayaran" class="form-control">
                  <option value='cash' selected>Cash</option>
                  <option value='transfer'>Transfer</option>
                </select>
              </div>
            </div>


              <div class="form-group row">
                <label for="bukti_pembayaran" class="col-sm-2 col-form-label">Bukti Pembayaran</label>
                <div class="col-sm-10">
                  <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control"/>
                </div>
              </div>

              <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                  <select name="status" class="select2 form-control">
                    <?php if($status == 1) { ?>
                      <option value=1 selected>Lunas</option>
                      <option value=0>Belum lunas</option>
                    <?php } elseif($status == 2) { ?>
                      <option value=1>Lunas</option>
                      <option value=0 selected>Belum lunas</option>
                    <?php } else { ?>
                      <option value=0>Belum lunas</option>
                      <option value=1>Lunas</option>
                    <?php } ?>
                  </select>
                </div>
              </div> 

              <button type="submit" name="tambah" class="btn btn-primary tambah" onclick="return confirm('Apakah data yang anda masukkan sudah benar ?');">Tambah</button>
              <a href="?page=pelanggan" class="btn btn-warning">Kembali</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<br>

<script>
  function sum(){
    var jmlKilo = document.getElementById('jml_kilo').value;
    var tarif = document.getElementById('tarif').value;
    var total = parseInt(jmlKilo) * parseInt(tarif);

    if(!isNaN(total)){
      document.getElementById('totalbayar').value = total;
    } else {
      document.getElementById('totalbayar').value = '';
    }
  }

  function jenis(){
    var id = $("#id_jenis").val();

    $.ajax({
      url: "page/laundry/autofill.php",
      data: 'idjenis=' + id,
      success: function (data){
        var json = data,
        obj = JSON.parse(json);

        if (obj.sukses) {
          $('#tarif').val(obj.sukses.tarif);
          $('#tgl_selesai').val(obj.sukses.tgl_selesai);
          $('#jml_kilo').val('');
          $('#totalbayar').val('');
        } else if (obj.gagal) {
          $('#tarif').val('');
          $('#tgl_selesai').val('');
          $('#jml_kilo').val('');
          $('#totalbayar').val('');
        }
      }
    });
  }

  document.getElementById('example-text-input').oninput = function() {
    if (this.value.length > 50) {
      alert('Catatan tidak boleh melebihi 50 karakter.');
      this.value = this.value.substring(0, 50);
    }
  };

  document.querySelector('form').onsubmit = function() {
    var catatan = document.getElementById('example-text-input').value;
    if (catatan.length > 50) {
      alert('Catatan tidak boleh melebihi 50 karakter.');
      return false;
    }
    return true;
  };
</script>
