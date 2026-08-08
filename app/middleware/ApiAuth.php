<?php
/** app/middleware/ApiAuth.php - API认证中间件 */
class ApiAuth {
    public static function verify(array $input=[]):bool {
        $key=$input['api_key']??'';
        $secret=$input['api_secret']??'';
        if(empty($key)||empty($secret)) return false;
        try{
            $c=Database::fetchOne("SELECT * FROM sm_api_channel WHERE api_key=? AND is_enabled=1 LIMIT 1",[$key]);
            return $c&&Security::verifyPassword($secret,$c['secret_key']);
        }catch(Exception $e){return false;}
    }
}