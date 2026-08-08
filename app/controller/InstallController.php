<?php
/** app/controller/InstallController.php - 安装向导 */
class InstallController {
    public function run():void {
        $step=$_GET['step']??'1';
        switch($step){
            case '1':$this->step1();break;
            case '2':$this->step2();break;
            case '3':$this->step3();break;
            case '4':$this->step4();break;
            case '5':$this->step5();break;
            default:$this->step1();
        }
    }
    private function step1():void {require_once APP_PATH.'/view/install/step1.php';}
    private function step2():void {require_once APP_PATH.'/view/install/step2.php';}
    private function step3():void {require_once APP_PATH.'/view/install/step3.php';}
    private function step4():void {require_once APP_PATH.'/view/install/step4.php';}
    private function step5():void {require_once APP_PATH.'/view/install/step5.php';}

    public function check():void {
        $reqs=[['name'=>'PHP版本>=8.1','passed'=>version_compare(PHP_VERSION,'8.1.0','>=')],['name'=>'PDO扩展','passed'=>extension_loaded('pdo')],['name'=>'GD扩展','passed'=>extension_loaded('gd')],['name'=>'cURL扩展','passed'=>extension_loaded('curl')],['name'=>'OpenSSL','passed'=>extension_loaded('openssl')],['name'=>'Mbstring','passed'=>extension_loaded('mbstring')],['name'=>'JSON','passed'=>extension_loaded('json')],['name'=>'Fileinfo','passed'=>extension_loaded('fileinfo')],['name'=>'上传目录可写','passed'=>is_writable(PUBLIC_PATH.'/uploads')],['name'=>'配置目录可写','passed'=>is_writable(CONFIG_PATH)]];
        $all=true;foreach($reqs as $r) if(!$r['passed']){$all=false;break;}
        Response::success(['requirements'=>$reqs,'all_passed'=>$all]);
    }

    public function database():void {
        $input=json_decode(file_get_contents('php://input'),true)?:[];
        $v=new Validator($input);$v->required('host')->required('database')->required('username');
        if(!$v->passes()){Response::error($v->firstError());return;}
        try{
            $dsn="mysql:host={$input['host']};port={$input['port']};charset=utf8mb4";
            $pdo=new PDO($dsn,$input['username'],$input['password']??'');
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$input['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$input['database']}`");
            $pdo->exec(file_get_contents(BASE_PATH.'/database/install.sql'));
            $cfg="<?php\nreturn ".var_export(['host'=>$input['host'],'port'=>$input['port'],'database'=>$input['database'],'username'=>$input['username'],'password'=>$input['password'],'charset'=>'utf8mb4','prefix'=>'sm_'],true).";\n";
            file_put_contents(CONFIG_PATH.'/database.php',$cfg);
            Response::success(['message'=>'数据库安装成功']);
        }catch(PDOException $e){Response::error('数据库错误: '.$e->getMessage());}
    }

    public function admin():void {
        $input=json_decode(file_get_contents('php://input'),true)?:[];
        $v=new Validator($input);$v->required('username')->required('email')->required('password')->length('password',8,50);
        if(!$v->passes()){Response::error($v->firstError());return;}
        Database::insert('sm_admin',['username'=>Security::xssClean($input['username']),'password'=>Security::hashPassword($input['password']),'email'=>Security::xssClean($input['email']),'real_name'=>Security::xssClean($input['real_name']??''),'role'=>1,'status'=>1,'created_at'=>date('Y-m-d H:i:s')]);
        Response::success(['message'=>'管理员创建成功']);
    }

    public function finish():void {
        file_put_contents(INSTALL_LOCK,date('Y-m-d H:i:s'));
        Response::success(['message'=>'安装完成']);
    }
}