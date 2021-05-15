<?php
    require 'database.php';
    session_start();
    
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
    }

    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 10;
    $limit_start = ($page - 1) * $limit;
    $data = $db->prepare("SELECT * FROM user_details ORDER BY user_id LIMIT $limit_start, $limit");
    $data->execute();

    // Pagination //
    $rows = $db->prepare("SELECT COUNT(*) AS 'rows' FROM user_details");
    $rows->execute();
    $get_rows = $rows->fetch();
    
    $num_of_page = ceil($get_rows['rows'] / $limit);
    $limit_num_page = 2;
    $start_number = ($page > $limit_num_page) ? $page - $limit_num_page : 1;
    $end_number = ($page < ($num_of_page - $limit_num_page)) ? $page + $limit_num_page : $num_of_page;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <title>QUIZ 2 | PWEB-C</title>
</head>
<body>
    
    <div class="container col-md-9">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between">
                <h2> Selamat Datang, <?= $_SESSION['username']; ?> </h2>
                <div>
                    <a href="logout.php" class="btn btn-warning"> Keluar </a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"> Username </th>
                            <th scope="col"> Nama Depan </th>
                            <th scope="col"> Nama Belakang </th>
                            <th scope="col"> Jenis Kelamin </th>
                            <th scope="col"> Password </th>
                            <th scope="col"> Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $data->fetch()): ?>
                        <tr>
                            <td hidden><?= $row['user_id']; ?></th>
                            <th scope="row"><?= $row['username']; ?></td>
                            <td><?= $row['first_name']; ?></td>
                            <td><?= $row['last_name']; ?></td>
                            <td><?= $row['gender']; ?></td>
                            <td><?= $row['password']; ?></td>
                            <td class="d-grid gap-2 d-md-flex justify-content-md-between">
                                <a href="delete.php?id=<?=$row['user_id'];?>&page=<?=$page;?>" class="btn btn-danger"> Delete </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <nav aria-label="Page Navigation">
                    <ul class="pagination flex-wrap justify-content-center">
                        <?php if ($page == 1): ?>
                            <li class="page-item disabled"><a class="page-link" href="#"> First </a></li>
                            <li class="page-item disabled"><a class="page-link" href="#"> &laquo; </a></li>
                        <?php else:
                            $previous = ($page > 1) ? $page - 1 : 1;
                        ?>
                            <li class="page-item"><a class="page-link" href="index.php?page=1"> First </a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?= $previous; ?>"> &laquo; </a></li>
                        <?php endif;

                            for ($i = $start_number; $i <= $end_number; $i++ ):
                                $active = ($i == $page) ? 'class="page-item active"' : 'class="page-item"';
                        ?>                    
                            <li <?= $active; ?> ><a class="page-link" href="index.php?page=<?= $i; ?>"> <?= $i; ?> </a></li> 
                        <?php endfor;

                        if ($page == $num_of_page):
                        ?>
                            <li class="page-item disabled"><a class="page-link" href="#"> &raquo; </a></li>
                            <li class="page-item disabled"><a class="page-link" href="#"> Last </a></li>
                        <?php else:
                            $next = ($page < $num_of_page) ? $page + 1 : $num_of_page;
                        ?>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?= $next; ?>"> &raquo; </a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?= $num_of_page; ?>"> Last </a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <div class="card-footer text-muted d-flex justify-content-center"> 
                &copy 2021 Arham Zainul Abidin 192410101095
            </div>
        </div>
    </div>

</body>
</html>