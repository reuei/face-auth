<?php
/** app/controller/AdminController.php - 后台管理 */
class AdminController {
    public function dispatch(string $uri):void {
        $uri=trim($uri,'/');
        $map=['admin'=>'login','admin/login'=>'doLogin','admin/dashboard'=>'dashboard','admin/api-manage'=>'apiManage','admin/api-debug'=>'apiDebug','admin/auth-logs'=>'authLogs','admin/settings'=>'settings','admin/admin-manage'=>'adminManage','admin/mofang-config'=>'mofangConfig'];
        if(isset($map[$uri])){
            $method=$map[$uri];
            $this->$method();
        }else{
            require_once APP_PATH.'/view/admin/login.php';
        }
    }
    public function login():void {require_once APP_PATH.'/view/admin/login.php';}
    public function doLogin():void {
        $input=json_decode(file_get_contents('php://input'),true)?:[];
        $v=new Validator($input);$v->required('username')->required('password');
        if(!$v->passes()){Response::error($v->firstError());return;}
        $admin=Database::fetchOne("SELECT * FROM sm_admin WHERE username=? AND status=1 LIMIT 1",[$input['username']]);
        if(!$admin||!Security::verifyPassword($input['password'],$admin['password'])){Response::error('用户名或密码错误',401);return;}
        Database::update('sm_admin',['last_login_ip'=>Security::getClientIp(),'last_login_time'=>date('Y-m-d H:i:s')],'id=?',[$admin['id']]);
        $_SESSION['admin_id']=$admin['id'];
        $_SESSION['admin_username']=$admin['username'];
        $_SESSION['admin_role']=$admin['role'];
        Response::success(['message'=>'登录成功']);
    }
    public function dashboard():void {require_once APP_PATH.'/view/admin/dashboard.php';}
    public function apiManage():void {require_once APP_PATH.'/view/admin/api-manage.php';}
    public function apiDebug():void {require_once APP_PATH.'/view/admin/api-debug.php';}
    public function authLogs():void {require_once APP_PATH.'/view/admin/auth-logs.php';}
    public function settings():void {require_once APP_PATH.'/view/admin/settings.php';}
    public function adminManage():void {require_once APP_PATH.'/view/admin/admin-manage.php';}
    public function mofangConfig():void {require_once APP_PATH.'/view/admin/mofang-config.php';}
}