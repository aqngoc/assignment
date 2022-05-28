<?php
ob_start();
session_start();

$name = htmlspecialchars($_GET['nama']);
var_dump($name);
if (strpos($name, 'bailam') != false) {
    $regex = '/(.)+\//i';
    $name = preg_replace($regex, '', $name);
    var_dump($name);

    $conn = mysqli_connect('localhost', 'root', '', 'assignment');
    $name = mysqli_real_escape_string($conn, $name);
    $sql = "SELECT * FROM bailam WHERE ten_filebailam= '$name'";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($result);
    mysqli_close($conn);

    if ($result != null && $_SESSION['id'] == $result['id_hocsinh']) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
        header("Content-Disposition: attachment; filename=\"" . basename($name) . "\";");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($name));
        ob_clean();
        flush();
        readfile($name); //showing the path to the server where the file is to be download
        exit;
    } else {
        echo 'Bạn không được phép download bài của người khác';
    }
} else {
    header('Content-Description: File Transfer');
    header('Content-Type: application/force-download');
    header("Content-Disposition: attachment; filename=\"" . basename($name) . "\";");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($name));
    ob_clean();
    flush();
    readfile($name); //showing the path to the server where the file is to be download
    exit;
}
