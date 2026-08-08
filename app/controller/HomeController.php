<?php
/** app/controller/HomeController.php - 前台控制器 */
class HomeController {
    public function index():void {
        $html=file_get_contents(PUBLIC_PATH.'/dist/index.html');
        if($html){Response::html($html);return;}
        require_once APP_PATH.'/view/home.php';
    }
    public function verify():void {require_once APP_PATH.'/view/verify.php';}
    public function result():void {require_once APP_PATH.'/view/result.php';}
    public function pricing():void {require_once APP_PATH.'/view/pricing.php';}
    public function docs():void {require_once APP_PATH.'/view/docs.php';}
    public function about():void {require_once APP_PATH.'/view/about.php';}
}