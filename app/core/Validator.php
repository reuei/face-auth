<?php
/** app/core/Validator.php - 输入验证 */
class Validator {
    private array $errors=[];
    private array $data;
    public function __construct(array $data){$this->data=$data;}
    public function required(string $f,string $msg=''):self {
        if(empty($this->data[$f])&&$this->data[$f]!=='0') $this->errors[$f]=$msg?:"{$f}不能为空";
        return $this;
    }
    public function email(string $f,string $msg=''):self {
        if(!empty($this->data[$f])&&!filter_var($this->data[$f],FILTER_VALIDATE_EMAIL)) $this->errors[$f]=$msg?:"{$f}格式不正确";
        return $this;
    }
    public function length(string $f,int $min,int $max,string $msg=''):self {
        $l=mb_strlen($this->data[$f]??'');
        if($l<$min||$l>$max) $this->errors[$f]=$msg?:"{$f}长度应在{$min}-{$max}之间";
        return $this;
    }
    public function passes():bool {return empty($this->errors);}
    public function firstError():?string {return $this->errors[array_key_first($this->errors)]??null;}
    public function errors():array {return $this->errors;}
}