<?php

if (isGet()) {
    $filterAll = filter();
    if ($filterAll['static'] == 'hienthi') {
        $sql = "SELECT todos.book_id, todos.task, books.book_author, books.book_price, publisher.publisher_name 
                FROM books 
                JOIN publisher ON books.publisherid = publisher.publisherid";
        $selectquery = getRaw($sql);
        
        
        // Output the table and data
        ?>
        <div class="container mt-5">
            <h2 class="mb-4">Danh sách sản phẩm</h2>
     
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                        
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Tác Gỉa</th>
                            <th>Nhà Xuất Bản</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($selectquery)): ?>
                            <?php foreach ($selectquery as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td> <!-- Row Number -->
                                    <td><?= htmlspecialchars($row['book_title']) ?></td> <!-- Book Title -->
                                    <td><?= number_format($row['book_price']) ?> VND</td> <!-- Book Price -->
                                    <td><?= htmlspecialchars($row['book_author']) ?></td> <!-- Book Author -->
                                    <td><?= htmlspecialchars($row['publisher_name']) ?></td> <!-- Publisher Name -->
                                    <td>
                                       
                                    <button class="btn btn-warning btn-sm">
    <a href="?module=home&action=mainvip&static=hienthi&update=<?= $row['book_id'] ?>" class="text-white">Cập Nhật</a>
</button>
<button class="btn btn-danger">
    <a href="?module=home&action=mainvip&static=hienthi&delete=<?= $row['book_id'] ?>" class="text-white">Xóa</a>
</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có sản phẩm nào được tìm thấy</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    if(!empty($filterAll['update'])){
   redirect('?module=auth&action=update&key='.$filterAll['update']);

}
}
?>