<?php
/** app/service/FaceService.php - 人脸核身编排 */
require_once APP_PATH.'/service/Channel/ChannelInterface.php';
require_once APP_PATH.'/service/Channel/TencentHuiyanChannel.php';
require_once APP_PATH.'/service/Channel/BaiduFaceChannel.php';
require_once APP_PATH.'/service/Channel/LocalDemoChannel.php';

class FaceService {
    private array $channels=[];
    private ChannelInterface $defaultChannel;

    public function __construct() {
        $this->loadChannels();
        $this->defaultChannel=$this->channels['local']??new LocalDemoChannel();
    }

    private function loadChannels():void {
        try{
            $list=Database::fetchAll("SELECT * FROM sm_api_channel WHERE is_enabled=1 ORDER BY priority ASC");
            foreach($list as $c){
                switch($c['provider']){
                    case 'tencent':$this->channels['tencent']=new TencentHuiyanChannel();break;
                    case 'baidu':$this->channels['baidu']=new BaiduFaceChannel();break;
                    case 'local':$this->channels['local']=new LocalDemoChannel();break;
                }
            }
        }catch(Exception $e){}
        if(!isset($this->channels['local'])) $this->channels['local']=new LocalDemoChannel();
    }

    public function detect(string $image,?string $channel=null):array {
        $ch=$channel&&isset($this->channels[$channel])?$this->channels[$channel]:$this->defaultChannel;
        try{return $ch->detect($image);}
        catch(Exception $e){return $this->channels['local']->detect($image);}
    }

    public function compare(string $img1,string $img2,?string $channel=null):array {
        $ch=$channel&&isset($this->channels[$channel])?$this->channels[$channel]:$this->defaultChannel;
        try{return $ch->compare($img1,$img2);}
        catch(Exception $e){return $this->channels['local']->compare($img1,$img2);}
    }

    public function liveness(array $frames,?string $channel=null):array {
        $ch=$channel&&isset($this->channels[$channel])?$this->channels[$channel]:$this->defaultChannel;
        try{return $ch->liveness($frames);}
        catch(Exception $e){return $this->channels['local']->liveness($frames);}
    }

    public function getChannels():array {return $this->channels;}

    public function initVerification(array $data):array {
        $record=['name'=>Security::xssClean($data['name']??''),'id_card'=>Security::xssClean($data['id_card']??''),'token'=>Security::generateToken(64),'verify_type'=>$data['verify_type']??'full','status'=>'pending','ip'=>Security::getClientIp(),'user_agent'=>$_SERVER['HTTP_USER_AGENT']??'', 'created_at'=>date('Y-m-d H:i:s')];
        $id=Database::insert('sm_verification',$record);
        $record['id']=$id;
        return $record;
    }

    public function getVerification(string $token):?array {
        return Database::fetchOne("SELECT * FROM sm_verification WHERE token=? LIMIT 1",[$token]);
    }

    public function completeVerification(string $token,array $result):void {
        $v=$this->getVerification($token);
        if(!$v) return;
        Database::update('sm_verification',['status'=>$result['passed']?'passed':'failed','face_match_score'=>$result['score']??0,'liveness_passed'=>$result['liveness_passed']?1:0,'fail_reason'=>$result['fail_reason']??'','api_source'=>$result['source']??'','completed_at'=>date('Y-m-d H:i:s')],'id=?',[$v['id']]);
    }
}