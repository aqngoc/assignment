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

                $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND password=?;");
                $stmt->bind_param("ss", $input_username, $input_password);
                $stmt->execute();

                //Lấy kết quả, kiểm tra nếu tồn tại bản ghi -> tài khoản và mật khẩu chính xác
                $result = $stmt->get_result();
                if (($result->num_rows) > 0) {
                    //tạo mới session khi đăng nhập thành công

                    $result = mysqli_fetch_assoc($result);
                    $_SESSION['username'] = $result['username'];
                    $_SESSION['role'] = $result['role'];
                    $_SESSION['id'] = $result['id'];
                    // sleep(1);
                    // echo '<script>alert("Login thành công")</script>';       
                    session_regenerate_id();
                    header("Location: trangchu.php");
                } else {
                    echo '<script>alert("Tài khoản hoặc mật khẩu không chính xác")</script>';
                }

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