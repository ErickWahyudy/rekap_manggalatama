<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $judul ?></title>
    <meta name="keywords" content="<?= $nama_judul ?>, <?= $meta_keywords ?>, <?= $meta_description ?>, kassandra, kassandra hd production, KASSANDRA, KASSANDRA HD PRODUCTION">
    <meta name="description" content="<?= $nama_judul ?>, <?= $meta_keywords ?>, <?= $meta_description ?>">
    <meta name="author" content="KASSANDRA, KASSANDRA HD PRODUCTION">
    <meta content='index,follow' name='robots'/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 4.5.2 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Favicon -->
    <link href="<?= base_url('themes') ?>/favicon.ico" rel="icon">

    <!-- sweetalert -->
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

    <style>
        .bottom-left {
            position: fixed;
            bottom: 20px;
            left: 20px;
            color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
            font-size: 36px; /* Sesuaikan ukuran sesuai kebutuhan Anda */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .clock {
            font-weight: bold;
        }

        .date {
            font-weight: normal;
            font-size: 24px; /* Sesuaikan ukuran sesuai kebutuhan Anda */
        }
    </style>
</head>

<body class="hold-transition login-page" background="<?= base_url('themes/foto_background/'.$background) ?>" style="background-size: cover; background-attachment: fixed;">
<!-- Menambah jam dan tanggal di pojok kiri bawah seperti windows 11 -->
<div class="bottom-left">
    <div id="clock" class="clock"></div>
    <div id="date" class="date"></div>
</div>

<div class="login-box">
    <?= $this->session->flashdata('pesan') ?>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="card-title">
                          <b> Login Web App <br>
                              <?= $nama_judul ?>
                          </b>
                        </h4>
                    </div>

                    <div class="card-body">
                        <form id="loginForm">
                            <div class="input-group mb-3">
                                <input type="text" name="email" class="form-control" placeholder="Email" required="" autocomplete="off">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span> &ensp;
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password" required="" autocomplete="off">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                            </div>
                        </form>

                        <center>
                            <br><br>
                            <strong>Copyright &copy; <?php echo date('Y'); ?>
                            <?php $nama_judul = $this->db->get('tb_pengaturan')->row_array(); ?>
                            <a href="https://bit.ly/kassandrahdproduction" target="blank"><?= $nama_judul['nama_judul'] ?></a>.</strong> All rights reserved.
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tautan ke file JavaScript jQuery (jika diperlukan) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    //jam dan tanggal
    function updateTime() {
        const now = new Date();
        const clockElement = document.getElementById("clock");
        const dateElement = document.getElementById("date");

        const options = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
        const formattedDate = now.toLocaleDateString("id-ID", options);
        const timeString = now.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" });

        clockElement.textContent = timeString;
        dateElement.textContent = formattedDate;
    }

    // Memanggil updateTime setiap detik
    setInterval(updateTime, 1000);

    // Memanggil updateTime untuk pertama kali
    updateTime();

    $(document).ready(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('login') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status === 'success') {
                    swal({
                        title: "Login Berhasil",
                        text: "Anda akan diarahkan ke halaman utama",
                        type: "success",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    }).then(function() {
                        window.location.href = data.redirect; // Redirect berdasarkan data dari server
                    });
                } else {
                    swal({
                        title: "Login Gagal",
                        text: data.message, // Pesan error dari server
                        type: "error",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    });
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                swal({
                    title: "Error",
                    text: "Terjadi kesalahan saat mengirim data",
                    type: "error",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            }
        });
    });
});

</script>
</body>
</html>
