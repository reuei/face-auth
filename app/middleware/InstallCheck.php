<?php
/** app/middleware/InstallCheck.php - 安装检查中间件 */
class InstallCheck {
    public static function check():void {
        if(!file_exists(INSTALL_LOCK)){header('Location:/install');exit;}
    }
}