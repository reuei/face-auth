<?php
/** app/core/Response.php - 统一响应 */
class Response {
    public static function json(array $data,int $code=200):void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['code'=>$code,'data'=>$data],JSON_UNESCAPED_UNICODE);
        exit;
    }
    public static function success(array $data=[],string $msg='success'):void {
        self::json(['message'=>$msg]+$data,200);
    }
    public static function error(string $msg,int $code=400):void {
        self::json(['message'=>$msg],$code);
    }
    public static function html(string $html):void {
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }
}