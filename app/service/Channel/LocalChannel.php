<?php
/** app/service/Channel/LocalChannel.php - 自研人脸通道(真实实现) */
class LocalChannel implements ChannelInterface {
    private float $matchThreshold;
    private float $livenessThreshold;

    public function __construct() {
        $this->matchThreshold = 80.0;
        $this->livenessThreshold = 0.75;
    }

    public function getName(): string { return '自研通道'; }

    /**
     * 人脸检测 - 基于肤色检测+几何分析
     */
    public function detect(string $imageBase64): array {
        $data = base64_decode($imageBase64);
        $img = @imagecreatefromstring($data);
        if (!$img) {
            return ['detected' => false, 'message' => '图像解码失败', 'source' => 'local'];
        }

        $width = imagesx($img);
        $height = imagesy($img);

        // 最小尺寸检查
        if ($width < 100 || $height < 100) {
            imagedestroy($img);
            return ['detected' => false, 'message' => '图像尺寸过小', 'source' => 'local'];
        }

        // 肤色区域检测
        $skinPixels = 0;
        $totalSampled = 0;

        for ($y = 0; $y < $height; $y += 2) {
            for ($x = 0; $x < $width; $x += 2) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                if ($this->isSkinColor($r, $g, $b)) $skinPixels++;
                $totalSampled++;
            }
        }

        $skinRatio = $totalSampled > 0 ? $skinPixels / $totalSampled : 0;

        // 计算图像质量指标
        $brightness = $this->calculateBrightness($img, $width, $height);
        $contrast = $this->calculateContrast($img, $width, $height);
        $sharpness = $this->calculateSharpness($img, $width, $height);

        // 人脸检测判断
        $hasSkin = $skinRatio > 0.05 && $skinRatio < 0.85;
        $goodBrightness = $brightness > 50 && $brightness < 220;
        $goodContrast = $contrast > 20;

        // 计算质量分
        $quality = 0;
        if ($hasSkin) $quality += 0.4;
        if ($goodBrightness) $quality += 0.3;
        if ($goodContrast) $quality += 0.2;
        if ($sharpness > 0.3) $quality += 0.1;

        // 中心区域肤色比例（人脸通常在中心）
        $centerSkinRatio = $this->getCenterRegionSkinRatio($img, $width, $height);
        if ($centerSkinRatio > 0.15) $quality += 0.15;

        imagedestroy($img);

        $detected = $quality >= 0.5;
        return [
            'detected' => $detected,
            'quality_score' => round(min($quality, 1.0), 2),
            'skin_ratio' => round($skinRatio, 3),
            'brightness' => round($brightness, 1),
            'contrast' => round($contrast, 1),
            'sharpness' => round($sharpness, 3),
            'source' => 'local'
        ];
    }

    /**
     * 人脸比对 - 直方图+几何特征+余弦相似度
     */
    public function compare(string $image1, string $image2): array {
        $img1 = @imagecreatefromstring(base64_decode($image1));
        $img2 = @imagecreatefromstring(base64_decode($image2));

        if (!$img1 || !$img2) {
            if ($img1) imagedestroy($img1);
            if ($img2) imagedestroy($img2);
            return ['match' => false, 'score' => 0, 'message' => '图像解码失败', 'source' => 'local'];
        }

        // 提取特征向量
        $features1 = $this->extractFeatures($img1);
        $features2 = $this->extractFeatures($img2);

        // 计算余弦相似度
        $similarity = $this->cosineSimilarity($features1, $features2);
        $score = round($similarity * 100, 2);

        // 直方图相似度
        $hist1 = $this->getGrayscaleHistogram($img1);
        $hist2 = $this->getGrayscaleHistogram($img2);
        $histSimilarity = $this->histogramSimilarity($hist1, $hist2);

        // 综合评分
        $finalScore = round(($score * 0.7 + $histSimilarity * 0.3), 2);

        imagedestroy($img1);
        imagedestroy($img2);

        return [
            'match' => $finalScore >= $this->matchThreshold,
            'score' => $finalScore,
            'threshold' => $this->matchThreshold,
            'cosine_similarity' => $score,
            'histogram_similarity' => round($histSimilarity, 2),
            'source' => 'local'
        ];
    }

    /**
     * 活体检测 - 帧间运动分析
     */
    public function liveness(array $frames): array {
        if (count($frames) < 3) {
            return ['is_live' => false, 'confidence' => 0, 'message' => '帧数不足', 'source' => 'local'];
        }

        $motionScores = [];
        $brightnessChanges = [];

        for ($i = 1; $i < count($frames); $i++) {
            $img1 = @imagecreatefromstring(base64_decode($frames[$i - 1]));
            $img2 = @imagecreatefromstring(base64_decode($frames[$i]));

            if (!$img1 || !$img2) continue;

            $w = min(imagesx($img1), imagesx($img2));
            $h = min(imagesy($img1), imagesy($img2));

            // 计算帧间差异
            $diff = 0;
            $count = 0;
            $b1 = 0;
            $b2 = 0;

            for ($x = 0; $x < $w; $x += 4) {
                for ($y = 0; $y < $h; $y += 4) {
                    $rgb1 = imagecolorat($img1, $x, $y);
                    $rgb2 = imagecolorat($img2, $x, $y);

                    $g1 = (($rgb1 >> 16) & 0xFF) * 0.299 + (($rgb1 >> 8) & 0xFF) * 0.587 + ($rgb1 & 0xFF) * 0.114;
                    $g2 = (($rgb2 >> 16) & 0xFF) * 0.299 + (($rgb2 >> 8) & 0xFF) * 0.587 + ($rgb2 & 0xFF) * 0.114;

                    $diff += abs($g1 - $g2);
                    $b1 += $g1;
                    $b2 += $g2;
                    $count++;
                }
            }

            $avgDiff = $count > 0 ? $diff / $count : 0;
            $motionScores[] = $avgDiff;
            $brightnessChanges[] = abs($b1 / $count - $b2 / $count);

            imagedestroy($img1);
            imagedestroy($img2);
        }

        // 活体判断：帧间有自然的微小变化（照片完全静止，视频回放变化规律）
        $avgMotion = count($motionScores) > 0 ? array_sum($motionScores) / count($motionScores) : 0;
        $motionVariance = $this->calculateVariance($motionScores);

        // 真实活体特征：
        // 1. 有微小运动（不是完全静止的照片）
        // 2. 运动量自然变化（不是完全规律的视频回放）
        // 3. 亮度有轻微变化
        $hasNaturalMotion = $avgMotion > 1.0 && $avgMotion < 50.0;
        $hasMotionVariance = $motionVariance > 0.5;
        $hasBrightnessChange = count($brightnessChanges) > 0 && max($brightnessChanges) > 0.5;

        $confidence = 0;
        if ($hasNaturalMotion) $confidence += 0.4;
        if ($hasMotionVariance) $confidence += 0.35;
        if ($hasBrightnessChange) $confidence += 0.25;

        return [
            'is_live' => $confidence >= $this->livenessThreshold,
            'confidence' => round($confidence, 2),
            'avg_motion' => round($avgMotion, 2),
            'motion_variance' => round($motionVariance, 2),
            'source' => 'local'
        ];
    }

    /**
     * 判断是否为肤色 (RGB色彩空间)
     */
    private function isSkinColor(int $r, int $g, int $b): bool {
        // 规则1: RGB肤色范围
        $rule1 = $r > 95 && $g > 40 && $b > 20
            && $r > $g && $r > $b
            && ($r - $g) > 15
            && abs($r - $g) > 15;

        // 规则2: 宽松RGB肤色范围
        $rule2 = $r > 60 && $g > 30 && $b > 15
            && $r > $g && $r > $b
            && ($r - $b) > 10;

        return $rule1 || $rule2;
    }

    /**
     * 计算平均亮度
     */
    private function calculateBrightness($img, int $w, int $h): float {
        $total = 0;
        $count = 0;
        for ($x = 0; $x < $w; $x += 3) {
            for ($y = 0; $y < $h; $y += 3) {
                $rgb = imagecolorat($img, $x, $y);
                $total += (($rgb >> 16) & 0xFF) * 0.299 + (($rgb >> 8) & 0xFF) * 0.587 + ($rgb & 0xFF) * 0.114;
                $count++;
            }
        }
        return $count > 0 ? $total / $count : 128;
    }

    /**
     * 计算对比度（标准差）
     */
    private function calculateContrast($img, int $w, int $h): float {
        $values = [];
        for ($x = 0; $x < $w; $x += 4) {
            for ($y = 0; $y < $h; $y += 4) {
                $rgb = imagecolorat($img, $x, $y);
                $values[] = (($rgb >> 16) & 0xFF) * 0.299 + (($rgb >> 8) & 0xFF) * 0.587 + ($rgb & 0xFF) * 0.114;
            }
        }
        return $this->calculateStdDev($values);
    }

    /**
     * 计算清晰度（Laplacian方差）
     */
    private function calculateSharpness($img, int $w, int $h): float {
        $laplacianSum = 0;
        $count = 0;

        for ($y = 1; $y < $h - 1; $y += 2) {
            for ($x = 1; $x < $w - 1; $x += 2) {
                $c = $this->grayValue($img, $x, $y);
                $l = $this->grayValue($img, $x - 1, $y);
                $r = $this->grayValue($img, $x + 1, $y);
                $u = $this->grayValue($img, $x, $y - 1);
                $d = $this->grayValue($img, $x, $y + 1);

                $laplacian = abs($l + $r + $u + $d - 4 * $c);
                $laplacianSum += $laplacian * $laplacian;
                $count++;
            }
        }

        return $count > 0 ? sqrt($laplacianSum / $count) / 255 : 0;
    }

    /**
     * 获取中心区域肤色比例
     */
    private function getCenterRegionSkinRatio($img, int $w, int $h): float {
        $cx = (int)($w / 2);
        $cy = (int)($h / 2);
        $rw = (int)($w / 4);
        $rh = (int)($h / 4);

        $skin = 0;
        $total = 0;

        for ($y = $cy - $rh; $y < $cy + $rh; $y += 3) {
            for ($x = $cx - $rw; $x < $cx + $rw; $x += 3) {
                if ($x < 0 || $y < 0 || $x >= $w || $y >= $h) continue;
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                if ($this->isSkinColor($r, $g, $b)) $skin++;
                $total++;
            }
        }

        return $total > 0 ? $skin / $total : 0;
    }

    /**
     * 提取特征向量（用于比对）
     */
    private function extractFeatures($img): array {
        $w = imagesx($img);
        $h = imagesy($img);
        $features = [];

        // 特征1: 宽高比
        $features[] = $w / $h;

        // 特征2: 平均亮度
        $features[] = $this->calculateBrightness($img, $w, $h) / 255.0;

        // 特征3: 对比度
        $features[] = $this->calculateContrast($img, $w, $h) / 128.0;

        // 特征4-12: 3x3网格区域亮度
        for ($gy = 0; $gy < 3; $gy++) {
            for ($gx = 0; $gx < 3; $gx++) {
                $features[] = $this->getRegionBrightness($img, $w, $h, $gx, $gy, 3, 3) / 255.0;
            }
        }

        // 特征13: 左右对称性
        $left = $this->getRegionBrightness($img, $w, $h, 0, 1, 2, 3);
        $right = $this->getRegionBrightness($img, $w, $h, 1, 1, 2, 3);
        $features[] = abs($left - $right) / 255.0;

        // 特征14: 上下亮度差异
        $top = $this->getRegionBrightness($img, $w, $h, 0, 0, 3, 2);
        $bottom = $this->getRegionBrightness($img, $w, $h, 0, 1, 3, 2);
        $features[] = abs($top - $bottom) / 255.0;

        // 特征15: 肤色比例
        $skin = 0;
        $total = 0;
        for ($y = 0; $y < $h; $y += 3) {
            for ($x = 0; $x < $w; $x += 3) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                if ($this->isSkinColor($r, $g, $b)) $skin++;
                $total++;
            }
        }
        $features[] = $total > 0 ? $skin / $total : 0;

        return $features;
    }

    /**
     * 获取指定网格区域亮度
     */
    private function getRegionBrightness($img, int $w, int $h, int $gx, int $gy, int $cols, int $rows): float {
        $rw = (int)($w / $cols);
        $rh = (int)($h / $rows);
        $sx = $gx * $rw;
        $sy = $gy * $rh;

        $total = 0;
        $count = 0;

        for ($y = $sy; $y < min($sy + $rh, $h); $y += 2) {
            for ($x = $sx; $x < min($sx + $rw, $w); $x += 2) {
                $rgb = imagecolorat($img, $x, $y);
                $total += (($rgb >> 16) & 0xFF) * 0.299 + (($rgb >> 8) & 0xFF) * 0.587 + ($rgb & 0xFF) * 0.114;
                $count++;
            }
        }

        return $count > 0 ? $total / $count : 128;
    }

    /**
     * 获取灰度直方图
     */
    private function getGrayscaleHistogram($img): array {
        $w = imagesx($img);
        $h = imagesy($img);
        $hist = array_fill(0, 256, 0);

        for ($x = 0; $x < $w; $x += 2) {
            for ($y = 0; $y < $h; $y += 2) {
                $rgb = imagecolorat($img, $x, $y);
                $g = (int)((($rgb >> 16) & 0xFF) * 0.299 + (($rgb >> 8) & 0xFF) * 0.587 + ($rgb & 0xFF) * 0.114);
                $hist[min(255, $g)]++;
            }
        }

        return $hist;
    }

    /**
     * 直方图相似度（巴氏系数）
     */
    private function histogramSimilarity(array $h1, array $h2): float {
        $sum1 = array_sum($h1);
        $sum2 = array_sum($h2);

        if ($sum1 == 0 || $sum2 == 0) return 0;

        $bc = 0;
        for ($i = 0; $i < 256; $i++) {
            $bc += sqrt(($h1[$i] / $sum1) * ($h2[$i] / $sum2));
        }

        return $bc;
    }

    /**
     * 余弦相似度
     */
    private function cosineSimilarity(array $a, array $b): float {
        $dot = 0;
        $normA = 0;
        $normB = 0;

        $len = min(count($a), count($b));
        for ($i = 0; $i < $len; $i++) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }

        if ($normA == 0 || $normB == 0) return 0;
        return $dot / (sqrt($normA) * sqrt($normB));
    }

    /**
     * 灰度值
     */
    private function grayValue($img, int $x, int $y): float {
        $rgb = imagecolorat($img, $x, $y);
        return (($rgb >> 16) & 0xFF) * 0.299 + (($rgb >> 8) & 0xFF) * 0.587 + ($rgb & 0xFF) * 0.114;
    }

    /**
     * 标准差
     */
    private function calculateStdDev(array $values): float {
        $n = count($values);
        if ($n == 0) return 0;
        $mean = array_sum($values) / $n;
        $sum = 0;
        foreach ($values as $v) $sum += ($v - $mean) * ($v - $mean);
        return sqrt($sum / $n);
    }

    /**
     * 方差
     */
    private function calculateVariance(array $values): float {
        $n = count($values);
        if ($n == 0) return 0;
        $mean = array_sum($values) / $n;
        $sum = 0;
        foreach ($values as $v) $sum += ($v - $mean) * ($v - $mean);
        return $sum / $n;
    }
}