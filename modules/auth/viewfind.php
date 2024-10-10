<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task manager</title>

    <?php
    layouts('header');
    ?>

</head>
<body>

    <!-- Header -->
    

    <!-- Main Content -->
  <form method ="post">
        <div class="container mt-5">
        <div class="row text-center">
                 <div class="col-md-2 d-flex align-items-center">
                    <a href="?module=home&action=mainvip&static=thongke" class="btn btn-primary btn-block thongke">Thống Kê</a>
                </div>
        
                <div class="col-md-2 d-flex align-items-center">
                    <a href="?module=home&action=mainvip&static=hienthi" class="btn btn-primary btn-block hienthi">Hiển Thị</a>
                </div>
        
                <div class="col-md-2 d-flex align-items-center">
                    <a href="?module=home&action=mainvip&static=themsp" class="btn btn-primary btn-block addsanpham">Thêm đối tượng</a>
                </div>
        
       
            <div class="col-md-6 search-form">
                <span class="search-label">Tìm kiếm:</span>
                <form class="form-inline" action="" method="post">
                    <input class="form-control mr-2 texttimkiem" name="search" type="search" placeholder="Tìm kiếm" aria-label="Search">
                    <button class="btn btn-outline-success timkiem" type="submit">Tìm kiếm</button>

                </form>
            </div>
        </div>
    </div>
  </form>


    <!-- Footer -->
    <?php
    layouts('footer');
    ?>

</body>
</html>
