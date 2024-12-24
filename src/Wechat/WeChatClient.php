<?php
namespace Jongates\WechatPublicer\Wechat;

use Jongates\WechatPublicer\Common\HttpClient;

class WeChatClient {
    private $tokenManager;
    private $baseUrl = 'https://api.weixin.qq.com/cgi-bin';

    public function __construct($config) {
        $this->tokenManager = new TokenManager($config);
    }

    /**
     * 上传图片
     */
    public function uploadImage($imagePath) {
        if (!file_exists($imagePath)) {
            throw new \Exception('图片文件不存在');
        }

        $url = $this->baseUrl . '/material/add_material';
        $params = [
            'access_token' => $this->tokenManager->getAccessToken(),
            'type' => 'image'
        ];

        $data = [
            'media' => new \CURLFile($imagePath)
        ];

        $result = HttpClient::post($url . '?' . http_build_query($params), $data);
        return $result['media_id'] ?? null;
    }

    /**
     * 添加草稿
     */
    public function addDraft($article) {
        $url = $this->baseUrl . '/draft/add';
        $params = [
            'access_token' => $this->tokenManager->getAccessToken()
        ];

        $data = [
            'articles' => $article
        ];

		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		//var_dump($data);
        $result = HttpClient::post($url . '?' . http_build_query($params), $data);
		//var_dump($result);
        return $result['media_id'] ?? null;
    }

    /**
     * 发布草稿
     */
    public function publishDraft($mediaId) {
        $url = $this->baseUrl . '/freepublish/submit';
        $params = [
            'access_token' => $this->tokenManager->getAccessToken()
        ];

        $data = [
            'media_id' => $mediaId
        ];

        $result = HttpClient::post($url . '?' . http_build_query($params), $data);
        return $result;
    }


}
