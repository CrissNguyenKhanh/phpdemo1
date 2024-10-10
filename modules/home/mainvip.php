<?php
 if(!defined('_CODE')){
    die('Acesss Denied...');
 
   }
require_once('D:\xamppoii\htdocs\baithigiuaki\modules\auth\viewfind.php');

if (isGet()) {
    $filterAll = filter();
    
    // Kiểm tra nếu có yêu cầu cập nhật
    if (!empty($filterAll['update'])) {
        redirect('?module=auth&action=update&key=' . $filterAll['update']);
        exit; 
    }

    // Nếu tham số static là 'hienthi', lấy và hiển thị dữ liệu
    if ($filterAll['static'] == 'hienthi') {
        $sql = "SELECT todos.todoid, todos.task, todos.image, todos.completed, users.username 
                FROM todos 
                JOIN users ON todos.userid = users.userid";
        $selectquery = getRaw($sql);
        
        // Hiển thị danh sách công việc
        ?>
        <div class="container mt-5">
            <h2 class="mb-4">Danh sách công việc</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Công việc</th>
                            <th>Hình ảnh</th>
                            <th>Hoàn thành</th>
                            <th>Người dùng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($selectquery)): ?>
                            <?php foreach ($selectquery as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($row['task']) ?></td>
                                    <td>
                                        <?php if (!empty($row['image'])): ?>
                                            <img src="uploads/images/<?= htmlspecialchars($row['image']) ?>" alt="Hình ảnh công việc" style="width: 100px; height: auto;">
                                        <?php else: ?>
                                            Không có hình ảnh
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['completed'] ? 'Đã hoàn thành' : 'Chưa hoàn thành' ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm">
                                            <a href="?module=home&action=mainvip&static=hienthi&update=<?= $row['todoid'] ?>" class="text-white">Cập Nhật</a>
                                        </button>
                                        <button class="btn btn-danger" onclick="confirmDelete(<?= $row['todoid'] ?>)">Xóa</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có công việc nào được tìm thấy</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function confirmDelete(bookId) {
                // Hiển thị hộp thoại xác nhận
                var confirmation = confirm("Bạn có chắc chắn muốn xóa sách này không?");
                if (confirmation) {
                    // Nếu người dùng chọn OK, chuyển hướng đến đường dẫn xóa
                    window.location.href = "?module=home&action=mainvip&static=hienthi&delete=" + bookId;
                }
            }
        </script>

        <?php
        // Xử lý yêu cầu xóa nếu có
        if (isset($filterAll['delete'])) {
            $deleteQuery = delete('todos', 'todoid = ' . intval($filterAll['delete']));
  
            // Bạn có thể thay thế pre() bằng thông báo khác hoặc redirect
        }  



    }else if ($filterAll['static'] == 'thongke') {
        // 1. Thống kê số lượng sách theo từng nhà xuất bản
        $sql = "SELECT username,total_tasks,completed_tasks  FROM thongketodo";
        
        // Lấy kết quả thống kê theo nhà xuất bản
        $statics = getRaw($sql);
        
        if (!empty($statics)) {
            echo "<h3>Kết quả thống kê :</h3>";
            echo "<table class='table table-striped table-hover'>
                    <thead>
                        <tr>
                            <th>Tên nhân công</th>
                            <th>Tổng Công Việc</th>
                             <th>Số Công việc đã hoàn thành</th>

                        </tr>
                    </thead>
                    <tbody>";
    
            foreach ($statics as $task) {
                echo "<tr>
                        <td>" . htmlspecialchars($task['username']) . "</td>
                        <td>" . htmlspecialchars($task['total_tasks']) . "</td>
                         <td>" . htmlspecialchars($task['completed_tasks']) . "</td>
                      </tr>";
            }
    
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info'>Không tìm thấy kết quả thống kê phù hợp.</div>";
        }
    
        
        
    
    }else if ($filterAll['static'] == 'themsp') {
       

        redirect('?module=auth&action=add');
        // ?>
        
        // <?php
    
    }
}


?>
<?php
if (isPost()) {
    // Lấy dữ liệu từ form sau khi gửi và lọc input
    $filterPost = filter();
    $searchQuery = isset($filterPost['search']) ? trim($filterPost['search']) : '';

    // Kiểm tra xem từ khóa tìm kiếm có rỗng không
    if (!empty($searchQuery)) {
        // Câu SQL tìm kiếm theo tên task hoặc username
        $sql = "SELECT todos.todoid, todos.task, todos.image, todos.completed, todos.created, users.username
                FROM todos
                JOIN users ON todos.userid = users.userid
                WHERE todos.task LIKE :search
                OR users.username LIKE :search";

        // Sử dụng tham số để bảo vệ khỏi SQL Injection
        $params = [
            'search' => '%' . $searchQuery . '%'
        ];

        // Thực hiện truy vấn và lấy kết quả tìm kiếm
        $searchResults = getRaw2($sql, $params);

        if (!empty($searchResults)) {
            // Hiển thị kết quả tìm kiếm
            echo "<h3>Kết quả tìm kiếm cho: " . htmlspecialchars($searchQuery) . "</h3>";
            echo "<table class='table table-striped table-hover'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên Công Việc</th>
                            <th>Người Dùng</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Tạo</th>
                            <th>Hình ảnh</th>
                        </tr>
                    </thead>
                    <tbody>";

            // Hiển thị từng công việc trong kết quả
            foreach ($searchResults as $index => $todo) {
                $status = $todo['completed'] ? 'Hoàn thành' : 'Chưa hoàn thành';
                echo "<tr>
                        <td>" . ($index + 1) . "</td>
                        <td>" . htmlspecialchars($todo['task']) . "</td>
                        <td>" . htmlspecialchars($todo['username']) . "</td>
                        <td>" . $status . "</td>
                        <td>" . htmlspecialchars($todo['created']) . "</td>
                        <td><img src='uploads/images/" . htmlspecialchars($todo['image']) . "' alt='Hình ảnh công việc' style='width: 100px; height: auto;'></td>
                      </tr>";
            }

            echo "</tbody>
                  </table>";
        } else {
            echo "<div class='alert alert-info'>Không tìm thấy kết quả phù hợp cho: <strong>" . htmlspecialchars($searchQuery) . "</strong>.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Vui lòng nhập từ khóa tìm kiếm.</div>";
    }
}
?>



