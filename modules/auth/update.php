<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    global $filterAll;
    global $token;
    layouts('header');
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Công Việc</title>
</head>
<body>
<?php

include_once 'include/database.php'; 

// Xử lý khi nhận dữ liệu qua phương thức GET
if (isGet()) {
    $filterAll = filter();
    
    if (isset($filterAll['key'])) {
        $token = $filterAll['key'];

        
        $sql = 'SELECT todoid, task, completed, userid FROM todos WHERE todoid = :todoid';
        $params = ['todoid' => $token];
        $queryRow = getOneRaw2($sql, $params);

       
        if (empty($queryRow)) {
            echo "<div class='alert alert-danger'>Không tìm thấy thông tin công việc.</div>";
            exit;
        }

       
        $usersSql = 'SELECT userid, username FROM users';
        $usersList = getRaw2($usersSql, []);
    } else {
        echo "<div class='alert alert-danger'>Không có thông tin công việc nào được cung cấp.</div>";
        exit;
    }
}
?>

<div class="container mt-5">
    <h2>Cập Nhật Công Việc</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="task">Tên Công Việc</label>
            <input type="text" class="form-control" id="task" name="task" value="<?= isset($queryRow['task']) ? htmlspecialchars($queryRow['task']) : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="completed">Trạng Thái</label>
            <select class="form-control" id="completed" name="completed">
                <option value="0" <?= isset($queryRow['completed']) && $queryRow['completed'] == 0 ? 'selected' : ''; ?>>Chưa hoàn thành</option>
                <option value="1" <?= isset($queryRow['completed']) && $queryRow['completed'] == 1 ? 'selected' : ''; ?>>Đã hoàn thành</option>
            </select>
        </div>

        <div class="form-group">
            <label for="userid">Người Dùng</label>
            <select class="form-control" id="userid" name="userid">
                <?php foreach ($usersList as $user) { ?>
                    <option value="<?= $user['userid'] ?>" <?= isset($queryRow['userid']) && $queryRow['userid'] == $user['userid'] ? 'selected' : ''; ?>><?= htmlspecialchars($user['username']) ?></option>
                <?php } ?>
            </select>
        </div>

        <input type="hidden" name="code" value="<?php echo htmlspecialchars($_GET['key']); ?>">
        <input type="hidden" name="okcu" value ="capnhatdinhcao">;
        <input type="submit" value="Cập Nhật" name="update" class="btn btn-primary">
        <a href="?module=home&action=mainvip&static=hienthi" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>

<?php
// Xử lý khi form được gửi qua phương thức POST
if (isPost()) {
    $task = filter_input(INPUT_POST, 'task');
    $completed = filter_input(INPUT_POST, 'completed');
    $userid = filter_input(INPUT_POST, 'userid');
    $action = filter_input(INPUT_POST, 'okcu');

   
    if (!empty($action) && $action == 'capnhatdinhcao') {
       
        if (!empty($task) && isset($completed) && !empty($userid)) {
           
            $data = [
                'task' => $task,
                'completed' => $completed,
                'userid' => $userid,
                'todoid' => filter_input(INPUT_POST, "code", FILTER_SANITIZE_STRING) 
            ];

      
            $condition = "todoid = :todoid";  
          
            $updateResult = update('todos', $data, $condition);

            
            if ($updateResult) {
                echo "<div class='alert alert-success'>Cập nhật công việc thành công!</div>";
            } else {
                echo "<div class='alert alert-danger'>Cập nhật công việc thất bại!</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Vui lòng nhập đầy đủ thông tin công việc.</div>";
        }
    }
}
?>

<?php
layouts('footer');
?>
</body>
</html>
