
<div class="page-content-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-title-box">
          <div class="btn-group float-right">
            <ol class="breadcrumb hide-phone p-0 m-0">
              <li class="breadcrumb-item"><a href="#">Laundry</a></li>
              <li class="breadcrumb-item active">Data Transaksi Laundry</li>
            </ol>
          </div>
          <h4 class="page-title">Data Transaksi Laundry</h4>
        </div>
      </div>
    </div>

    <div class="row">
  <div class="col-12">
    <div class="card m-b-30">
      <div class="card-body">
        <div class="table-responsive">
          <h4 class="mt-0 header-title">
            <a href="?page=laundry&aksi=tambah" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Transaksi Laundry</a>
          </h4>
          <h4 class="mt-0 header-title">
            <!-- menampilkan status yang sudah lunas -->
            <a href="?page=laundry&aksi=laundrylunas" class="btn btn-success">Status Lunas</a>
            <!-- menampilkan status yang belum lunas -->
            <a href="?page=laundry&aksi=laundrybelumlunas" class="btn btn-danger">Status Belum Lunas</a>
          </h4>
          <table id="datatable" class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Jenis Layanan</th>
                <th>Tgl. Terima</th>
                <th>Tgl. Selesai</th>
                <th>Status</th>
                <th>Status Baju</th>
                <th>Total Bayar</th>
                <th>Metode Pembayaran</th>
                <th>Bukti Pembayaran</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              // menampilkan data transaksi laundry
              $query = "SELECT * FROM `tb_laundry` 
                        INNER JOIN `tb_pelanggan` ON `tb_laundry`.`pelangganid` = `tb_pelanggan`.`pelangganid` 
                        INNER JOIN `tb_users` ON `tb_users`.`userid` = `tb_laundry`.`userid` 
                        INNER JOIN `tb_jenis` ON `tb_jenis`.`kd_jenis` = `tb_laundry`.`kd_jenis` 
                        ORDER BY `tb_laundry`.`id_laundry` DESC";
              $result = mysqli_query($conn, $query); 
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td><?= $i; ?></td>
                  <td><?= $row['id_laundry']; ?></td>
                  <td><?= $row['pelanggannama']; ?></td>
                  <td><?= $row['jenis_laundry']; ?></td>
                  <td><?= $row['tgl_terima']; ?></td>
                  <td><?= $row['tgl_selesai']; ?></td>
                  <td><?= ($row['status_pembayaran'] == 1) ? '<nav class="badge badge-success">Lunas</nav>' : '<nav class="badge badge-danger">Belum lunas</nav>'; ?></td>
                  <td>
                    <?php if($row['status_pengambilan'] == 0) { ?>
                      <a href="?page=laundry&aksi=diambil&id=<?= $row['id_laundry']; ?>" class="btn btn-warning <?= ($row['status_pembayaran'] == 0) ? 'disabled' : ''; ?>" onclick="return confirm('Apakah anda yakin Baju sudah diambil?');">Belum Diambil</a>
                    <?php } elseif($row['status_pengambilan'] == 1) { ?>
                      <a href="#" class="btn btn-warning disabled">Sudah diambil</a>
                    <?php } ?>
                  </td>
                  <td>Rp. <?= number_format($row['totalbayar']); ?></td>
                  <td><?= $row['metode_pembayaran']; ?></td>
                  <td>
                    <!-- Tampilkan Bukti Pembayaran -->
                    <?php if (!empty($row['bukti_pembayaran'])): ?>
                      <a href="<?= $row['bukti_pembayaran']; ?>" target="_blank">
                        <img src="<?= $row['bukti_pembayaran']; ?>" alt="Bukti Pembayaran" width="100">
                      </a>
                    <?php else: ?>
                      Pembayaran Cash
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="?page=laundry&aksi=detail&id=<?= $row['id_laundry']; ?>" class="btn btn-primary mb-2"><i class="fa fa-eye"></i> Detail</a>
                    <?php if($row['status_pembayaran'] == 1) { ?>
                      <a href="page/cetak_transaksi.php?id=<?= $row['id_laundry']; ?>" class="btn btn-success" target="_blank"><i class="fa fa-download"> Cetak</i></a>
                    <?php } elseif($row['status_pembayaran'] == 0) { ?>
                      <a href="?page=laundry&aksi=lunasi&id=<?= $row['id_laundry']; ?>" class="btn btn-success mb-2" onclick="return confirm('Apakah anda yakin transaksi laundry sudah lunas ?');"><i class="fa fa-money"></i> Lunasi</a>
                      <a href="?page=laundry&aksi=hapus&id=<?= $row['id_laundry']; ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin untuk menghapus ?');"><i class="fa fa-trash-o"></i> Hapus</a>
                    <?php } ?>
                  </td>
                </tr>
              <?php $i++; endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- end col -->
</div>
<!-- end row -->
<!-- end page title end breadcrumb -->
</div>
<!-- container -->
</div>
