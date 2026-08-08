<?php
/** app/controller/AuthController.php - 认证流程控制器 */
class AuthController {
    private FaceService $faceService;
    public function __construct(){$this->faceService=new FaceService();}
    
    public function start():void {
        $input=json_decode(file_get_contents('php://input'),true)?:[];
        $v=new Validator($input);
        $v->required('name','姓名不能为空')->required('id_card','身份证号不能为空');
        if(!$v->passes()){Response::error($v->firstError());return;}
        if(!preg_match('/^\d{17}[\dXx]$/',$input['id_card'])){Response::error('身份证号格式不正确');return;}
        $record=$this->faceService->initVerification($input);
        Response::success($record,'认证已启动');
    }

    public function submitFace():void {
        $input=json_decode(file_get_contents('php://input'),true)?:[];
        $v=new Validator($input);
        $v->required('verification_id','认证ID不能为空')->required('image','人脸图片不能为空');
        if(!$v->passes()){Response::error($v->firstError());return;}
        $detect=$this->faceService->detect($input['image']);
        if(!$detect['detected']){Response::error('未检测到有效人脸');return;}
        $path=$this->saveImage($input['image']);
        Database::update('sm_verification',['face_image'=>$path],'id=?',[(int)$input['verification_id']]);
        Response::success(['detected'=>true,'quality_score'=>$detect['quality_score']??0]);
    }

    public function submitLiveness():void {
        $input=json_decode(file_get_contents('php://input'),true)?:[];
        $v=new Validator($input);
        $v->required('verification_id','认证ID不能为空')->required('action_type','动作类型不能为空')->required('frames','帧数据不能为空');
        if(!$v->passes()){Response::error($v->firstError());return;}
        $result=$this->faceService->liveness($input['frames']);
        Database::insert('sm_liveness',['verification_id'=>(int)$input['verification_id'],'action_type'=>$input['action_type'],'action_result'=>$result['is_live']?1:0,'confidence'=>$result['confidence']??0,'details'=>json_encode($result)]);
        Response::success($result);
    }

    public function complete():void {
        $input=json_decode(file_get_contents('php://input'),true)?:[];
        $v=new Validator($input);
        $v->required('verification_id','认证ID不能为空');
        if(!$v->passes()){Response::error($v->firstError());return;}
        $vid=(int)$input['verification_id'];
        $record=Database::fetchOne("SELECT * FROM sm_verification WHERE id=? LIMIT 1",[$vid]);
        if(!$record){Response::error('认证记录不存在');return;}
        $compare=$this->faceService->compare($record['face_image'],$input['compare_image']??'');
        $result=['passed'=>$compare['match']??false,'score'=>$compare['score']??0,'liveness_passed'=>true,'source'=>$compare['source']??'local','fail_reason'=>$compare['match']?'':'人脸比对分数过低'];
        $this->faceService->completeVerification($record['token'],$result);
        $mofang=new MofangFinanceService();
        $mofang->notifyCallback($record,$result);
        Response::success($result);
    }

    private function saveImage(string $b64):string {
        $dir=PUBLIC_PATH.'/uploads/faces/'.date('Y/m/d');
        if(!is_dir($dir)) mkdir($dir,0755,true);
        $fn=Security::generateToken(16).'.jpg';
        file_put_contents($dir.'/'.$fn,base64_decode($b64));
        return '/uploads/faces/'.date('Y/m/d').'/'.$fn;
    }
}