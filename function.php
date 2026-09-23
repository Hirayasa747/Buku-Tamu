<?php
// panggil file koneksi.php
require_once('koneksi.php');

//membuat query ke/ database
function query($query)
{
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// fungsi untuk generate kode tamu baru berdasarkan data terakhir di database
function generate_kode_tamu()
{
    global $koneksi;

    $query = mysqli_query($koneksi, "SELECT id_tamu FROM buku_tamu ORDER BY id_tamu DESC LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    $kodeTerakhir = $data['id_tamu'] ?? null;
    $urutan = $kodeTerakhir ? (int) substr($kodeTerakhir, 2, 3) : 0;
    $urutan++;

    $huruf = "zt";
    return $huruf . sprintf("%03s", $urutan);
}

// fungsi untuk menambahkan data tamu
function tambah_tamu($data)
{
    global $koneksi;

    $tanggal     = date('Y-m-d');
    $nama_tamu   = htmlspecialchars($data['nama_tamu']);
    $alamat      = htmlspecialchars($data['alamat']);
    $no_hp       = htmlspecialchars($data['no_hp']);
    $bertemu     = htmlspecialchars($data['bertemu']);
    $kepentingan = htmlspecialchars($data['kepentingan']);

    //upload gambar
    $gambar = uploadGambar();
    if (!$gambar) {
        return false;
    }

    
    $maxRetry = 5;
    for ($i = 0; $i < $maxRetry; $i++) {
        $kode = generate_kode_tamu();

        $query = "INSERT INTO buku_tamu (id_tamu, tanggal, nama_tamu, alamat, no_hp, bertemu, kepentingan, gambar)
                   VALUES ('$kode', '$tanggal', '$nama_tamu', '$alamat', '$no_hp', '$bertemu', '$kepentingan', '$gambar')";

        try {
            mysqli_query($koneksi, $query);
            return mysqli_affected_rows($koneksi);
        } catch (mysqli_sql_exception $e) {
           
            if (!str_contains($e->getMessage(), 'Duplicate entry')) {
                throw $e;
            }
          
        }
    }

    
    return false;
}

// fungsi untuk ubah data tamu
function ubah_tamu($data)
{
    global $koneksi;

    $id          = htmlspecialchars($data['id_tamu']);
    $nama_tamu   = htmlspecialchars($data['nama_tamu']);
    $alamat      = htmlspecialchars($data['alamat']);
    $no_hp       = htmlspecialchars($data['no_hp']);
    $bertemu     = htmlspecialchars($data['bertemu']);
    $kepentingan = htmlspecialchars($data['kepentingan']);
    $gambarlama  = htmlspecialchars($data['gambarlama']);

    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarlama;
    } else {
        $gambar = uploadGambar();
        if (!$gambar) {
            $gambar = $gambarlama;
        }
    }

    $query = "UPDATE buku_tamu SET 
                nama_tamu   = '$nama_tamu',
                alamat      = '$alamat',
                no_hp       = '$no_hp',
                bertemu     = '$bertemu',
                kepentingan = '$kepentingan',
                gambar      = '$gambar'
              WHERE id_tamu = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}


//function hapus data tamu
function hapus_tamu($id)
{
    global $koneksi;

    $query = "DELETE FROM buku_tamu WHERE id_tamu = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function tambah_user($data)
{
    global $koneksi;

    $kode  = htmlspecialchars($data['id_user']);
    $username = htmlspecialchars($data['username']);
    $password = htmlspecialchars($data['password']);
    $user_role = htmlspecialchars($data['user_role']);

    //enkripsi password dengan password_hash
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users VALUES ('$kode', '$username', '$password_hash', '$user_role')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function ubah_user($data)
{
    global $koneksi;

    $kode  = htmlspecialchars($data['id_user']);
    $username = htmlspecialchars($data['username']);
    $user_role = htmlspecialchars($data['user_role']);

    $query = "UPDATE users SET
              username      = '$username',  
              user_role     =  '$user_role'
              WHERE id_user = '$kode'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}


//function hapus data user
function hapus_user($id)
{
    global $koneksi;

    $query = "DELETE FROM users  WHERE id_user = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

//function ganti password user
function ganti_password($data)
{
    global $koneksi;
    $kode      = htmlspecialchars($data['id_user']);
    $password  = htmlspecialchars($data['password']);
    $password_hash  = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE users SET
         password       = '$password_hash'
         WHERE id_user    =   '$kode'";

    mysqli_query($koneksi, $query);


    return mysqli_affected_rows($koneksi);
}


function uploadGambar()
{
   
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

  
    if ($error == 4) {
        echo "<script>
                alert('pilih gambar terlebih dahulu!');
              </script>";
        return false;
    }

    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                alert('File yang diunggah harus gambar!');
              </script>";
        return false;
    }

   
    if ($ukuranFile > 1000000) {
        echo "<script>
                alert('Ukuran gambar terlalu besar!');
              </script>";
        return false;
    }

   
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file(
        $tmpName,
        'assets/upload_gambar/' . $namaFileBaru
    );

    return $namaFileBaru;
}