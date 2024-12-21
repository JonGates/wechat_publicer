<?php

namespace WechatPublicer\Publisher;

use WechatPublicer\WeChat\WeChatClient;
use WechatPublicer\Article\ArticleGenerator;

class Publisher {
    private $wechat;
    private $article_generator;
	private $config;

    public function __construct(array $config) {
		$this->config = $config;

		if (!isset($this->config['cache_path'])) {
			throw new \Exception('cache_path 不能为空');
		}

		if (!isset($this->config['templates_path'])) {
			throw new \Exception('templates_path 不能为空');
		}

		if (!empty($this->config['cache_path']) && !is_dir($this->config['cache_path'])) {
			mkdir($this->config['cache_path'], 0777, true);
		}

		if (!empty($this->config['templates_path']) && !is_dir($this->config['templates_path'])) {
			throw new \Exception('模板目录不存在：' . $this->config['templates_path']);
		}

        $this->wechat = new WeChatClient($this->config);
        $this->article_generator = new ArticleGenerator($this->config);
    }

    /**
     * 发布文章到公众号
     */
    public function publishArticle($data) {
        $article = $this->article_generator->generateArticle($data);
        $mediaId = $this->wechat->addDraft($article);
        return $this->wechat->publishDraft($mediaId);
    }

    /**
     * 发布图文
     */
    public function publishArticleWithImage($data) {
		$articles = [];
		foreach ($data as $item) {
			// 优先使用thumb_media_id
			if (isset($item['thumb_media_id'])) {
				$thumbMediaId = $item['thumb_media_id'];
			} else {
				if (isset($item['thumb_media_path'])) {
					// 如果thumb_media_id为空，则上传封面图片
					$thumbMediaId = $this->wechat->uploadImage($item['thumb_media_path']);
				} else {
					// 如果thumb_media_path为空，则使用默认封面图片
					$thumbMediaId = '4uyfP5XSWLkLfwA93nlMsfAWFXcuixT1bnLry3da6kyMSPL9S4C-m9i4sekp1zcb';
				}
			}

			$_article = $this->article_generator->generateArticle($item);
			$_article['thumb_media_id'] = $thumbMediaId;
			$articles[] = $_article;
		}

		$mediaId = $this->wechat->addDraft($articles);
        return $this->wechat->publishDraft($mediaId);
    }
}
