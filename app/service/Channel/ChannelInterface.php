<?php
/** app/service/Channel/ChannelInterface.php - 通道接口 */
interface ChannelInterface {
    public function detect(string $imageBase64): array;
    public function compare(string $image1, string $image2): array;
    public function liveness(array $frames): array;
    public function getName(): string;
}