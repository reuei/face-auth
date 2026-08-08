<?php
/** app/core/Token.php - 长Token生成与校验 */
class Token {
    private static int $expireMinutes=15;
    private static string $table='sm_token';
    public static function generate(int $userId,int $orderId=0,string $scene='face_verify'):array {
        $random=bin2hex(random_bytes(32));
        $ts=time();
        $payload=json_encode(['uid'=>$userId,'oid'=>$orderId,'scene'=>$scene,'ts'=>$ts]);
        $secret=self::getSecret();
        $sig=hash_hmac('sha256',$payload,$secret);
        $token="{$random}.{$ts}.{$sig}";
        $expire=date('Y-m-d H:i:s',$ts+self::$expireMinutes*60);
        try{
            Database::insert(self::$table,['token'=>$token,'user_id'=>$userId,'order_id'=>$orderId,'scene'=>$scene,'status'=>'pending','expire_time'=>$expire,'created_at'=>date('Y-m-d H:i:s')]);
        }catch(Exception $e){}
        return ['token'=>$token,'expire_time'=>$expire,'verify_url'=>'https://face.builds.codes/verify?token='.urlencode($token)];
    }
    public static function verify(string $token):?array {
        try{
            return Database::fetchOne("SELECT * FROM ".self::$table." WHERE token=? AND status='pending' AND expire_time>NOW()",[$token]);
        }catch(Exception $e){return null;}
    }
    public static function markUsed(string $token,string $result=''):void {
        try{Database::update(self::$table,['status'=>'used','used_at'=>date('Y-m-d H:i:s'),'result'=>$result],'token=?',[$token]);}catch(Exception $e){}
    }
    private static function getSecret():string {
        $f=CONFIG_PATH.'/app.php';
        $c=file_exists($f)?require $f:[];
        return $c['auth']['token_secret']??'senma_default_secret_32_chars';
    }
}