<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $conn = mysqli_connect('localhost', 'root', '', 'assignment');
    if ($conn->connect_error) {
        echo '<script>alert("Connect Database fail!")</script>';
        die();
    }

    $stmt = $conn->prepare("DELETE FROM user WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: quanlysinhvien.php");
}
if (!empty($_GET['idmess']) && (!empty($_GET['idttcn']) || !empty($_GET['iddsnd'])) ) {
    $id = $_GET['idmess'];
    $conn = mysqli_connect('localhost', 'root', '', 'assignment');
    if ($conn->connect_error) {
        echo '<script>alert("Connect Database fail!")</script>';
        die();
    }

    $stmt = $conn->prepare("DELETE FROM messenger WHERE idmess=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    if(!empty($_GET['idttcn']) && !empty($_GET['username'])){
        header("Location: thongtincanhan.php?id=".$_GET['idttcn']."&username=".$_GET['username']);
    };
    if(!empty($_GET['iddsnd'])){
        header("Location: danhsachnguoidung.php?id=".$_GET['iddsnd']);
    }
    
}
