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
                <div class="container" style="width: 800px;">
                    <center>
                        <h1>Giải challenge</h1>
                    </center>
                    <br>
                    <?php
                    $id_challenge = $_GET['id_challenge'];
                    $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                    $id_challenge = mysqli_real_escape_string($conn, $id_challenge);
                    $sql = 'SELECT * FROM challenge WHERE id_challenge=' . $id_challenge;
                    $result = mysqli_query($conn, $sql);
                    mysqli_close($conn);

                    $result = mysqli_fetch_assoc($result);
                    var_dump($result);
                    ?>

                    <form action="" method="POST">
                        <div class="form-group">
                            <label>ID challenge:</label><br>
                            <input type="text" class="form-control" value="<?= $result['id_challenge'] ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Tên challenge:</label><br>
                            <input type="text" class="form-control" value="<?= $result['ten_challenge'] ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Gợi ý:</label><br>
                            <input type="text" class="form-control" value="<?= $result['goiy'] ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Đáp án:</label><br>
                            <input type="text" class="form-control" placeholder="Nhập dáp án" name="input_dapan" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" name="click_submit_dapan" value="Submit">
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['click_submit_dapan'])) {
                        if (!empty($_POST['input_dapan'])) {
                            $input_dapan = strtolower($_POST['input_dapan']);
                            $dapan = $result['ten_filechallenge'];

                            $dapan = substr($dapan, 0, -4);
                            if($dapan == $input_dapan ){
                                
                            }
                        } else {
                            echo '<script>alert("Vui lòng nhập đán án")</script>';
                        }
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>