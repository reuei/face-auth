<?php
/** app/middleware/Auth.php - 认证中间件 */
class AuthMiddleware {
    public static function check():void {
        if(empty($_SESSION['admin_id'])){header('Location:/admin');exit;}
    }
    public static function adminId():int {return (int)($_SESSION['admin_id']??0);}
}