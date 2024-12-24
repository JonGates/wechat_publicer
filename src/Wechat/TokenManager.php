<?php
namespace Jongates\WechatPublicer\WeChat;

use Jongates\WechatPublicer\Common\HttpClient;

class TokenManager {
    private $appId;
    private $appSecret;
    private $cacheFile;

    public function __construct($config) {
        $this->appId = $config['app_id'];
        $this->appSecret = $config['app_secret'];
        $this->cacheFile = $config['cache_path'] . '/'. $config['app_id'] .'.json';
    }

    /**
     * 获取access token
     */
    public function getAccessToken() {
        $tokenData = $this->loadTokenFromCache();

        if ($tokenData && $this->isTokenValid($tokenData)) {
            return $tokenData['access_token'];
        }

        return $this->refreshAccessToken();
    }


    /**
     * 从缓存加载token
     */
    private function loadTokenFromCache() {
        if (!file_exists($this->cacheFile)) {
            return null;
        }

        return json_decode(file_get_contents($this->cacheFile), true);
    }

    /**
     * 检查token是否有效
     */
    private function isTokenValid($tokenData) {
        return isset($tokenData['expires_at']) &&
               $tokenData['expires_at'] > time() + 300; // 预留5分钟余量
    }

    /**
     * 刷新access token
     */
    private function refreshAccessToken() {
        $url = sprintf(
            'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
            $this->appId,
            $this->appSecret
        );

        $response = HttpClient::get($url);
        $result = json_decode($response, true);

        if (!isset($result['access_token'])) {
            throw new \Exception('获取access token失败: ' . json_encode($result));
        }

        $tokenData = [
            'access_token' => $result['access_token'],
            'expires_at' => time() + $result['expires_in']
        ];

        $this->saveTokenToCache($tokenData);
        return $tokenData['access_token'];
    }

    /**
     * 保存token到缓存
     */
    private function saveTokenToCache($tokenData) {
        $cacheDir = dirname($this->cacheFile);
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        file_put_contents($this->cacheFile, json_encode($tokenData));
    }

}
