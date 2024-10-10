<?php
layouts('header');
if (!defined('_CODE')) {
    die('Access Denied...');
}

// Xử lý khi có dữ liệu POST
if (isPost()) {
    $filterAll = filter();
    
    // Lấy dữ liệu từ form
    $task = $_POST['task']; 
    
  
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['book_image']['tmp_name'];
        $fileName = $_FILES['book_image']['name'];
        $fileSize = $_FILES['book_image']['size'];
        $fileType = $_FILES['book_image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $uploadFileDir = '';
        $dest_path = $uploadFileDir . $fileName;

        
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $image = $dest_path;
        } else {
            echo "<div class='alert alert-danger'>Lỗi khi tải lên file hình ảnh!</div>";
            exit;
        }
    } else {
        echo "<div class='alert alert-danger'>Bạn phải chọn một hình ảnh!</div>";
        exit;
    }

    $trangthai = $filterAll['completed'];
    $username = $_POST['username'];
    $email = $_POST['email'];


    if (empty($email)) {
        echo "<div class='alert alert-danger'>Email không được để trống!</div>";
        exit;
    }
    

    
    $checkUserSql = "SELECT userid FROM users WHERE username = :username";
    $existingUser = getOneRaw2($checkUserSql, ['username' => $username]);

    if (!$existingUser) {
        
        $insertUser = [
            'username' => $username,
            'email' => $email,
            'password' => '', 
            'confirmed' => 0, 
            'type' => 0, 
            'created' => date("Y-m-d H:i:s"),
            'updated' => date("Y-m-d H:i:s")
        ];
        
        $insertUserResult = insert('users', $insertUser);
        
        if (!$insertUserResult) {
            echo "<div class='alert alert-danger'>Thêm người dùng thất bại!</div>";
            exit;
        }
        
        // Lấy ID của người dùng vừa thêm
        global $conn;
        $user_id = $conn->lastInsertId();
    } else {
        // Nếu người dùng đã tồn tại, lấy user_id
        $user_id = $existingUser['userid'];
    }

    // 2. Thêm công việc vào bảng todos
    $insertTodo = [
        'task' => $task,
        'image' => $image,
        'completed' => $trangthai,
        'userid' => $user_id,
        'created' => date("Y-m-d H:i:s"),
        'updated' => date("Y-m-d H:i:s")
    ];

    $insertTodoResult = insert('todos', $insertTodo);

    if ($insertTodoResult) {
        echo "<div class='alert alert-success'>Thêm công việc thành công!</div>";
    } else {
        echo "<div class='alert alert-danger'>Thêm công việc thất bại!</div>";
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Thêm Công Việc Mới</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="task">Tên Công Việc</label>
            <input type="text" class="form-control" id="task" name="task" required>
        </div>
        
        <div class="form-group">
            <label for="book_image">Hình ảnh</label>
            <input type="file" class="form-control" id="book_image" name="book_image" required>
        </div>
        <div class="form-group">
            <label for="completed">Trạng Thái</label>
            <select class="form-control" id="completed" name="completed">
                <option value="0">Chưa hoàn thành</option>
                <option value="1">Đã hoàn thành</option>
            </select>
        </div>
        <div class="form-group">
    <label for="email">Email</label>
    <input type="email" class="form-control" id="email" name="email" required>
</div>

        <div class="form-group">
            <label for="username">Người Dùng</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm Công Việc</button>
        <a href="?module=home&action=mainvip&static=hienthi" class="btn btn-secondary">Quay Lại</a>
        
    </form>
</div>


<?php
layouts('footer');
?>
