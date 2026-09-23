<?php

require_once('function.php');
require_once('koneksi.php');

session_start();

if (($_SESSION['role']) != 'operator') {
    include_once('templates/header.php'); // baru include di sini kalau mau tampilkan halaman
    echo "<script>alert('anda tidak memiliki akses')</script>";
    echo "<script>window.location.href='index.php'</script>";
    exit;
}

// Proses simpan HARUS di atas, sebelum header.php di-include
if (isset($_POST['simpan'])) {
    if (tambah_tamu($_POST) > 0) {
        header('Location: buku-tamu.php?status=sukses');
    } else {
        header('Location: buku-tamu.php?status=gagal');
    }
    exit;
}

// Baru include header.php SETELAH semua kemungkinan redirect selesai
include_once('templates/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Buku Tamu</h1>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] === 'sukses') {
    ?>
            <div class="alert alert-success" role="alert">
                Data Berhasil Disimpan!
            </div>
    <?php
        } elseif ($_GET['status'] === 'gagal') {
    ?>
            <div class="alert alert-danger" role="alert">
                Data Gagal Disimpan!
            </div>
    <?php
        }
    }
    ?>
    <!-- lanjutan kode tabel dst tetap sama -->




    <!-- DataTales Example -->
    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#tambahModal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Data Tamu</span>
            </button>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th class="text-nowrap">Nama Tamu</th>
                            <th>Alamat</th>
                            <th>No Telp/HP</th>
                            <th class="text-nowrap">Bertemu Dengan</th>
                            <th>Kepentingan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        // Penomoran auto-increment
                        $no = 1;

                        // Query untuk menampilkan data dari tabel buku_tamu
                        $buku_tamu = query("SELECT * FROM buku_tamu");

                        foreach ($buku_tamu as $tamu) :
                        ?>

                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="text-nowrap"><?= $tamu['tanggal']; ?></td>
                                <td><?= $tamu['nama_tamu']; ?></td>
                                <td class="text-nowrap"><?= $tamu['alamat']; ?></td>
                                <td><?= $tamu['no_hp']; ?></td>
                                <td><?= $tamu['bertemu']; ?></td>
                                <td><?= $tamu['kepentingan']; ?></td>

                                <td class="text-nowrap">
                                    <a class="btn  btn-success"
                                        href="edit-tamu.php?id_tamu=<?= $tamu['id_tamu']; ?>">
                                        Ubah
                                    </a>
                                    <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" class="btn btn-danger"
                                        href="hapus-tamu.php?id=<?= $tamu['id_tamu'] ?>">Hapus</a>



                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>



            </div>


        </div>


    </div>

</div>


<?php
//mengambil data barang dari tabel dengan kode barang terbesar
$query = mysqli_query($koneksi, "SELECT max(id_tamu) as kodeTerbesar FROM buku_tamu");
$data = mysqli_fetch_array($query);
$kodeTamu = $data['kodeTerbesar'];

//mengambil angka dari kode barang terbesar, menggunakan fungsi substr dan diubah ke integer dengan (int)
$urutan = (int) substr($kodeTamu, 2, 3);

//nomor yang diambil akan ditambah 1 untuk menentukan nomor urut berikutnya
$urutan++;

//membuat kode barang baru
//sprintf("%03s", $urutan); berfungsi untuk membuat string menjadi 3 karakter, misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015', jika sprintf("%03s", 1); maka akan menghasilkan '001'

//angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya zt
$huruf = "zt";
$kodeTamu = $huruf . sprintf("%03s", $urutan);


?>










<!-- Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambahModalLabel">Modal title</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="id_tamu" id="id_tamu" value="<?= $kodeTamu ?>">
                    <div class="form-group row">
                        <label for="nama_tamu" class="col-sm-3 col-form-label">Nama Tamu</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_tamu" name="nama_tamu" required>
                        </div>

                    </div>



                    <!-- Semua input -->

                    <div class="form-group row">
                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                id="alamat"
                                name="alamat"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="no_hp" class="col-sm-3 col-form-label">No. Telepon</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                id="no_hp"
                                name="no_hp"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="bertemu" class="col-sm-3 col-form-label">Bertemu dg.</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                id="bertemu"
                                name="bertemu"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kepentingan" class="col-sm-3 col-form-label">Kepentingan</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                id="kepentingan"
                                name="kepentingan"
                                required>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="gambar" class="col-sm-3 col-form-label">Unggah Foto</label>
                        <div class="custom-file col-sm-8">
                            <input type="file" class="custom-file-input" id="gambar" name="gambar">
                            <label class="custom-file-label" for="gambar">Chose File</label>
                        </div>
                    </div>







                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                            Close
                        </button>

                        <button type="submit"
                            name="simpan"
                            class="btn btn-primary">
                            Simpan
                        </button>

                    </div>

                </form>

















                <?php

                include_once('templates/footer.php');

                ?>