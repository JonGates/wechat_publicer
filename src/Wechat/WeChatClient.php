<?php
namespace Jongates\WechatPublicer\Wechat;

use Jongates\WechatPublicer\Common\HttpClient;

class WeChatClient {
	private $config;
    private $tokenManager;
    private $baseUrl = 'https://api.weixin.qq.com/cgi-bin';

    public function __construct($config) {
        $this->config = $config;
        $this->tokenManager = new TokenManager($config);
    }

    /**
     * 图片处理
     */
	public function image_process($imagePath) {
		try {
			// 判断是否是远程图片
			if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
				// 是远程图片,下载到缓存目录
				$imageContent = file_get_contents($imagePath);
				if ($imageContent === false) {
					throw new \Exception('远程图片下载失败');
				}
				$ext = pathinfo(parse_url($imagePath, PHP_URL_PATH), PATHINFO_EXTENSION);
				$ext = $ext ?: 'jpg';
				$localPath = $this->config['cache_path'] . '/upload/image/' . md5($imagePath) . '.' . $ext;
				// 创建缓存目录
				if (!is_dir($this->config['cache_path']. '/upload/image')) {
					if (!mkdir($this->config['cache_path']. '/upload/image', 0777, true)) {
						throw new \Exception('缓存目录创建失败');
					}
				}
				if (!file_put_contents($localPath, $imageContent)) {
					throw new \Exception('图片保存失败');
				}
				$imagePath = $localPath;
			}
			if (!file_exists($imagePath)) {
				throw new \Exception('图片文件不存在');
			}
			return $imagePath;
		} catch (\Exception $e) {
			throw new \Exception('图片处理失败: ' . $e->getMessage());
		}
	}

    /**
     * 上传图片
     */
	public function upload_image($imagePath) {
		try{
			$imagePath = $this->image_process($imagePath);
			$url = $this->baseUrl . '/media/uploadimg';
			$params = [
				'access_token' => $this->tokenManager->getAccessToken(),
				'type' => 'image'
			];

			$data = [
				'media' => new \CURLFile($imagePath)
			];
			$result = HttpClient::post($url . '?' . http_build_query($params), $data);
			return $result;
		} catch (\Exception $e) {
			throw new \Exception('上传临时素材失败: ' . $e->getMessage());
		}

	}


    /**
     * 上传永久素材
     */
    public function upload_material($imagePath) {
		try{
			$imagePath = $this->image_process($imagePath);
			$url = $this->baseUrl . '/material/add_material';
			$params = [
				'access_token' => $this->tokenManager->getAccessToken(),
				'type' => 'image'
			];

			$data = [
				'media' => new \CURLFile($imagePath)
			];
			$result = HttpClient::post($url . '?' . http_build_query($params), $data);
			return $result;
		} catch (\Exception $e) {
			throw new \Exception('上传临时素材失败: ' . $e->getMessage());
		}
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
