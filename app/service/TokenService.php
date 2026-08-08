<?php
/** app/service/TokenService.php - 长Token生成与校验 */
class TokenService {
    private static int $expire=900;
    public static function generate(int $userId,int $orderId=0,string $scene='face_verify'):array {
        return Token::generate($userId,$orderId,$scene);
    }
    public static function verify(string $token):?array {return Token::verify($token);}
    public static function markUsed(string $token,string $result=''):void {Token::markUsed($token,$result);}
    public static function getExpire():int {return self::$expire;}
}