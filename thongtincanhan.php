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
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
            <div class="col-sm-3 sidenav" style="width:15%">
                <h4>Danh Mục</h4>
                <div class="list-group">
                    <a href="trangchu.php" class="list-group-item ">Giới Thiệu</a>
                    <a href="quanlysinhvien.php" class="list-group-item">Quản lý sinh viên</a>
                    <a href="thongtincanhan.php" class="list-group-item active">Thông tin cá nhân</a>
                    <a href="danhsachnguoidung.php" class="list-group-item">Danh sách người dùng</a>
                    <?php
                    if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                        echo '<a href="giaobai.php" class="list-group-item">Giao bài</a>';
                    } else {
                        echo '<a href="nopbai.php" class="list-group-item">Nộp bài</a>';
                    }
                    ?>
                    <a href="challenge.php" class="list-group-item">Challenge</a>

                </div><br>
            </div>

            <div class="row">
                <?php
                if (!empty($_SESSION['id'])) {

                    $id = $_SESSION['id'];
                    $sql = 'SELECT * FROM user WHERE id=' . $id;
                    $result = GetData($sql);
                    $result = mysqli_fetch_assoc($result);

                    //Khởi tạo giá trị value để gán vào form
                    $value_username = 'value="' . $result['username'] . '"';
                    // $value_password = 'value="' . $result['password'] . '"';
                    $value_hoten = 'value="' . $result['hoten'] . '"';
                    $value_email = 'value="' . $result['email'] . '"';
                    $value_sdt = 'value="' . $result['sdt'] . '"';
                    if ($result['role'] == 0) $value_role = 'Giảng viên';
                    else $value_role = 'Sinh viên';

                ?>
                    <div class="col-sm-6" style="width: 40% ;">
                        <!-- <div style="width: 500px;"> -->
                        <center>
                            <h1>Thông tin cá nhân</h1>
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
                            <!-- <div class="form-group">
                                <label>Password:</label>
                                <input type="password" class="form-control" placeholder="Nhập password" name="input_password" <?= $value_password ?> required>
                            </div> -->
                            <div class="form-group">
                                <label>Họ tên:</label>
                                <input type="text" class="form-control" placeholder="Nhập họ và tên đầy đủ" name="input_hoten" <?= $value_hoten ?> readonly>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" class="form-control" placeholder="Nhập email" name="input_email" <?= $value_email ?> required>
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại:</label>
                                <input type="number" class="form-control" placeholder="Nhập số điện thoại" name="input_phone" <?= $value_sdt ?> required>
                            </div>

                            <button type="submit" class="btn btn-primary" name="click_submit">Cập nhật</button>
                        </form>

                        <?php
                        if (isset($_POST['click_submit'])) {
                            if (
                                !empty($_POST['input_email']) && !empty($_POST['input_phone'])
                            ) {
                                // $input_username = $_POST['input_username'];
                                // $input_password = hash('sha256', $_POST['input_password']);
                                // $input_hoten = $_POST['input_hoten'];
                                $input_email = $_POST['input_email'];
                                $input_phone = $_POST['input_phone'];

                                //Kiểm tra email và phone có đúng định dạng
                                $pattern_email = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,7}$/";
                                $pattern_phone = "/^[0-9]{10}$/";

                                if (preg_match($pattern_email, $input_email)) {
                                    if (preg_match($pattern_phone, $input_phone)) {
                                        //xử lý khi email và phone đã đúng định dạng
                                        $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                                        if ($conn->connect_error) {
                                            echo '<script>alert("Connect Database fail!")</script>';
                                            die();
                                        }
                                        $stmt_update = $conn->prepare("UPDATE user SET email=?, sdt=? WHERE id=?");
                                        $stmt_update->bind_param("ssi", $input_email, $input_phone, $_SESSION['id']);
                                        $stmt_update->execute();
                                        $stmt_update->close();
                                        $conn->close();
                                        echo '<script>alert("Cập nhật thành công")</script>';
                                        header("refresh: 0");
                                    } else {
                                        echo '<script>alert("Số điện thoại không hợp lệ")</script>';
                                    }
                                } else {
                                    echo '<script>alert("Email không hợp lệ")</script>';
                                }
                            } else {
                                echo '<script>alert("Không được để trống bất kỳ trường thông tin nào")</script>';
                            }
                        }
                        ?>
                    </div>
                    <div class="col-sm-6" style=" width: 30% ;">
                        <center>
                            <h1>Tin nhắn</h1>
                        </center>

                        <br>
                        <table class="table table-condensed table-hover">
                            <?php
                            if (empty($_GET['id'])) {

                            ?>

                                <thead>
                                    <tr>
                                        <th>Hộp thoại</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                                    $stmt1 = $conn->prepare('SELECT DISTINCT idgui, username FROM messenger, user WHERE idnhan=? AND idgui=id');
                                    $stmt1->bind_param('i', $_SESSION['id']);
                                    $stmt1->execute();
                                    $result = $stmt1->get_result();
                                    $stmt1->close();
                                    $conn->close();

                                    while ($mess = mysqli_fetch_assoc($result)) {
                                    ?>

                                        <tr>
                                            <td><a href="?id=<?= $mess['idgui'] ?>&username=<?= $mess['username'] ?>">Username: <?= $mess['username'] ?></a></td>
                                            <td style="width: 50%">ID: <?= $mess['idgui'] ?></td>
                                            <td></td>
                                        </tr>


                                    <?php
                                    }
                                    ?>

                                </tbody>

                            <?php
                            } else {
                                $value_mess = '';
                                if (!empty($_GET['idmess']) && !empty($_GET['id']) && !empty($_GET['noidung'])) {
                                    $value_mess = 'value="' . $_GET['noidung'] . '"';
                                }
                            ?>
                                <form action="" method="POST">
                                    <input type="text" size="48%" placeholder="Nhập tin nhắn" name="input_mess" <?= $value_mess ?>>
                                    <input type="submit" name="click_submit_mess">
                                </form>
                                <br>
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
                                <?php
                                $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                                $stmt2 = $conn->prepare('SELECT * FROM messenger WHERE (idnhan=? AND idgui=?) OR (idnhan=? AND idgui=?)');
                                $stmt2->bind_param('iiii', $_SESSION['id'], $_GET['id'], $_GET['id'], $_SESSION['id']);
                                $stmt2->execute();
                                $result2 = $stmt2->get_result();

                                $stmt2->close();
                                $conn->close();
                                ?>
                                <thead>
                                    <tr>
                                        <th>Username: <?= $_GET["username"] ?></th>
                                        <th></th>
                                        <th></th>
                                    </tr>

                                </thead>


                                <?php
                                while ($mess2 = mysqli_fetch_assoc($result2)) {
                                ?>
                                    <tr>
                                        <?php
                                        if ($_SESSION['id'] == $mess2['idgui']) {
                                        ?>
                                            <td style="width: 90%; "><?= $mess2['time'] . ' [you] ' . htmlspecialchars($mess2['noidung']) ?></td>
                                            <td><a href="?idmess=<?= $mess2['idmess'] ?>&id=<?= $_GET['id'] ?>&username=<?= $_GET["username"] ?>&noidung=<?= $mess2['noidung'] ?>"><img src="./image/edit.png" width="70%"></a></td>
                                            <td><a href="delete.php?idmess=<?= $mess2['idmess'] ?>&idttcn=<?= $_GET['id'] ?>&username=<?= $_GET["username"] ?>"><img src="./image/delete.png" width="70%"></a></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="width: 90%;"><?= $mess2['time'] . ' [' . $_GET["username"] . '] ' . htmlspecialchars($mess2['noidung']) ?></td>
                                            <td></td>
                                            <td></td>
                                        <?php

                                        }
                                        ?>

                                    </tr>

                            <?php
                                }
                            }
                            ?>

                        </table>
                    </div>

                <?php
                } else {
                    echo '<center><h1>Bạn phải đăng nhập mới sử dụng được chức năng này</h1></center>';
                }
                ?>
            </div>


        </div>
    </div>
    </div>
</body>

</html>