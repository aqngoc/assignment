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
    <title>Challenge</title>
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
        <div class="row content" style="height: 550px;">
            <div class="col-sm-3 sidenav" style="width:300px">
                <h4>Danh Mục</h4>
                <div class="list-group">
                    <a href="trangchu.php" class="list-group-item ">Giới Thiệu</a>
                    <a href="quanlysinhvien.php" class="list-group-item ">Quản lý sinh viên</a>
                    <a href="thongtincanhan.php" class="list-group-item">Thông tin cá nhân</a>
                    <a href="danhsachnguoidung.php" class="list-group-item ">Danh sách người dùng</a>
                    <?php
                    if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                        echo '<a href="giaobai.php" class="list-group-item">Giao bài</a>';
                    } else {
                        echo '<a href="nopbai.php" class="list-group-item">Nộp bài</a>';
                    }
                    ?>
                    <a href="challenge.php" class="list-group-item active">Challenge</a>

                </div><br>
            </div>



            <div class="col-sm-9">
                <?php
                if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                ?>
                    <div class="container" style="width: 800px;">
                        <center>
                            <h1>Challenge</h1>
                        </center>
                        <br>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Tên challenge:</label><br>
                                <input type="text" class="form-control" placeholder="Nhập tên challenge" name="input_tenchallenge" required>
                            </div>
                            <div class="form-group">
                                <label>Gợi ý:</label><br>
                                <input type="text" class="form-control" placeholder="Nhập gợi ý" name="input_goiy" required>
                            </div>
                            <div class="form-group">
                                <label>Chọn file challenge để upload:</label>
                                <input type="file" name="input_filechallenge" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" name="click_submit_file" value="Tạo">
                            </div>
                        </form>
                    </div>
                    <?php
                    function stripVN($str)
                    {
                        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
                        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
                        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
                        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
                        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
                        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
                        $str = preg_replace("/(đ)/", 'd', $str);

                        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
                        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
                        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
                        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
                        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
                        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
                        $str = preg_replace("/(Đ)/", 'D', $str);
                        return $str;
                    }

                    if (isset($_POST['click_submit_file'])) {
                        if (!empty($_POST['input_tenchallenge']) && !empty($_POST['input_goiy'])) {
                            // var_dump($_FILES['input_filebaitap']);
                            //lấy tên file
                            $ten_filechallenge = $_FILES['input_filechallenge']['name'];
                            $ten_filechallenge = stripVN($ten_filechallenge);
                            $ten_filechallenge = trim(preg_replace("/\s+/"," ", $ten_filechallenge));

                            // var_dump($ten_filechallenge);

                            $ex = strtolower(pathinfo($ten_filechallenge, PATHINFO_EXTENSION));

                            if ($ex == 'txt') {
                                if (file_exists('./challenge/' . $ten_filechallenge)) {
                                    echo '<script>alert("File đã tồn tại")</script>';
                                } else {
                                    move_uploaded_file($_FILES['input_filechallenge']['tmp_name'], './challenge/' . $ten_filechallenge);
                                    //luu dia chi file vao database
                                    $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                                    $ten_challenge = mysqli_real_escape_string($conn, $_POST['input_tenchallenge']);
                                    $ten_filechallenge = mysqli_real_escape_string($conn, $ten_filechallenge);
                                    $goiy = mysqli_real_escape_string($conn, $_POST['input_goiy']);
                                    $sql = "INSERT INTO challenge VALUES (null, '$ten_challenge', '$ten_filechallenge', '$goiy', " . $_SESSION['id'] . ", '')";
                                    mysqli_query($conn, $sql);
                                    echo '<script>alert("Upload challenge thành công")</script>';
                                    mysqli_close($conn);
                                }
                            } else {
                                echo '<script>alert("Chỉ được phép upload file định dạng .txt")</script>';
                            }
                        } else {
                            echo '<script>alert("Không được để trống tên challenge và gợi ý")</script>';
                        }
                    }

                    ?>

                    <hr>
                    <div class="container" style="width: 800px;">
                        <h3>Danh sách challenge</h3>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên challenge</th>
                                    <th>Người tạo challenge</th>
                                    <th style="text-align: center; width: 25%;">Số người hoàn thành</th>
                                </tr>
                            </thead>

                            <?php
                            $sql = 'SELECT challenge.id_challenge, challenge.ten_challenge, challenge.ten_filechallenge, challenge.id_giaovien, challenge.id_hocsinh, user.hoten FROM challenge INNER JOIN user ON challenge.id_giaovien = user.id';
                            $result = GetData($sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tbody>
                                    <td><?= $row['id_challenge'] ?></td>
                                    <td><?= $row['ten_challenge'] ?></td>
                                    <td><?= $row['hoten'] ?></td>
                                    <td style="text-align: center;">
                                        <?php
                                        $str = $row['id_hocsinh'];
                                        if ($str == '') {
                                            echo 0;
                                        } else {
                                            $arr = explode(',', $str);
                                            echo count($arr) - 1;
                                        }

                                        ?>
                                    </td>
                                </tbody>
                            <?php
                            }

                            ?>




                        </table>
                    </div>

                <?php
                } else {

                ?>
                    <div class="container" style="width: 800px;">

                        <center>
                            <h1>Danh sách challenge</h1>
                        </center>
                        <br>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên challenge</th>
                                    <th>Người tạo challenge</th>
                                    <th style="text-align: center; width: 20px;">Trạng thái</th>
                                    <th style="text-align: center; width: 70px;">Chi tiết</th>
                                </tr>
                            </thead>

                            <?php
                            $sql = 'SELECT challenge.id_challenge, challenge.ten_challenge, challenge.ten_filechallenge, challenge.goiy, challenge.id_giaovien, challenge.id_hocsinh, user.hoten FROM challenge INNER JOIN user ON challenge.id_giaovien = user.id';
                            $result = GetData($sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tbody>
                                    <td><?= $row['id_challenge'] ?></td>
                                    <td><?= $row['ten_challenge'] ?></td>
                                    <td><?= $row['hoten'] ?></td>
                                    <td>
                                        <?php
                                        //xử lý kiểm tra học sinh đã nộp bài tập này hay chưa
                                        $hocsinhhoanthanh = $row['id_hocsinh'];
                                        $hocsinhhoanthanh = explode(',', $hocsinhhoanthanh);
                                        if (isset($_SESSION['id']) && in_array($_SESSION['id'], $hocsinhhoanthanh)) {
                                            echo '<button type="button" class="btn btn-success">Hoàn thành</button>';
                                        } else {
                                            echo '<button type="button" class="btn btn-danger">Chưa làm</button>';
                                        }
                                        ?>

                                    </td>
                                    <td style="text-align: center;"><a href="giaichallenge.php?id_challenge=<?= $row['id_challenge'] ?>">Giải</a></td>
                                </tbody>
                            <?php
                            }
                            ?>

                        </table>

                    </div>

                <?php
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