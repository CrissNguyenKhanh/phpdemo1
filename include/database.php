<?php

if (!defined('_CODE')) {
    die('Access Denied...');
}

function query($sql, $data = [] ,$check = true) {
    global $conn;
    $result = false;
    try {
        $statement = $conn->prepare($sql);
        $result = !empty($data) ? $statement->execute($data) : $statement->execute();
    } catch (Exception $e) {
        echo $e->getMessage() . "<br>";
        echo 'File: ' . $e->getFile() . '<br>';
        echo 'Line: ' . $e->getLine() . '<br>';
        die();
    } if($check){
        return $statement;
    }
    return $result;
}

function insert($table, $data) {
    $keys = array_keys($data);
    $columns = implode(',', $keys);
    $placeholders = ':' . implode(',:', $keys);
    
    $sql = 'INSERT INTO ' . $table . ' (' . $columns . ') VALUES (' . $placeholders . ')';
    
    $result = query($sql, $data);
    
    if ($result) {
        echo "Registration successful.";
    }
    
    return $result;
}

function updateDuLieu($book_id, $booktitle, $bookname, $bookprice) {
    global $conn; // Nếu bạn dùng kết nối toàn cục
    $sql = "UPDATE books SET book_title = :book_title, book_author = :book_author, book_price = :book_price WHERE book_id = :book_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':book_title', $booktitle);
    $stmt->bindParam(':book_author', $bookname);
    $stmt->bindParam(':book_price', $bookprice);
    $stmt->bindParam(':book_id', $book_id);
    
    // Thực hiện câu truy vấn và kiểm tra kết quả
    return $stmt->execute();
}



function update($table, $data, $condition = '') {
    $updates = [];
    
    foreach ($data as $key => $value) {
        $updates[] = $key . ' = :' . $key;
    }
    
    $updateString = implode(', ', $updates);
    
    if (!empty($condition)) {
     
        $sql = 'UPDATE ' . $table . ' SET ' . $updateString . ' WHERE ' . $condition;
      
    } else {
        $sql = 'UPDATE ' . $table . ' SET ' . $updateString;
    }
    
    $result = query($sql, $data);
    
    return $result;
}
 function delete($table,$condition = ''){
    if(!empty($condition)){
    
        $sql  = 'DELETE FROM '.$table.' WHERE '.$condition;
       
    }else{
         $sql  = 'DELETE FROM '.$table;
    }
    $kq = query($sql);
    return $kq;
}

function getRaw($sql) {
    $kq = query($sql, [], true);
    if (is_object($kq)) {
        $dataFetch = $kq->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra xem kết quả có rỗng không và trả về mảng rỗng nếu không có kết quả
    if (empty($dataFetch)) {
        return [];
    }

    return $dataFetch;
}
function getOneRaw($sql){
    $kq = query($sql , [], true);
    if(is_object($kq)){
        $dataFetch =$kq->fetch(PDO::FETCH_ASSOC);
    }
    
    return $dataFetch;


}
function getRaw2($sql, $params = []) {
    // Thực hiện truy vấn với các tham số (nếu có)
    $result = query($sql, $params, true); // Gọi hàm query để thực hiện truy vấn
    
    if (is_object($result)) {
        $dataFetch = $result->fetchAll(PDO::FETCH_ASSOC); // Lấy tất cả các dòng kết quả
    }

    // Kiểm tra xem có kết quả hay không, nếu không trả về mảng rỗng
    if (empty($dataFetch)) {
        return [];
    }

    return $dataFetch;
}


function getOneRaw2($sql, $params = []) {
    $kq = query($sql, $params, true); // Pass the parameters to the query function
    if (is_object($kq)) {
        $dataFetch = $kq->fetch(PDO::FETCH_ASSOC);
    }
    
    return $dataFetch;
}
function getRowscount($sql){
    $kq = query($sql , [], true);
    if(!empty($kq)){
        return $kq ->rowCount();
    }
    
    return $dataFetch;


}

function updateData($table, $data, $condition) {
    global $conn; // Sử dụng biến $conn toàn cục để kết nối đến database
    
    // Tạo mảng để lưu các cặp 'cột = giá trị' cho câu truy vấn SQL
    $updateFields = [];
    foreach ($data as $key => $value) {
        $updateFields[] = "$key = :$key";  // Tạo ra các cặp 'cột = :cột' để thay thế sau
    }

    // Ghép mảng thành chuỗi để sử dụng trong câu lệnh SQL
    $updateFieldsStr = implode(', ', $updateFields);

    // Xây dựng câu truy vấn SQL
    $sql = "UPDATE $table SET $updateFieldsStr WHERE $condition";

    try {
        // Chuẩn bị truy vấn SQL
        $stmt = $conn->prepare($sql);

        // Gán giá trị cho các tham số truy vấn
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Thực thi câu lệnh truy vấn
        return $stmt->execute();
    } catch (PDOException $e) {
        // Xử lý ngoại lệ nếu có lỗi xảy ra
        echo "Lỗi cập nhật dữ liệu: " . $e->getMessage();
        return false;
    }
}
