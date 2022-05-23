<?php
ob_start();
session_start();
require 'connect_db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Nộp bài</title>
    <style>
        /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
        .row.content {
            height: 1000px
        }

        /* Set gray background color and 100% height */
        .sidenav {
            background-color: #f1f1f1;
            height: 100%;
        }

        /* Set black background color, white text and some padding */
        footer {
            background-color: #555;
            color: white;
            padding: 15px;
        }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }

            .row.content {
                height: auto;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="trangchu.php">Assignment</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (!empty($_SESSION['username'])) {
                ?>
                    <li style="color:white; margin-top: 5px">
                        <h4>Xin chào <?= $_SESSION['username'] ?></h4>
                    </li>
                    <li><a href='logout.php'><span class='glyphicon glyphicon-user'></span> Đăng xuất</a></li>
                <?php
                } else {
                    echo '<li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Đăng ký</a></li>
                        <li><a href="index.php"><span class="glyphicon glyphicon-log-in"></span> Đăng nhập</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row content" >
            <div class="col-sm-3 sidenav" style="width:15%">
                <h4>Danh Mục</h4>
                <div class="list-group">
                    <a href="trangchu.php" class="list-group-item ">Giới Thiệu</a>
                    <a href="quanlysinhvien.php" class="list-group-item ">Quản lý sinh viên</a>
                    <a href="thongtincanhan.php" class="list-group-item">Thông tin cá nhân</a>
                    <a href="danhsachnguoidung.php" class="list-group-item ">Danh sách người dùng</a>
                    <?php
                    if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                        echo '<a href="giaobai.php" class="list-group-item active">Giao bài</a>';
                    } else {
                        echo '<a href="nopbai.php" class="list-group-item active">Nộp bài</a>';
                    }
                    ?>
                    <a href="challenge.php" class="list-group-item">Challenge</a>

                </div><br>
            </div>

            <div class="row" >
                <div class="col-sm-6" style="width: 700px ;">

                    <center>
                        <h1>Danh sách bài tập</h1>
                    </center>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên bài tập</th>
                                <th>Giáo viên giao bài</th>
                                <th>Đề bài</th>
                                <th style="text-align: center; width: 20px;">Trạng thái</th>
                            </tr>
                        </thead>

                        <?php
                        $sql = 'SELECT baitap.id_baitap, baitap.ten_baitap, baitap.ten_file, baitap.id_giaovien, baitap.hocsinhhoanthanh, user.hoten FROM baitap INNER JOIN user ON baitap.id_giaovien = user.id';
                        $result = GetData($sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tbody>
                                <td><?= $row['id_baitap'] ?></td>
                                <td><?= $row['ten_baitap'] ?></td>
                                <td><?= $row['hoten'] ?></td>
                                <td>
                                    <?php
                                    $file = $row['ten_file']; //Let say If I put the file name Bang.png
                                    echo "<a href='download.php?nama=" . "./baitap/".$file . "'>Download</a> ";

                                    //xử lý kiểm tra học sinh đã nộp bài tập này hay chưa
                                    $hocsinhhoanthanh = $row['hocsinhhoanthanh'];
                                    $hocsinhhoanthanh = explode(',', $hocsinhhoanthanh);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (!in_array($_SESSION['id'], $hocsinhhoanthanh)) {
                                        echo '<button type="button" class="btn btn-danger">Chưa làm</button>';
                                    } else {
                                        echo '<button type="button" class="btn btn-success">Hoàn thành</button>';
                                    }
                                    ?>

                                </td>
                            </tbody>
                        <?php
                        }

                        ?>

                    </table>

                </div>
                <div class="col-sm-6" style="width: 25% ">
                    <center>
                        <h1>Nộp bài</h1>
                    </center>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Chọn bài tập cần nộp:</label><br>
                            <select name="input_baitapcannop" class="form-control">
                                <?php
                                $sql = 'SELECT * FROM baitap';
                                $ketqua = GetData($sql);
                                while ($hang = mysqli_fetch_assoc($ketqua)) {
                                    echo '<option value="' . $hang['id_baitap'] . '">' . $hang['ten_baitap'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Chọn bài làm để upload:</label>
                            <input type="file" name="input_filebailam" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info" name="click_submit_file" value="Nộp bài">
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['click_submit_file'])) {
                        // var_dump($_FILES['input_filebaitap']);
                        //lấy tên file
                        $tenfile = $_FILES['input_filebailam']['name'];
                        if(file_exists("./bailam/".$_POST['input_baitapcannop'])){
                            //đã tồn tại thì ko làm gì cả
                        }else{
                            mkdir("./bailam/".$_POST['input_baitapcannop']);
                        }

                        move_uploaded_file($_FILES['input_filebailam']['tmp_name'], './bailam/' .$_POST['input_baitapcannop'].'/'. $tenfile);
                        //cập nhật id vào danhsach hocsinhhoanthanh
                        $id_baitap_nop = $_POST['input_baitapcannop'];
                        $sql = 'SELECT * FROM baitap WHERE id_baitap='.$id_baitap_nop;
                        $result = GetData($sql);
                        $result = mysqli_fetch_assoc($result);
                        $danhsachhocsinhhoanthanh = $result['hocsinhhoanthanh'];
                        if(strpos($danhsachhocsinhhoanthanh, $_SESSION['id'] )==false){
                            $danhsachhocsinhhoanthanh = $danhsachhocsinhhoanthanh.','.$_SESSION['id'];
                            $sql = "UPDATE baitap SET hocsinhhoanthanh='$danhsachhocsinhhoanthanh' WHERE id_baitap=$id_baitap_nop";
                            QueryData($sql);
                            header('refresh: 0');
                        }else{
                            echo '<script>alert("Bài làm của bạn đã được cập nhật")</script>';
                        }
                    }


                    ?>
                </div>



            </div>
        </div>
        <?php

        ?>
    </div>
    </div>
</body>

</html>