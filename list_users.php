<?php
// list_users.php (fixed)
// Start the session
session_start();

// include model (dùng đường dẫn tuyệt đối để tránh lỗi require)
require_once __DIR__ . '/models/UserModel.php';
$userModel = new UserModel();

// Nếu có file functions.php trong project thì include, nếu không thì định nghĩa e() tại chỗ
$functionsPath = __DIR__ . '/functions.php';
if (file_exists($functionsPath)) {
    require_once $functionsPath;
} else {
    // hàm escape đơn giản (dùng khi project chưa có functions.php)
    function e($str) {
        return htmlspecialchars((string)$str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

// Lấy tham số tìm kiếm an toàn
$params = [];
if (!empty($_GET['keyword'])) {
    // chỉ lấy chuỗi nguyên thủy, không in trực tiếp ra HTML
    $params['keyword'] = (string) $_GET['keyword'];
}

// Lấy danh sách users
$users = $userModel->getUsers($params);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <?php
    // include meta/header bằng đường dẫn tuyệt đối để tránh lỗi
    $metaPath = __DIR__ . '/views/meta.php';
    if (file_exists($metaPath)) include $metaPath;
    ?>
</head>
<body>
    <?php
    $headerPath = __DIR__ . '/views/header.php';
    if (file_exists($headerPath)) include $headerPath;
    ?>
    <div class="container">
        <?php if (!empty($users)) { ?>
            <div class="alert alert-warning" role="alert">
                List of users! <br>
                <!-- Hiển thị URL mẫu nhưng encode để không tạo injection -->
                Hacker: <?php echo e('http://php.local/list_users.php?keyword=ASDF%25%22%3BTRUNCATE+banks%3B%23%23'); ?>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Type</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <th scope="row"><?php echo (int) $user['id']; ?></th>
                            <td><?php echo e($user['name']); ?></td>
                            <td><?php echo e($user['fullname']); ?></td>
                            <td><?php echo e($user['type']); ?></td>
                            <td>
                                <a href="form_user.php?id=<?php echo (int)$user['id']; ?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true" title="Update"></i>
                                </a>
                                <a href="view_user.php?id=<?php echo (int)$user['id']; ?>">
                                    <i class="fa fa-eye" aria-hidden="true" title="View"></i>
                                </a>
                                <a href="delete_user.php?id=<?php echo (int)$user['id']; ?>" onclick="return confirm('Delete user #<?php echo (int)$user['id']; ?>?');">
                                    <i class="fa fa-eraser" aria-hidden="true" title="Delete"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } // end foreach ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-dark" role="alert">
                No users found!
            </div>
        <?php } ?>
    </div>
</body>
</html>
