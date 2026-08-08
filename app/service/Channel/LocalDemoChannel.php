<?php
/** app/service/Channel/LocalDemoChannel.php - 自研演示通道 */
class LocalDemoChannel implements ChannelInterface {
    private float $threshold;
    public function __construct(){$this->threshold=80;}
    public function getName():string {return '自研通道(演示模式)';}

    public function detect(string $imageBase64):array {
        $d=base64_decode($imageBase64);
        $img=@imagecreatefromstring($d);
        if(!$img) return ['detected'=>false,'message'=>'图像解码失败','source'=>'local'];
        $w=imagesx($img);$h=imagesy($img);
        $brightness=0;$count=0;
        for($x=0;$x<$w;$x+=3) for($y=0;$y<$h;$y+=3) {
            $rgb=imagecolorat($img,$x,$y);
            $brightness+=(($rgb>>16)&0xFF)*0.299+(($rgb>>8)&0xFF)*0.587+($rgb&0xFF)*0.114;
            $count++;
        }
        imagedestroy($img);
        $avg=$count>0?$brightness/$count:128;
        $quality=1-abs($avg-128)/128;
        return ['detected'=>$quality>0.3,'quality_score'=>round($quality,2),'source'=>'local','demo'=>true];
    }

    public function compare(string $image1,string $image2):array {
        $h1=$this->getHistogram($image1);
        $h2=$this->getHistogram($image2);
        if(empty($h1)||empty($h2)) return ['match'=>false,'score'=>0,'source'=>'local','demo'=>true];
        $dot=0;$n1=0;$n2=0;
        for($i=0;$i<256;$i++){$dot+=$h1[$i]*$h2[$i];$n1+=$h1[$i]*$h1[$i];$n2+=$h2[$i]*$h2[$i];}
        $sim=$n1>0&&$n2>0?$dot/(sqrt($n1)*sqrt($n2)):0;
        $score=round($sim*100,2);
        return ['match'=>$score>=$this->threshold,'score'=>$score,'source'=>'local','demo'=>true];
    }

    private function getHistogram(string $b64):array {
        $d=base64_decode($b64);
        $img=@imagecreatefromstring($d);
        if(!$img) return [];
        $w=imagesx($img);$h=imagesy($img);
        $hist=array_fill(0,256,0);
        for($x=0;$x<$w;$x++) for($y=0;$y<$h;$y++) {
            $rgb=imagecolorat($img,$x,$y);
            $g=(int)((($rgb>>16)&0xFF)*0.299+(($rgb>>8)&0xFF)*0.587+($rgb&0xFF)*0.114);
            $hist[$g]++;
        }
        imagedestroy($img);
        return $hist;
    }

    public function liveness(array $frames):array {
        return ['is_live'=>true,'confidence'=>0.85,'source'=>'local','demo'=>true];
    }
}