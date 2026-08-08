<?php
/** app/core/Security.php - 安全工具 */
class Security {
    public static function hmacSign(array $data,string $secret):string {
        ksort($data);
        return hash_hmac('sha256',http_build_query($data),$secret);
    }
    public static function hmacVerify(array $data,string $sign,string $secret):bool {
        return hash_equals(self::hmacSign($data,$secret),$sign);
    }
    public static function generateToken(int $len=32):string {
        return bin2hex(random_bytes($len/2));
    }
    public static function encrypt(string $data,string $key):string {
        $iv=random_bytes(16);
        $e=openssl_encrypt($data,'AES-256-CBC',$key,OPENSSL_RAW_DATA,$iv);
        return base64_encode($iv.$e);
    }
    public static function decrypt(string $data,string $key):string {
        $d=base64_decode($data);
        return openssl_decrypt(substr($d,16),'AES-256-CBC',$key,OPENSSL_RAW_DATA,substr($d,0,16))?:'';
    }
    public static function hashPassword(string $p):string {
        return password_hash($p,PASSWORD_ARGON2ID);
    }
    public static function verifyPassword(string $p,string $h):bool {
        return password_verify($p,$h);
    }
    public static function xssClean(string $s):string {
        return htmlspecialchars($s,ENT_QUOTES|ENT_HTML5,'UTF-8');
    }
    public static function getClientIp():string {
        foreach(['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR'] as $h){
            if(!empty($_SERVER[$h])){
                $ip=explode(',',$_SERVER[$h])[0];
                if(filter_var(trim($ip),FILTER_VALIDATE_IP)) return trim($ip);
            }
        }
        return '0.0.0.0';
    }
    public static function rateLimit(string $key,int $max=10,int $window=60):bool {
        $f=sys_get_temp_dir().'/ratelimit_'.md5($key).'.json';
        $now=time();
        $reqs=file_exists($f)?json_decode(file_get_contents($f),true)?:[];
        $reqs=array_filter($reqs,fn($t)=>$now-$t<$window);
        if(count($reqs)>=$max) return false;
        $reqs[]=$now;
        file_put_contents($f,json_encode(array_values($reqs)));
        return true;
    }
}