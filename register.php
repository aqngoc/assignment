<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Register</title>
</head>

<body style="background-image: url('./image/background_login.jpg'); background-size: cover;">
    <div style="width: 600px; padding: 5%">
        <h2>Đăng ký</h2>
        <form action="" method="POST">
            <div class="mb-3 mt-3">
                <label>Username:</label><br>
                <input type="text" class="form-control" placeholder="Nhập Username" name="input_username" required>
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" class="form-control" placeholder="Nhập password" name="input_password" required>
            </div>
            <div class="mb-3">
                <label>Họ tên:</label>
                <input type="text" class="form-control" placeholder="Nhập họ và tên đầy đủ" name="input_hoten" required>
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" class="form-control" placeholder="Nhập email" name="input_email" required>
            </div>
            <div class="mb-3">
                <label>Số điện thoại:</label>
                <input type="number" class="form-control" placeholder="Nhập số điện thoại" name="input_phone" required>
            </div>
            <button type="submit" class="btn btn-primary" name="click_submit">Đăng ký</button>
            <a style="margin-left: 20px;" href="./index.php">Quay lại</a>
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
                        $stmt1 = $conn->prepare("SELECT * FROM user WHERE username=?");
                        $stmt1->bind_param("s", $input_username);
                        $stmt1->execute();
                        $result = $stmt1->get_result();
                        if (($result->num_rows) > 0) {
                            echo '<script>alert("Username đã tồn tại")</script>';
                        } else {
                            //thuc hien them moi user
                            $stmt = $conn->prepare("INSERT INTO user VALUES (null,?, ?, ?, ?, ?, 0)");
                            $stmt->bind_param("sssss", $input_username, $input_password, $input_hoten, $input_email, $input_phone);
                            $stmt->execute();

                            echo '<script>alert("Đăng ký thành công")</script>';
                            $stmt->close();
                        }
                        $stmt1->close();
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

</body>

</html>