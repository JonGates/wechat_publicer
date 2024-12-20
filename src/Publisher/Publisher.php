<?php

namespace WechatPublicer\Publisher;

use WechatPublicer\WeChat\WeChatClient;
use WechatPublicer\Article\ArticleGenerator;

class Publisher {
    private $wechat;
    private $article_generator;

    public function __construct($config) {
        $this->wechat = new WeChatClient($config);
        $this->article_generator = new ArticleGenerator();
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
        if (isset($data['cover_path'])) {
            $thumbMediaId = $this->wechat->uploadImage($data['cover_path']);
        } else {
            $thumbMediaId = '4uyfP5XSWLkLfwA93nlMsfAWFXcuixT1bnLry3da6kyMSPL9S4C-m9i4sekp1zcb';
        }
        
        $article = $this->article_generator->generateArticle($data);
        $article['thumb_media_id'] = $thumbMediaId;
        $mediaId = $this->wechat->addDraft($article);
        return $this->wechat->publishDraft($mediaId);
    }
} 