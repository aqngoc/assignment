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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>QL SV</title>
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
                            <a href="quanlysinhvien.php" class="list-group-item active">Quản lý sinh viên</a>
                        <?php } ?>
                        <a href="thongtincanhan.php" class="list-group-item">Thông tin cá nhân</a>
                        <a href="danhsachnguoidung.php" class="list-group-item">Danh sách người dùng</a>
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

            <!-- Kiểm tra xem acc có role được phép hay không -->
            <?php
            if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
            ?>

                <div class="container" >
                    <?php
                    //lấy dữ liệu theo id mỗi khi người dùng thực hiện action view hoặc edit

                    //Khởi tạo giá trị cho các biến tránh lỗi khi không có action
                    $id = $role = '';
                    $value_username = $value_password = $value_hoten = $value_email = $value_sdt = '';
                    $disabled = '';

                    //Nếu click chitiet/sua mới thực hiện lấy thông tin dựa vào id điền vào form
                    if (!empty($_GET['action']) && !empty($_GET['id'])) {
                        $id = $_GET['id'];
                        $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                        if ($conn->connect_error) {
                            echo '<script>alert("Connect Database fail!")</script>';
                            die();
                        }

                        //Lấy toàn bộ thông tin user thông qua id
                        $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
                        $stmt->bind_param("s", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $result = mysqli_fetch_assoc($result);

                        $stmt->close();
                        $conn->close();

                        //Khởi tạo giá trị value để gán vào form
                        $value_username = 'value="' . $result['username'] . '"';
                        $value_password = 'value="' . $result['password'] . '"';
                        $value_hoten = 'value="' . $result['hoten'] . '"';
                        $value_email = 'value="' . $result['email'] . '"';
                        $value_sdt = 'value="' . $result['sdt'] . '"';


                        if ($result['role'] == 0) $role = 'Giảng viên';
                        else $role = 'Sinh viên';

                        //Nếu action là xem chi tiết thì gán attribute disabled để ko cho người dùng nhập
                        if ($_GET['action'] == 'view') $disabled = 'disabled';
                    }
                    ?>

                    <!-- Form nhập/hiển thị thông tin -->
                    <div class="row">
                        <div class="col-sm-6" style="width: 30% ;">
                            <h1>Thông tin sinh viên</h1>
                            <form action="" method="POST">
                                <div class="form-group">
                                    <label>Username:</label><br>
                                    <input type="text" class="form-control" placeholder="Nhập Username" name="input_username" <?= $value_username ?> <?= $disabled ?> required>
                                </div>
                                <div class="form-group">
                                    <label>Password:</label>
                                    <input type="password" class="form-control" placeholder="Nhập password" name="input_password" <?= $value_password ?> <?= $disabled ?> required>
                                </div>
                                <div class="form-group">
                                    <label>Họ tên:</label>
                                    <input type="text" class="form-control" placeholder="Nhập họ và tên đầy đủ" name="input_hoten" <?= $value_hoten ?> <?= $disabled ?> required>
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="email" class="form-control" placeholder="Nhập email" name="input_email" <?= $value_email ?> <?= $disabled ?> required>
                                </div>
                                <div class="form-group">
                                    <label>Số điện thoại:</label>
                                    <input type="number" class="form-control" placeholder="Nhập số điện thoại" name="input_phone" <?= $value_sdt ?> <?= $disabled ?> required>
                                </div>

                                <!-- Nếu thực hiện sửa hoặc xem chi tiết mới hiển thị ID và Role -->
                                <?php
                                if (!empty($_GET['action'])) {
                                ?>
                                    <div class="form-group">
                                        <label>ID:</label>
                                        <input type="number" value="<?= $id ?>" style="width: 50px ;" disabled>
                                        <label>Role:</label>
                                        <input type="text" value="<?= $role ?>" style="width: 100px ;" disabled>
                                    </div>

                                    <!-- Sửa tên button tùy theo chức năng thêm/sửa và không có gì nếu ở chức năng xem chi tiết -->
                                <?php
                                    if ($_GET['action'] == 'edit') {
                                        echo '<button type="submit" class="btn btn-primary" name="click_submit">Cập nhật</button>';
                                    }
                                } else {
                                    echo '<button type="submit" class="btn btn-primary" name="click_submit">Thêm</button>';
                                }
                                ?>
                            </form>

                            <?php
                            if (isset($_POST['click_submit'])) {
                                if (
                                    !empty($_POST['input_username']) && !empty($_POST['input_password']) && !empty($_POST['input_hoten']) &&
                                    !empty($_POST['input_email']) && !empty($_POST['input_phone'])
                                ) {
                                    $input_username = $_POST['input_username'];
                                    $input_password = hash('sha256', $_POST['input_password']);
                                    $input_hoten = $_POST['input_hoten'];
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

                                            //Kiem tra user da ton tai hay chua
                                            $stmt_selec = $conn->prepare("SELECT * FROM user WHERE username=?");
                                            $stmt_selec->bind_param("s", $input_username);
                                            $stmt_selec->execute();
                                            $result = $stmt_selec->get_result();

                                            if (($result->num_rows) > 0) {
                                                //Khi đã tồn tại username, xác định xem có phải action chỉnh sửa không, nếu đúng thì thực hiện update
                                                if (!empty($_GET['action'])) {
                                                    $result = mysqli_fetch_assoc($result);
                                                    $stmt_update = $conn->prepare("UPDATE user SET username=?, password=?, hoten=?, email=?, sdt=? WHERE id=?");
                                                    $stmt_update->bind_param("sssssi", $input_username, $input_password, $input_hoten, $input_email, $input_phone, $result['id']);
                                                    $stmt_update->execute();
                                                    $stmt_update->close();
                                                    echo '<script>alert("Cập nhật thành công")</script>';
                                                } else {
                                                    echo '<script>alert("Username đã tồn tại")</script>';
                                                }
                                            } else {
                                                //thuc hien them moi user sinhvien
                                                $stmt_insert = $conn->prepare("INSERT INTO user VALUES (null,?, ?, ?, ?, ?, 1)");
                                                $stmt_insert->bind_param("sssss", $input_username, $input_password, $input_hoten, $input_email, $input_phone);
                                                $stmt_insert->execute();
                                                $stmt_insert->close();
                                                // $sql='INSERT INTO user VALUES (null,$input_username, $input_password, $input_hoten, $input_email, $input_phone, 1)';
                                                // QueryData($sql);

                                                echo '<script>alert("Đăng ký thành công")</script>';
                                            }
                                            $stmt_selec->close();
                                            $conn->close();
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
                        <div class="col-sm-6" style="width: 53%">
                            <center><h1>Danh sách sinh viên</h1></center>
                            <br>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Họ tên</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Liệt kê danh sách sinh viên (role = 1) -->
                                    <?php
                                    $sql = "SELECT id, hoten FROM user WHERE role=1";
                                    $result_lietke = GetData($sql);
                                    while ($row = mysqli_fetch_assoc($result_lietke)) {
                                    ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= $row['hoten'] ?></td>
                                            <td style="width: 100px ; text-align: center;"><a href="?id=<?= $row['id'] ?>&action=view" class="btn btn-primary">Chi tiết</a></td>
                                            <td style="width: 50px ; text-align: center;"><a href="?id=<?= $row['id'] ?>&action=edit" class="btn btn-warning">Sửa</a></td>
                                            <td style="width: 50px ; text-align: center;"><a href="./delete.php?id=<?= $row['id'] ?>" class="btn btn-danger">Xóa</a></td>
                                        </tr>


                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php

            } else {
                echo '<center><h1>Chỉ có Role giáo viên mới có thể truy cập chức năng này</h1></center>';
            }
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