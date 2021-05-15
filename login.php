<?php 
    require 'database.php';
    session_start();

    if (isset($_COOKIE['user']) && isset($_COOKIE['pass'])) {
        $user = $_COOKIE['user'];
        $pass = $_COOKIE['pass'];
        $auth = $db->prepare("SELECT * FROM user_details WHERE username = ? AND password = ?");
        $auth->execute([$user, $pass]);
        $count = $auth->rowCount();
        if ($count == 1) {
            $_SESSION['username'] = $user;
        }
    }

    if (isset($_SESSION['username'])) {
        header("Location: index.php");
    }

    $validSignup = isset($_POST['signup']);
    $validLogin = isset($_POST['login']);
    if ($validSignup) {
        $Dusername = $_POST['Dusername'];
        $first_name = $_POST['firstName'];
        $last_name = $_POST['lastName'];
        $gender = $_POST['gender'];
        $Dpassword = md5($_POST['Dpassword']);
        try {
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $db->prepare("INSERT INTO user_details VALUES (null, ?, ?, ?, ?, ?)");
            $statement->execute([$Dusername, $first_name, $last_name, $gender, $Dpassword]);
        } catch (\Exception $e) {
            $e->getMessage();
        }
    } elseif ($validLogin) {
        $Musername = $_POST['Musername'];
        $Mpassword = md5($_POST['Mpassword']);
        try {
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $data = $db->prepare("SELECT * FROM user_details WHERE username = ? AND password = ?");
            $data->execute([$Musername, $Mpassword]);
            $count = $data->rowCount();
            if ($count == 1) {
                $_SESSION['username'] = $Musername;
                if (isset($_POST['stayIn'])) {
                    setcookie('user', $Musername, time() + (86400 * 3));
                    setcookie('pass', $Mpassword, time() + (86400 * 3));
                }
                header("Location: index.php");
                return;
            } else {
                $e = "Data tidak ada";
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <title>Welcome | PWEB-C</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"> </script>
</head>
<body>
    
<h2 class="text-center my-5">SELAMAT DATANG</h2>
<div class="container d-flex justify-content-center">
    <div class="p-5 col-md-5 border rounded shadow" action="" method="POST">
        <nav>
            <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-login-tab" data-toggle="tab" href="#nav-login" role="tab" aria-controls="nav-login" aria-selected="true">MASUK</a>
                <a class="nav-item nav-link" id="nav-signup-tab" data-toggle="tab" href="#nav-signup" role="tab" aria-controls="nav-signup" aria-selected="false">DAFTAR</a>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <!-- MASUK -->
            <form action="" method="POST" class="tab-pane fade show active" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
                <div class="my-3">
                    <input type="text" class="form-control" name="Musername" placeholder="Username" pattern="[A-Za-z0-9]{1,}" title="Contoh: BudiSanjaya99, budisanjaya, budiSnjy" value="<?= isset($_COOKIE['user']) ? $_COOKIE['user'] : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="Mpassword" placeholder="Password" pattern=".{6,12}" title=" 6 - 12 Karakter" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="checkIn" name="stayIn">
                    <label class="form-check-label" for="checkIn"> Ingat Saya </label>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3" name="login"> Masuk </button>
            </form>

            <!-- DAFTAR -->
            <form action="" method="POST" class="tab-pane fade" id="nav-signup" role="tabpanel" aria-labelledby="nav-signup-tab">
                <div class="my-3">
                    <input type="text" class="form-control" name="Dusername" placeholder="Username" pattern="[A_Za-z0-9]{2,}" title="Contoh: BudiSanjaya99, budisanjaya, budiSnjy" required>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="firstName" placeholder="Nama Depan" pattern="[A-Za-z]{1,}" title="Contoh: Budi, budi" required>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="lastName" placeholder="Nama Belakang" pattern="[A-Za-z]{1,}" title="Contoh: Sanjaya, sanjaya" required>
                </div>
                <div class="mb-3">
                    <select class="form-select" name="gender">
                            <option value='Male' selected> Laki-Laki </option>
                            <option value="Female"> Perempuan </option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="dPass" name="Dpassword" placeholder="Password" pattern=".{6,12}" title=" 6 - 12 Karakter" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="conPass" name="conPassword" placeholder="Konfirmasi Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3" name="signup"> Daftar </button>
            </form>

            <!-- ALERT -->
            <?php 
                if ($validSignup and empty($e)):
            ?>
                <div class='alert alert-success'> Data berhasil didaftarkan. Silahkan masuk terlebih dahulu. </div>
            <?php
                elseif (($validSignup or $validLogin) and !empty($e)):
            ?>
                <div class='alert alert-danger'> Data yang anda masukkan salah! <br> Kesalahan: <?= $e; ?> </div>
            <?php
                endif;
            ?>
        </div>
    </div>
</div>

<script>
    $('#nav-tab a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
        $('.alert').hide();
    });

    function valPass() {
        if ($('#dPass').val() != $('#conPass').val()) {
                $('#conPass')[0].setCustomValidity("Password tidak sama.");
            } else {
                $('#conPass')[0].setCustomValidity("");
            }
    }
    $('#dPass').change(function() {
        valPass();
    });
    $('#conPass').keyup(function() {
        valPass();
    });
</script>
</body>
</html>