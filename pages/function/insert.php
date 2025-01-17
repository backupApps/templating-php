<?php 
session_start();

if (!isset($_POST['nama'])) {
   header("location: form_insert.php");
}

if (isset($_POST['submit'])) {
   require "connect.php";
   
   $nama = $_POST["nama"];
   $nim = $_POST["nim"];
   $prodi = $_POST["prodi_id"];
   $kelas = $_POST["kelas_id"];
   $email = $_POST["email"];
   $alamat = $_POST["alamat"];
   $agama = $_POST["agama"];

   /* insert gambar */
   // deklarasi variable
   $allVar = !$nama || !$nim || !$prodi || !$email || !$alamat || !$agama;

   $gambar = $_FILES["gambar"]["name"];
   $tmpGambar = $_FILES["gambar"]["tmp_name"];
   $folder = "../gambar/" . $gambar;

   // ekstensi gambar
   $ekstensiValid = ['jpg', 'jpeg', 'png'];
   $ekstensi_file = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
   $ekstensiGambar = explode('.', $gambar);
   $ekstensiGambar = end($ekstensiGambar);

   // fungsi waktu
   $_FILES["gambar"]["name"] = date('l, d-m-Y  H:i:s');

   // generate nama baru
   $namaFile = strtolower(md5($_FILES["gambar"]["name"]).'.'.$ekstensiGambar);

   // input tidak hilang saat tombol submit ditekan
   if ($true = true) {
      if (isset($nama)) {
         $_SESSION['nama'] = $nama;
      } if (isset($nim)) {
         $_SESSION['nim'] = $nim;
      } if (isset($prodi)) {
         $_SESSION['prodi_id'] = $prodi;
      } if (isset($kelas)) {
         $_SESSION['kelas_id'] = $kelas;
      } if (isset($email)) {
         $_SESSION['email'] = $email;
      } if (isset($alamat)) {
         $_SESSION['alamat'] = $alamat;
      } if (isset($agama)) {
         $_SESSION['agama'] = $agama;
      } if (isset($gambar)) {
         $_SESSION['gambar'] = $gambar;
      }
   }

   $error = 0;   

   // validasi
   if (empty($nama)) {
      $error = 1;
      $_SESSION['is-invalid-nama'] = 'is-invalid';
      $_SESSION['name-err'] = 'Kolom Nama Tidak Boleh Kosong';
      header("location: form_insert.php");
   } else if (!preg_match("/^[a-zA-Z ]*$/", $nama)) {
      $error = 1;
      $_SESSION['name-err'] = "Nama hanya boleh mengandung huruf dan spasi";
      header("location: form_insert.php");
   }

   if (empty($nim)) {
      $error = 1;
      $_SESSION['is-invalid-nim'] = 'is-invalid';
      $_SESSION['nim-err'] = "Kolom NIM Tidak Boleh Kosong";
      header("location: form_insert.php");
   } else if (!preg_match("/[0-9]\d/", $nim)) {
      $error = 1;
      $_SESSION['nim-err'] = "NIM hanya boleh mengandung angka";
      header("location: form_insert.php");
   }

   if (empty($prodi)) {
      $error = 1;
      $_SESSION['is-invalid-prodi'] = 'is-invalid';
      $_SESSION['prodi-err'] = "Pilih Program Studi";
      header("location: form_insert.php");
   }

   if (empty($kelas)) {
      $error = 1;
      $_SESSION['is-invalid-kelas'] = 'is-invalid';
      $_SESSION['kelas-err'] = "Pilih Kelas";
      header("location: form_insert.php");
   } 

   if (empty($email)) {
      $error = 1;
      $_SESSION['is-invalid-email'] = 'is-invalid';
      $_SESSION['email-err'] = "Kolom Email Tidak Boleh Kosong";
      header("location: form_insert.php");
   } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 1;
      $_SESSION['email-err'] = "Format email tidak valid";
   }

   if (empty($alamat)) {
      $error = 1;
      $_SESSION['is-invalid-alamat'] = 'is-invalid';
      $_SESSION['alamat-err'] = "Kolom Alamat Tidak Boleh Kosong";
      header("location: form_insert.php");
   } else if (!preg_match("/^[a-zA-Z0-9., ]*$/", $alamat)) {
      $error = 1;
      $_SESSION['alamat-err'] = "Hanya boleh angka, huruf, titik dan koma";
      header("location: form_insert.php");
   }

   if (empty($agama)) {
      $error = 1;
      $_SESSION['is-invalid-agama'] = 'is-invalid';
      $_SESSION['agama-err'] = "Kolom Agama Tidak Boleh Kosong";
      header("location: form_insert.php");
   } else if (!preg_match("/^[a-zA-Z ]*$/", $agama)) {
      $error = 1;
      $_SESSION['agama-err'] = "Agama hanya boleh mengandung huruf dan spasi";
      header("location: form_insert.php");
   }

   if (empty($gambar)) {
      $error = 1;
      $_SESSION['is-invalid-gambar'] = 'is-invalid';
      $_SESSION['gambar-err'] = "Pilih Gambar";
      header("location: form_insert.php");
   } else if (!in_array($ekstensi_file, $ekstensiValid)) {
      $error = true;
      $_SESSION['gambar-err'] = "hanya file berekstensi jpg, jpeg dan png";
      header("location: ../form_insert.php");
      exit;
   }

   // upload gambar
   if ($allVar) {
      echo "";
   } else {
      $gambar_upload = move_uploaded_file($tmpGambar, '../gambar/'.$namaFile);
   }
   

   if($error == 0) {
      $sql = "INSERT INTO data_diri (nama, nim, prodi_id, kelas_id, email, alamat, agama, gambar) 
               VALUES ('$nama', '$nim', '$prodi', '$kelas', '$email', '$alamat', '$agama', '$namaFile')";
      $q = mysqli_query($conn, $sql);
      $mar = mysqli_affected_rows($conn);

      if($mar > 0){
         $_SESSION['msg'] = "
                     <script>
                        alert('Proses Insert Berhasil!');
                        document.location.href = '../pages/tables.php';
                     </script>
                  ";
         header("location: ../tables.php");

      }else{
         $_SESSION['msg'] = "
                     <script>
                        alert('Proses Insert Gagal!');
                        document.location.href = '../pages/tables.php';
                     </script>
                  ";
         header("location: ../tables.php");
      }
   } else {
      $_SESSION['all-msg'] = "ISI SEMUA KOLOM";
      header("location: ../form_insert.php");
   }
}
?>
