<?php
/** app/core/Database.php - PDO数据库封装 */
class Database {
    private static ?PDO $instance = null;
    private static array $config = [];
    private static function loadConfig():void {
        if(empty(self::$config)){
            $f=CONFIG_PATH.'/database.php';
            if(!file_exists($f)) throw new Exception('数据库配置不存在');
            self::$config=require $f;
        }
    }
    public static function getInstance():PDO {
        if(self::$instance===null){
            self::loadConfig();
            $dsn=sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s',self::$config['host'],self::$config['port']??'3306',self::$config['database'],self::$config['charset']??'utf8mb4');
            self::$instance=new PDO($dsn,self::$config['username'],self::$config['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
        }
        return self::$instance;
    }
    public static function query(string $sql,array $params=[]):PDOStatement {
        $stmt=self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    public static function fetchOne(string $sql,array $params=[]):?array {
        $r=self::query($sql,$params)->fetch();
        return $r?:null;
    }
    public static function fetchAll(string $sql,array $params=[]):array {
        return self::query($sql,$params)->fetchAll();
    }
    public static function insert(string $table,array $data):string {
        $cols=implode(',',array_keys($data));
        $vals=implode(',',array_fill(0,count($data),'?'));
        self::query("INSERT INTO {$table} ({$cols}) VALUES ({$vals})",array_values($data));
        return self::getInstance()->lastInsertId();
    }
    public static function update(string $table,array $data,string $where,array $whereParams=[]):int {
        $set=implode(',',array_map(fn($k)=>"{$k}=?",array_keys($data)));
        $s=self::query("UPDATE {$table} SET {$set} WHERE {$where}",array_merge(array_values($data),$whereParams));
        return $s->rowCount();
    }
    public static function begin():void {self::getInstance()->beginTransaction();}
    public static function commit():void {self::getInstance()->commit();}
    public static function rollback():void {self::getInstance()->rollBack();}
}