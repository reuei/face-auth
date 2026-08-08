<?php
/** app/controller/ApiController.php - API控制器 */
class ApiController {
    private FaceService $faceService;
    private MofangFinanceService $mofangService;
    public function __construct(){$this->faceService=new FaceService();$this->mofangService=new MofangFinanceService();}

    public function dispatch(string $route):void {
        $method=$_SERVER['REQUEST_METHOD'];
        $input=json_decode(file_get_contents('php://input'),true)?:[];
        $map=[
            'POST v1/auth/init'=>fn()=>$this->initAuth($input),
            'POST v1/auth/verify-token'=>fn()=>$this->verifyToken($input),
            'POST v1/auth/result'=>fn()=>$this->getResult($input),
            'POST v1/face/detect'=>fn()=>$this->faceDetect($input),
            'POST v1/face/compare'=>fn()=>$this->faceCompare($input),
            'POST v1/face/liveness'=>fn()=>$this->faceLiveness($input),
            'POST v1/mofang/init'=>fn()=>$this->mofangInit($input),
            'POST v1/mofang/notify'=>fn()=>$this->mofangNotify($input),
            'GET v1/mofang/query'=>fn()=>$this->mofangQuery($_GET),
        ];
        $key=$method.' '.$route;
        if(isset($map[$key])){$map[$key]();}else{Response::error('接口不存在',404);}
    }

    public function initAuth(array $input):void {
        $v=new Validator($input);$v->required('user_id','用户ID不能为空');
        if(!$v->passes()){Response::error($v->firstError());return;}
        $tokenData=TokenService::generate((int)$input['user_id'],(int)($input['order_id']??0));
        Response::success($tokenData);
    }
    public function verifyToken(array $input):void {
        $r=TokenService::verify($input['token']??'');
        Response::success(['valid'=>$r!==null,'info'=>$r]);
    }
    public function getResult(array $input):void {
        $v=$this->faceService->getVerification($input['token']??'');
        if(!$v){Response::error('记录不存在',404);return;}
        Response::success(['status'=>$v['status'],'score'=>(float)$v['face_match_score'],'completed_at'=>$v['completed_at']]);
    }
    public function faceDetect(array $input):void {
        $v=new Validator($input);$v->required('image','图片不能为空');
        if(!$v->passes()){Response::error($v->firstError());return;}
        Response::success($this->faceService->detect($input['image']));
    }
    public function faceCompare(array $input):void {
        $v=new Validator($input);$v->required('image1')->required('image2');
        if(!$v->passes()){Response::error($v->firstError());return;}
        Response::success($this->faceService->compare($input['image1'],$input['image2']));
    }
    public function faceLiveness(array $input):void {
        Response::success($this->faceService->liveness($input['frames']??[]));
    }
    public function mofangInit(array $input):void {
        $v=new Validator($input);$v->required('user_id');
        if(!$v->passes()){Response::error($v->firstError());return;}
        Response::success($this->mofangService->initVerification($input));
    }
    public function mofangNotify(array $input):void {Response::success(['received'=>true]);}
    public function mofangQuery(array $input):void {
        Response::success($this->mofangService->queryResult($input['token']??''));
    }
}