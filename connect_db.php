<?php
const HOST = 'localhost';
const DATABASE = 'assignment';
const USERNAME = 'root';
const PASSWORD = '';

function QueryData($sql){
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);

    if($conn->connect_error){
        echo 'Kết nối database thất bại';
        die();
    }

    mysqli_query($conn, $sql);
    mysqli_close($conn);
}


function GetData($sql){
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);

    if($conn->connect_error){
        echo 'Kết nối database thất bại';
        die();
    }

    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $result;
}


?>