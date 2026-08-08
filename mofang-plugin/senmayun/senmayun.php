<?php
/** mofang-plugin/senmayun/senmayun.php - 魔方财务插件 */
namespace MofangPlugin;
class SenmayunPlugin {
    private string $name='森码云实人认证';
    private string $version='1.0.0';
    public function install():array {return ['status'=>'success','message'=>'安装成功'];}
    public function uninstall():array {return ['status'=>'success','message'=>'卸载成功'];}
    public function config():array {return [
        'api_url'=>['type'=>'text','label'=>'API地址','default'=>'https://face.builds.codes'],
        'api_key'=>['type'=>'text','label'=>'API Key','default'=>''],
        'api_secret'=>['type'=>'password','label'=>'API Secret','default'=>''],
        'callback_url'=>['type'=>'text','label'=>'回调URL','default'=>''],
    ];}
    public function initVerify(int $userId,array $userInfo=[]):array {
        $cfg=$this->getConfig();
        if(empty($cfg['api_key'])) return ['status'=>'error','message'=>'配置不完整'];
        $data=['api_key'=>$cfg['api_key'],'api_secret'=>$cfg['api_secret'],'user_id'=>$userId,'real_name'=>$userInfo['real_name']??'','id_card'=>$userInfo['id_card']??'','return_url'=>$cfg['callback_url']??''];
        $r=$this->request($cfg['api_url'].'/api/v1/mofang/init',$data);
        if(!empty($r['success'])) return ['status'=>'success','token'=>$r['data']['token'],'verify_url'=>$r['data']['verify_url']];
        return ['status'=>'error','message'=>$r['message']??'失败'];
    }
    private function request(string $url,array $data):array {
        $ch=curl_init($url);curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>json_encode($data),CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type:application/json'],CURLOPT_TIMEOUT=>30]);
        $r=curl_exec($ch);curl_close($ch);
        return json_decode($r,true)?:[];
    }
    private function getConfig():array {return [];}
}