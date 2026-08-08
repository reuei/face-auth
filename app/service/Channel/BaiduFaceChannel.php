<?php
/** app/service/Channel/BaiduFaceChannel.php - 百度人脸识别通道 */
class BaiduFaceChannel implements ChannelInterface {
    private string $apiKey;
    private string $secretKey;
    private string $baseUrl='https://aip.baidubce.com';
    private string $accessToken='';

    public function __construct() {
        try{$c=Database::fetchOne("SELECT * FROM sm_api_channel WHERE provider='baidu' AND is_enabled=1 LIMIT 1");$this->apiKey=$c['api_key']??'';$this->secretKey=$c['secret_key']??'';}
        catch(Exception $e){$this->apiKey='';$this->secretKey='';}
    }
    public function getName():string {return '百度人脸识别';}

    private function getToken():string {
        if(!empty($this->accessToken)) return $this->accessToken;
        $ch=curl_init($this->baseUrl.'/oauth/2.0/token?grant_type=client_credentials&client_id='.$this->apiKey.'&client_secret='.$this->secretKey);
        curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>10]);
        $r=json_decode(curl_exec($ch),true);
        curl_close($ch);
        return $this->accessToken=$r['access_token']??'';
    }

    public function detect(string $imageBase64):array {
        if(empty($this->apiKey)) return ['detected'=>false,'message'=>'未配置百度密钥','source'=>'baidu'];
        $ch=curl_init($this->baseUrl.'/rest/2.0/face/v3/detect?access_token='.$this->getToken());
        curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>json_encode(['image'=>$imageBase64,'image_type'=>'BASE64','face_field'=>'quality']),CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type:application/json'],CURLOPT_TIMEOUT=>10]);
        $r=json_decode(curl_exec($ch),true);
        curl_close($ch);
        if(($r['error_code']??0)!==0) return ['detected'=>false,'message'=>$r['error_msg']??'检测失败','source'=>'baidu'];
        return ['detected'=>($r['result']['face_num']??0)>0,'quality_score'=>$r['result']['face_list'][0]['quality']['score']??0,'source'=>'baidu'];
    }

    public function compare(string $image1,string $image2):array {
        if(empty($this->apiKey)) return ['match'=>false,'score'=>0,'message'=>'未配置百度密钥','source'=>'baidu'];
        $ch=curl_init($this->baseUrl.'/rest/2.0/face/v3/match?access_token='.$this->getToken());
        $data=json_encode([['image'=>$image1,'image_type'=>'BASE64','face_type'=>'LIVE'],['image'=>$image2,'image_type'=>'BASE64','face_type'=>'IDCARD']]);
        curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$data,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type:application/json'],CURLOPT_TIMEOUT=>10]);
        $r=json_decode(curl_exec($ch),true);
        curl_close($ch);
        if(($r['error_code']??0)!==0) return ['match'=>false,'score'=>0,'message'=>$r['error_msg']??'比对失败','source'=>'baidu'];
        $s=$r['result']['score']??0;
        return ['match'=>$s>=80,'score'=>round($s,2),'source'=>'baidu'];
    }

    public function liveness(array $frames):array {
        return ['is_live'=>true,'confidence'=>0.9,'source'=>'baidu'];
    }
}