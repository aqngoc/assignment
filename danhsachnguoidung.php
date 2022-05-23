<?php
ob_start();
session_start();
require 'connect_db.php';

if(!empty($_SESSION['id'])){
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
    <title>Danh sách người dùng</title>
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
                        <a href="danhsachnguoidung.php" class="list-group-item active">Danh sách người dùng</a>
                        <?php
                        if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                            echo '<a href="giaobai.php" class="list-group-item">Giao bài</a>';
                        } else {
                            echo '<a href="nopbai.php" class="list-group-item">Nộp bài</a>';
                        }
                        ?>
                        <a href="challenge.php" class="list-group-item">Challenge</a>
                    <?php } ?>
                </div><br>
            </div>


            <?php
            if (empty($_GET['id'])) {
            ?>

                <div class="col-sm-9">
                    <div class="container" style="width: 800px;">
                        <center>
                            <h1>Danh sách người dùng</h1>
                        </center>
                        <br>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Họ tên</th>
                                    <th>Vai trò</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Liệt kê danh sách sinh viên (role = 1) -->
                                <?php
                                $sql = "SELECT * FROM user";
                                $result_lietke = GetData($sql);
                                while ($row = mysqli_fetch_assoc($result_lietke)) {
                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['username'] ?></td>
                                        <td><?= $row['hoten'] ?></td>
                                        <td><?= ($row['role'] == 0) ? 'Giáo viên' : 'Sinh viên' ?></td>
                                        <td style="width: 100px ; text-align: center;"><a href="?id=<?= $row['id'] ?>" class="btn btn-primary">Chi tiết</a></td>
                                    </tr>


                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <!-- hien thi thong tin chi tiet va form tin nhan -->

                <div class="row">
                    <?php
                    $id = $_GET['id'];
                    $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                    $stmt = $conn->prepare('SELECT * FROM user WHERE id=?');
                    $stmt->bind_param('i', $id);
                    $stmt->execute();

                    $result = $stmt->get_result();
                    $result = $result->fetch_assoc();
                    $stmt->close();
                    $conn->close();

                    if ($result['role'] == 0) $value_role = 'Giáo viên';
                    else $value_role = 'Sinh viên';
                    $name = $result['username'];
                    $value_username =  'value="' . $result['username'] . '"';
                    $value_hoten = 'value="' . $result['hoten'] . '"';
                    $value_email = 'value="' . $result['email'] . '"';
                    $value_phone = 'value="' . $result['sdt'] . '"';
                    ?>
                    <div class="col-sm-6" style="width: 500px ;">
                        <center>
                            <h1>Thông tin chi tiết</h1>
                        </center>
                        <br>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label>ID:</label>
                                <input type="number" value="<?= $id ?>" style="width: 50px ;" disabled>
                                <label>Role:</label>
                                <input type="text" value="<?= $value_role ?>" style="width: 100px ;" disabled>
                            </div>
                            <div class="form-group">
                                <label>Username:</label><br>
                                <input type="text" class="form-control" placeholder="Nhập Username" name="input_username" <?= $value_username ?> disabled>
                            </div>
                            <div class="form-group">
                                <label>Họ tên:</label>
                                <input type="text" class="form-control" placeholder="Nhập họ và tên đầy đủ" name="input_hoten" <?= $value_hoten ?> disabled>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" class="form-control" placeholder="Nhập email" name="input_email" <?= $value_email ?> disabled>
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại:</label>
                                <input type="number" class="form-control" placeholder="Nhập số điện thoại" name="input_phone" <?= $value_phone ?> disabled>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-6" style="width: 550px ;">
                        <center>
                            <h1>Để lại lời nhắn</h1>
                        </center>
                        <br>
                        <?php
                        $value_mess = '';
                        if (!empty($_GET['idmess']) && !empty($_GET['id']) && !empty($_GET['noidung'])) {
                            $value_mess = 'value="' . $_GET['noidung'] . '"';
                        }
                        ?>
                        <form action="" method="POST">
                            <input type="text" size="61%" placeholder="Nhập tin nhắn" name="input_mess" <?= $value_mess ?>>
                            <input type="submit" name="click_submit_mess">
                        </form>
                        <?php
                        if (isset($_POST['click_submit_mess'])) {
                            if (!empty($_POST['input_mess'])) {

                                $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                                if (empty($_GET['idmess'])) {
                                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                                    $time = date("[H:i-d/m/Y]");
                                    $stmt = $conn->prepare('INSERT INTO messenger VALUES (null, ?, ?, ?, ?)');
                                    $stmt->bind_param('iiss', $_SESSION['id'], $_GET['id'], $_POST['input_mess'], $time);
                                } else {
                                    $stmt = $conn->prepare('UPDATE messenger SET noidung=? WHERE idmess=?');
                                    $stmt->bind_param('si', $_POST['input_mess'], $_GET['idmess']);
                                    echo '<script>alert("Cập nhật tin nhắn thành công")</script>';
                                }

                                $stmt->execute();
                                $stmt->close();
                                $conn->close();
                            } else {
                                echo '<script>alert("Không được để trống lời nhắn")</script>';
                            }
                        }
                        ?>
                        <br>
                        <table class="table table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th>Nội dung</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                                $stmt1 = $conn->prepare('SELECT * FROM messenger WHERE (idnhan=? AND idgui=?) OR (idnhan=? AND idgui=?)');
                                $stmt1->bind_param('iiii', $_SESSION['id'], $_GET['id'], $_GET['id'], $_SESSION['id']);
                                $stmt1->execute();
                                $result = $stmt1->get_result();
                                $stmt1->close();
                                $conn->close();

                                while ($mess = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <?php
                                        if ($_SESSION['id'] == $mess['idgui']) {
                                        ?>
                                            <td style="width: 90%; "><?= $mess['time'] . ' [you] ' . htmlspecialchars($mess['noidung']) ?></td>
                                            <td><a href="?idmess=<?= $mess['idmess'] ?>&id=<?= $_GET['id'] ?>&noidung=<?= $mess['noidung'] ?>"><img src="./image/edit.png" width="70%"></a></td>
                                            <td><a href="delete.php?idmess=<?= $mess['idmess'] ?>&iddsnd=<?= $_GET['id'] ?>"><img src="./image/delete.png" width="70%"></a></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="width: 90%;"><?= $mess['time'] . ' [' . $name . '] ' . htmlspecialchars($mess['noidung']) ?></td>
                                            <td></td>
                                            <td></td>
                                        <?php
                                        }
                                        ?>

                                    </tr>

                                <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <?php

        ?>
    </div>
    </div>
</body>

</html>
<?php
}else{
    header("Location: trangchu.php");
}
?>