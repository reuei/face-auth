<?php
/** app/core/Logger.php - 日志记录 */
class Logger {
    private static string $dir=BASE_PATH.'/logs';
    private static function init():void {if(!is_dir(self::$dir)) mkdir(self::$dir,0755,true);}
    private static function write(string $level,string $msg,array $ctx=[]):void {
        self::init();
        $f=self::$dir.'/'.date('Y-m-d').'.log';
        $entry=sprintf("[%s][%s] %s %s\n",date('H:i:s'),$level,$msg,$ctx?json_encode($ctx,JSON_UNESCAPED_UNICODE):'');
        file_put_contents($f,$entry,FILE_APPEND|LOCK_EX);
    }
    public static function info(string $msg,array $ctx=[]):void {self::write('INFO',$msg,$ctx);}
    public static function error(string $msg,array $ctx=[]):void {self::write('ERROR',$msg,$ctx);}
    public static function warning(string $msg,array $ctx=[]):void {self::write('WARN',$msg,$ctx);}
}