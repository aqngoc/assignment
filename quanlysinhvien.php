<?php
ob_start();
session_start();
require 'connect_db.php';

if (!empty($_SESSION['id']) && $_SESSION['role'] == 0) {
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
                            <h4>Xin ch??o <?= $_SESSION['username'] ?></h4>
                        </li>
                        <li><a href='logout.php'><span class='glyphicon glyphicon-user'></span> ????ng xu???t</a></li>
                    <?php
                    } else {
                        echo '<li><a href="register.php"><span class="glyphicon glyphicon-user"></span> ????ng k??</a></li>
                        <li><a href="index.php"><span class="glyphicon glyphicon-log-in"></span> ????ng nh???p</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row content">
                <div class="col-sm-3 sidenav" style="width:20%">
                    <h4>Danh M???c</h4>
                    <div class="list-group">
                        <a href="trangchu.php" class="list-group-item ">Gi???i Thi???u</a>
                        <?php if (!empty($_SESSION['id'])) { ?>
                            <?php if ($_SESSION['role'] == 0) { ?>
                                <a href="quanlysinhvien.php" class="list-group-item active">Qu???n l?? sinh vi??n</a>
                            <?php } ?>
                            <a href="thongtincanhan.php" class="list-group-item">Th??ng tin c?? nh??n</a>
                            <a href="danhsachnguoidung.php" class="list-group-item">Danh s??ch ng?????i d??ng</a>
                            <?php
                            if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                                echo '<a href="giaobai.php" class="list-group-item">Giao b??i</a>';
                            } else {
                                echo '<a href="nopbai.php" class="list-group-item">N???p b??i</a>';
                            }
                            ?>
                            <a href="challenge.php" class="list-group-item">Challenge</a>
                        <?php } ?>

                    </div><br>
                </div>

                <!-- Ki???m tra xem acc c?? role ???????c ph??p hay kh??ng -->
                <?php
                if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                ?>

                    <div class="container">
                        <?php
                        //l???y d??? li???u theo id m???i khi ng?????i d??ng th???c hi???n action view ho???c edit

                        //Kh???i t???o gi?? tr??? cho c??c bi???n tr??nh l???i khi kh??ng c?? action
                        $id = $role = '';
                        $value_username = $value_password = $value_hoten = $value_email = $value_sdt = '';
                        $disabled = '';

                        //N???u click chitiet/sua m???i th???c hi???n l???y th??ng tin d???a v??o id ??i???n v??o form
                        if (!empty($_GET['action']) && !empty($_GET['id'])) {
                            $id = $_GET['id'];
                            $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                            if ($conn->connect_error) {
                                echo '<script>alert("Connect Database fail!")</script>';
                                die();
                            }

                            //L???y to??n b??? th??ng tin user th??ng qua id
                            $stmt = $conn->prepare("SELECT * FROM user WHERE id=? AND role=1");
                            $stmt->bind_param("s", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $result = mysqli_fetch_assoc($result);

                            $stmt->close();
                            $conn->close();


                            if ($result != null) {
                                //Kh???i t???o gi?? tr??? value ????? g??n v??o form
                                $value_username = 'value="' . htmlspecialchars($result['username']) . '"';
                                $value_password = 'value="' . htmlspecialchars($result['password']) . '"';
                                $value_hoten = 'value="' . htmlspecialchars($result['hoten']) . '"';
                                $value_email = 'value="' . htmlspecialchars($result['email']) . '"';
                                $value_sdt = 'value="' . htmlspecialchars($result['sdt']) . '"';


                                if ($result['role'] == 0) $role = 'Gi???ng vi??n';
                                else $role = 'Sinh vi??n';
                            }

                            //N???u action l?? xem chi ti???t th?? g??n attribute disabled ????? ko cho ng?????i d??ng nh???p
                            if ($_GET['action'] == 'view') $disabled = 'disabled';
                        }
                        ?>

                        <!-- Form nh???p/hi???n th??? th??ng tin -->
                        <div class="row">
                            <div class="col-sm-6" style="width: 30% ;">
                                <h1>Th??ng tin sinh vi??n</h1>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label>Username:</label><br>
                                        <input type="text" class="form-control" placeholder="Nh???p Username" name="input_username" <?= $value_username ?> <?= $disabled ?> required>
                                    </div>
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <input type="password" class="form-control" placeholder="Nh???p password" name="input_password" <?= $value_password ?> <?= $disabled ?> required>
                                    </div>
                                    <div class="form-group">
                                        <label>H??? t??n:</label>
                                        <input type="text" class="form-control" placeholder="Nh???p h??? v?? t??n ?????y ?????" name="input_hoten" <?= $value_hoten ?> <?= $disabled ?> required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="email" class="form-control" placeholder="Nh???p email" name="input_email" <?= $value_email ?> <?= $disabled ?> required>
                                    </div>
                                    <div class="form-group">
                                        <label>S??? ??i???n tho???i:</label>
                                        <input type="number" class="form-control" placeholder="Nh???p s??? ??i???n tho???i" name="input_phone" <?= $value_sdt ?> <?= $disabled ?> required>
                                    </div>

                                    <!-- N???u th???c hi???n s???a ho???c xem chi ti???t m???i hi???n th??? ID v?? Role -->
                                    <?php
                                    if (!empty($_GET['action'])) {
                                    ?>
                                        <div class="form-group">
                                            <label>ID:</label>
                                            <input type="number" value="<?= htmlspecialchars($id) ?>" style="width: 50px ;" disabled>
                                            <label>Role:</label>
                                            <input type="text" value="<?= htmlspecialchars($role) ?>" style="width: 100px ;" disabled>
                                        </div>

                                        <!-- S???a t??n button t??y theo ch???c n??ng th??m/s???a v?? kh??ng c?? g?? n???u ??? ch???c n??ng xem chi ti???t -->
                                    <?php
                                        if ($_GET['action'] == 'edit') {
                                            echo '<button type="submit" class="btn btn-primary" name="click_submit">C???p nh???t</button>';
                                        }
                                    } else {
                                        echo '<button type="submit" class="btn btn-primary" name="click_submit">Th??m</button>';
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

                                        //Ki???m tra email v?? phone c?? ????ng ?????nh d???ng
                                        $pattern_email = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,7}$/";
                                        $pattern_phone = "/^[0-9]{10}$/";

                                        if (preg_match($pattern_email, $input_email)) {
                                            if (preg_match($pattern_phone, $input_phone)) {
                                                //x??? l?? khi email v?? phone ???? ????ng ?????nh d???ng
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
                                                    //Khi ???? t???n t???i username, x??c ?????nh xem c?? ph???i action ch???nh s???a kh??ng, n???u ????ng th?? th???c hi???n update
                                                    if (!empty($_GET['action'])) {
                                                        $result = mysqli_fetch_assoc($result);
                                                        $stmt_update = $conn->prepare("UPDATE user SET username=?, password=?, hoten=?, email=?, sdt=? WHERE id=? AND role=1");
                                                        $stmt_update->bind_param("sssssi", $input_username, $input_password, $input_hoten, $input_email, $input_phone, $result['id']);
                                                        $stmt_update->execute();
                                                        $stmt_update->close();
                                                        echo '<script>alert("C???p nh???t th??nh c??ng")</script>';
                                                    } else {
                                                        echo '<script>alert("Username ???? t???n t???i")</script>';
                                                    }
                                                } else {
                                                    //thuc hien them moi user sinhvien
                                                    $stmt_insert = $conn->prepare("INSERT INTO user VALUES (null,?, ?, ?, ?, ?, 1, 0)");
                                                    $stmt_insert->bind_param("sssss", $input_username, $input_password, $input_hoten, $input_email, $input_phone);
                                                    $stmt_insert->execute();
                                                    $stmt_insert->close();
                                                    // $sql='INSERT INTO user VALUES (null,$input_username, $input_password, $input_hoten, $input_email, $input_phone, 1)';
                                                    // QueryData($sql);

                                                    echo '<script>alert("????ng k?? th??nh c??ng")</script>';
                                                }
                                                $stmt_selec->close();
                                                $conn->close();
                                            } else {
                                                echo '<script>alert("S??? ??i???n tho???i kh??ng h???p l???")</script>';
                                            }
                                        } else {
                                            echo '<script>alert("Email kh??ng h???p l???")</script>';
                                        }
                                    } else {
                                        echo '<script>alert("Kh??ng ???????c ????? tr???ng b???t k??? tr?????ng th??ng tin n??o")</script>';
                                    }
                                }

                                ?>

                            </div>
                            <div class="col-sm-6" style="width: 53%">
                                <center>
                                    <h1>Danh s??ch sinh vi??n</h1>
                                </center>
                                <br>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>H??? t??n</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Li???t k?? danh s??ch sinh vi??n (role = 1) -->
                                        <?php
                                        $sql = "SELECT id, hoten FROM user WHERE role=1";
                                        $result_lietke = GetData($sql);
                                        while ($row = mysqli_fetch_assoc($result_lietke)) {
                                        ?>
                                            <tr>
                                                <td><?= $row['id'] ?></td>
                                                <td><?= htmlspecialchars($row['hoten']) ?></td>
                                                <td style="width: 100px ; text-align: center;"><a href="?id=<?= $row['id'] ?>&action=view" class="btn btn-primary">Chi ti???t</a></td>
                                                <td style="width: 50px ; text-align: center;"><a href="?id=<?= $row['id'] ?>&action=edit" class="btn btn-warning">S???a</a></td>
                                                <?php
                                                //sinh token
                                                $token = md5(uniqid());
                                                $_SESSION['token'] = $token;
                                                ?>
                                                <td style="width: 50px ; text-align: center;">
                                                    <a href="./delete.php?id=<?= $row['id'] ?>&token=<?= $token ?>" class="btn btn-danger">X??a</a>
                                                </td>
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
                    echo '<center><h1>Ch??? c?? Role gi??o vi??n m???i c?? th??? truy c???p ch???c n??ng n??y</h1></center>';
                }
                ?>
            </div>
        </div>
    </body>

    </html>
<?php
} else {
    header("Location: trangchu.php");
}
?>