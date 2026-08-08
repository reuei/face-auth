<?php
/**
 * 森码云实人认证系统 - 统一入口
 * 域名: face.builds.codes
 */

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('INSTALL_LOCK', PUBLIC_PATH . '/install.lock');

// 检查是否绑定 public 目录
$scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
if (strpos($scriptPath, '/public/') === false && strpos($scriptPath, '/public') === false) {
    require_once APP_PATH . '/view/error/public_bind.php';
    exit;
}

// 检查是否安装
if (!file_exists(INSTALL_LOCK)) {
    if (!isset($_GET['install'])) {
        header('Location: /install');
        exit;
    }
    require_once APP_PATH . '/controller/InstallController.php';
    (new InstallController())->run();
    exit;
}

// 加载核心
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Response.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/core/Validator.php';
require_once APP_PATH . '/core/Token.php';
require_once APP_PATH . '/core/Logger.php';

// 路由分发
$isApi = isset($_GET['api']);
$isAdmin = isset($_GET['admin']);
$isInstall = isset($_GET['install']);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($isApi) {
    require_once APP_PATH . '/controller/ApiController.php';
    (new ApiController())->dispatch($_GET['api']);
} elseif ($isAdmin) {
    require_once APP_PATH . '/controller/AdminController.php';
    (new AdminController())->dispatch($uri);
} elseif ($isInstall) {
    require_once APP_PATH . '/controller/InstallController.php';
    (new InstallController())->run();
} else {
    // 加载前端SPA
    $distFile = PUBLIC_PATH . '/dist/index.html';
    if (file_exists($distFile)) {
        readfile($distFile);
    } else {
        require_once APP_PATH . '/view/home.php';
    }
}