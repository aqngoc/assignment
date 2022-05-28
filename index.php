<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Login</title>
</head>

<body style="background-image: url('./image/background_login.jpg'); background-size: cover;">
    <div style="width: 600px; padding: 5%">
        <h2>Đăng nhập</h2>
        <form action="" method="POST">
            <div class="mb-3 mt-3">
                <label>Username:</label><br>
                <input type="text" class="form-control" placeholder="Nhập Username" name="input_username" required>
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" class="form-control" placeholder="Nhập password" name="input_password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="click_submit">Login</button>
            <a style="margin-left: 20px;" href="./register.php">Đăng ký</a>
        </form>

        <?php
        if (isset($_POST['click_submit'])) {
            if (!empty($_POST['input_username']) && !empty($_POST['input_password'])) {
                $input_username = $_POST['input_username'];
                $input_password = hash('sha256', $_POST['input_password']);

                $conn = mysqli_connect('localhost', 'root', '', 'assignment');
                if ($conn->connect_error) {
                    echo '<script>alert("Connect Database fail!")</script>';
                    die();
                }

                $stmt = $conn->prepare("SELECT * FROM user WHERE username=?");
                $stmt->bind_param("s", $input_username);
                $stmt->execute();

                //Lấy kết quả, kiểm tra nếu tồn tại bản ghi -> tài khoản và mật khẩu chính xác
                $result = $stmt->get_result();
                $arrresult = mysqli_fetch_assoc($result);
                if ($arrresult['block'] <= 5) {
                    if (($result->num_rows) > 0 && $arrresult['password'] === $input_password) {

                        $result = mysqli_fetch_assoc($result);
                        $_SESSION['username'] = $arrresult['username'];
                        $_SESSION['role'] = $arrresult['role'];
                        $_SESSION['id'] = $arrresult['id'];
                        // echo '<script>alert("Login thành công")</script>';       
                        session_regenerate_id();

                        header("Location: trangchu.php");
                    } else {
                        $stmt = $conn->prepare("UPDATE user SET block=block+1 WHERE username=?");
                        $stmt->bind_param("s", $input_username);
                        $stmt->execute();

                        echo '<script>alert("Tài khoản hoặc mật khẩu không chính xác")</script>';
                    }
                }else{
                    echo '<script>alert("Tài khoản của bạn đã bị khóa")</script>';
                }

                // $input_username = mysqli_real_escape_string($conn, $input_username);
                // $sql = 'SELECT * FROM user WHERE username="'.$input_username.'"';
                // $result = mysqli_query($conn, $sql);
                // $result = mysqli_fetch_assoc($result);

                // if($result != null && $result['password']===$input_password){

                // }else{
                //     echo '<script>alert("Tài khoản hoặc mật khẩu không chính xác")</script>';
                // }

                $stmt->close();
                $conn->close();
            } else {
                echo '<script>alert("Không được để trống bất kỳ trường thông tin nào")</script>';
            }
        }
        ?>
    </div>
</body>

</html>