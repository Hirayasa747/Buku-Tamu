<?php

require_once('function.php');
include_once('templates/header.php');
require_once('koneksi.php');


?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data Users</h1>

    <?php
    //jika ada tombol simpan
    if (isset($_POST['simpan'])) {
        if (tambah_user($_POST) > 0) {

    ?>
            <div class="alert alert-success" role="alert">
                Data Berhasil Disimpan!
            </div>
        <?php
        } else {
        ?>
            <div class="alert alert-danger" role="alert">
                Data Gagal Disimpan!
            </div>
    <?php
        }
    }
    ?>





    <!-- DataTales Example -->
    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#tambahModal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Data Users</span>
            </button>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th class="text-nowrap">User Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        // Penomoran auto-increment
                        $no = 1;

                        // Query untuk menampilkan data dari tabel buku_user
                        $users = query("SELECT * FROM users");

                        foreach ($users as $user) :
                        ?>

                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="text-nowrap"><?= $user['username']; ?></td>
                                <td><?= $user['user_role']; ?></td>

                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-success"
                                        href="edit-user.php?id_user=<?= $user['id_user']; ?>">
                                        Ubah
                                    </a>
                                    <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" class="btn btn-danger"
                                    href="hapus-user.php?id=<?= $user['id_user'] ?>">Hapus</a>
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
$query = mysqli_query($koneksi, "SELECT max(id_user) as kodeTerbesar FROM users");
$data = mysqli_fetch_array($query);
$kodeuser = $data['kodeTerbesar'];

//mengambil angka dari kode barang terbesar, menggunakan fungsi substr dan diubah ke integer dengan (int)
$urutan = (int) substr($kodeuser, 3, 2);

//nomor yang diambil akan ditambah 1 untuk menentukan nomor urut berikutnya
$urutan++;

//membuat kode barang baru
//sprintf("%03s", $urutan); berfungsi untuk membuat string menjadi 3 karakter, misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015', jika sprintf("%03s", 1); maka akan menghasilkan '001'

//angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya zt
$huruf = "usr";
$kodeuser = $huruf . sprintf("%03s", $urutan);


?>










<!-- Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambahModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>

                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="hidden" name="id_user" id="id_user" value="<?= $kodeuser ?>">
                    <div class="form-group row">
                        <label for="username" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                    </div>



                    <!-- Semua input -->

                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label">Password</label>

                        <div class="col-sm-9">
                            <input type="password" class="form-control"
                                id="password"
                                name="password"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="user_role" class="col-sm-3 col-form-label">User Role</label>

                        <div class="col-sm-9">
                            <select class="form-control" id="user_role" name="user_role">
                            <option value="admin">Administrator</option>    
                            <option value="operator">Operator</option>  

                            </select>
                        </div>
                    </div>

                 

                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
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