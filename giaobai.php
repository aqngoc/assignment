<?php
ob_start();
session_start();
require 'connect_db.php';

if(!empty($_SESSION['id']) && $_SESSION['role']==0){
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
    <title>Giao bài</title>
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
        <div class="row content">
            <div class="col-sm-3 sidenav" style="width:20%">
                <h4>Danh Mục</h4>
                <div class="list-group">
                <a href="trangchu.php" class="list-group-item ">Giới Thiệu</a>
                    <?php if (!empty($_SESSION['id'])) { ?>
                        <?php if ($_SESSION['role'] == 0) { ?>
                            <a href="quanlysinhvien.php" class="list-group-item ">Quản lý sinh viên</a>
                        <?php } ?>
                        <a href="thongtincanhan.php" class="list-group-item">Thông tin cá nhân</a>
                        <a href="danhsachnguoidung.php" class="list-group-item">Danh sách người dùng</a>
                        <?php
                        if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                            echo '<a href="giaobai.php" class="list-group-item active">Giao bài</a>';
                        } else {
                            echo '<a href="nopbai.php" class="list-group-item active">Nộp bài</a>';
                        }
                        ?>
                        <a href="challenge.php" class="list-group-item">Challenge</a>
                    <?php } ?>

                </div><br>
            </div>

            <?php
            if ($_SESSION['role'] == 0) {
            ?>

                <div class="col-sm-9">
                    <?php
                    if (empty($_GET['id_baitap'])) {
                    ?>
                        <div class="container" style="width: 800px;">
                            <center>
                                <h1>Quản lý bài tập</h1>
                            </center>
                            <br>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Tên bài tập:</label><br>
                                    <input type="text" class="form-control" placeholder="Nhập tên bài tập" name="input_tenbaitap" required>
                                </div>
                                <div class="form-group">
                                    <label>Chọn bài tập để upload:</label>
                                    <input type="file" name="input_filebaitap" required>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" name="click_submit_file" value="Giao bài">
                                </div>
                            </form>
                        </div>
                        <?php
                        if (isset($_POST['click_submit_file'])) {
                            if (!empty($_POST['input_tenbaitap'])) {
                                // var_dump($_FILES['input_filebaitap']);
                                //lấy tên file
                                $tenfile = $_FILES['input_filebaitap']['name'];

                                if (file_exists('./baitap/' . $tenfile)) {
                                    echo '<script>alert("File đã tồn tại")</script>';
                                } else {
                                    move_uploaded_file($_FILES['input_filebaitap']['tmp_name'], './baitap/' . $tenfile);
                                    //luu dia chi file vao database
                                    $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                                    $ten_baitap = mysqli_real_escape_string($conn, $_POST['input_tenbaitap']);
                                    $ten_file = mysqli_real_escape_string($conn, $tenfile);
                                    $sql = "INSERT INTO baitap VALUES (null, '$ten_baitap', '$ten_file', " . $_SESSION['id'] . ",'')";
                                    QueryData($sql);
                                    echo '<script>alert("Upload bài tập thành công")</script>';

                                    mysqli_close($conn);
                                }
                            } else {
                                echo '<script>alert("Bạn chưa nhập tên bài tập")</script>';
                            }
                        }

                        ?>

                        <hr>
                        <div class="container" style="width: 800px;">
                            <h3>Danh sách bài tập</h3>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên bài tập</th>
                                        <th>Giáo viên giao bài</th>
                                        <th style="text-align: center; width: 15%;">Hoàn thành</th>
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
                                        <td style="text-align: center;">
                                            <a href="?id_baitap=<?= $row['id_baitap'] ?>">
                                                <?php
                                                $str = $row['hocsinhhoanthanh'];
                                                if ($str == '') {
                                                    echo 0;
                                                } else {
                                                    $arr = explode(',', $str);
                                                    echo count($arr) - 1;
                                                }

                                                ?>
                                            </a>
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
                        <div class="container" style="width: 800px; ">
                            <center>
                                <h1>
                                    Danh sách bài làm
                                </h1>

                            </center>
                            <br>
                            <?php
                            $id_baitap = $_GET['id_baitap'];

                            $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                            $id_baitap = mysqli_real_escape_string($conn, $id_baitap);
                            $sql = "SELECT * FROM baitap WHERE id_baitap=$id_baitap";
                            $result = mysqli_query($conn, $sql);
                            $result = mysqli_fetch_assoc($result);
                            $tenbaitap = $result['ten_baitap'];
                            mysqli_close($conn);
                            echo "<h3>Bài tập: $tenbaitap</h3>";


                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tên file</th>
                                        <th style="text-align: center; width: 20px;">Download</th>
                                    </tr>
                                </thead>
                                <?php foreach (glob("./bailam/$id_baitap/*") as $baitap) { ?>
                                    <tbody>

                                        <td>
                                            <?php
                                            $thumuc =  "./bailam/$id_baitap/";
                                            $baitap = str_replace($thumuc, '', $baitap);
                                            echo $baitap;
                                            ?>
                                        </td>
                                        <td style="text-align: center"><?php echo "<a href='download.php?nama=" . $baitap . "'><img src='./image/download.png' ></a> "; ?></td>

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
            <?php
            }else{
                echo '<center><h1>Bạn không có quyền truy cập vào chức năng này</h1></center>';
            }

            ?>

        </div>
    </div>

    </div>
    </div>
</body>

</html>
<?php
}else{
    header("Location: trangchu.php");
}
?>