<?php
ob_start();
session_start();
require 'connect_db.php';

if (!empty($_SERVER['HTTP_COOKIE'])) {
    var_dump($_COOKIE['PHPSESSID']);
    var_dump(substr($_SERVER['HTTP_COOKIE'], 10));

    $conn = mysqli_connect('localhost', 'root', '', 'assignment');
    if ($conn->connect_error) {
        echo '<script>alert("Connect Database fail!")</script>';
        die();
    }

    //xóa người dùng
    if (isset($_GET['token']) && $_GET['token'] == $_SESSION['token']) {
        if (!empty($_GET['id'])) {
            if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                $id = $_GET['id'];

                $stmt = $conn->prepare("DELETE FROM user WHERE id=? AND role=1");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                echo $stmt->get_result();
                $stmt->close();
                $conn->close();
                header("Location: quanlysinhvien.php");
            } else {
                echo 'Miss Authorication';
            }
        }
    } else {
        echo 'Token khong khop';
    }

    if (isset($_GET['token']) && $_GET['token'] == $_SESSION['token']) {
        //xóa tin nhắn
        if (!empty($_GET['idmess']) && (!empty($_GET['idttcn']) || !empty($_GET['iddsnd']))) {
            $id = $_GET['idmess'];
            $stmt = $conn->prepare("SELECT * FROM messenger WHERE idmess=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = mysqli_fetch_assoc($stmt->get_result());
            if (isset($_SESSION['id']) && $_SESSION['id'] == $result['idgui']) {
                $stmt = $conn->prepare("DELETE FROM messenger WHERE idmess=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                if (!empty($_GET['idttcn']) && !empty($_GET['username'])) {
                    header("Location: thongtincanhan.php?id=" . $_GET['idttcn'] . "&username=" . $_GET['username']);
                };
                if (!empty($_GET['iddsnd'])) {
                    header("Location: danhsachnguoidung.php?id=" . $_GET['iddsnd']);
                }
            } else {
                echo 'Miss Authortication';
            }


            $stmt->close();
            $conn->close();
        }
    } else {
        echo 'Token khong khop';
    }
} else {
    echo 'Miss Authentication';
}
