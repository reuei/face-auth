<?php
/** app/service/MofangFinanceService.php - 魔方财务对接 */
class MofangFinanceService {
    public function initVerification(array $data):array {
        $uid=(int)($data['user_id']??0);
        $tokenData=TokenService::generate($uid,(int)($data['order_id']??0),'mofang');
        Database::insert('sm_verification',['user_id'=>$uid,'token'=>$tokenData['token'],'name'=>Security::xssClean($data['name']??''),'id_card'=>Security::xssClean($data['id_card']??''),'verify_type'=>'full','status'=>'pending','ip'=>Security::getClientIp(),'created_at'=>date('Y-m-d H:i:s')]);
        return $tokenData;
    }

    public function queryResult(string $token):array {
        $v=Database::fetchOne("SELECT * FROM sm_verification WHERE token=? LIMIT 1",[$token]);
        if(!$v) return ['found'=>false,'message'=>'认证记录不存在'];
        return ['found'=>true,'status'=>$v['status'],'score'=>(float)$v['face_match_score'],'liveness_passed'=>(bool)$v['liveness_passed'],'completed_at'=>$v['completed_at']];
    }

    public function notifyCallback(array $verification,array $result):void {
        $c=require CONFIG_PATH.'/app.php';
        $url=$c['mofang']['callback_url']??'';
        if(empty($url)) return;
        $data=['token'=>$verification['token'],'status'=>$result['passed']?'passed':'failed','score'=>$result['score']??0,'timestamp'=>time()];
        $secret=$c['mofang']['api_secret']??'';
        $data['sign']=Security::hmacSign($data,$secret);
        $ch=curl_init($url);
        curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>json_encode($data),CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type:application/json'],CURLOPT_TIMEOUT=>10]);
        curl_exec($ch);
        curl_close($ch);
    }
}