<?php

require_once('function.php');
include_once('templates/header.php');

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Buku Tamu</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Daftar Tamu
            </h6>
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
    <button class="btn btn-sm btn-success">Ubah</button>
    <button class="btn btn-sm btn-danger">Hapus</button>
    </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            

        </div>


        </div>


    </div>

</div>
<!-- /.container-fluid -->

<?php

include_once('templates/footer.php');

?>