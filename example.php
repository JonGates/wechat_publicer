<?php

require_once __DIR__ . '/vendor/autoload.php';

use WechatPublicer\Publisher\Publisher;

// 配置信息
$config = [
    'app_id' => 'wx5555555555555555',
    'app_secret' => '55555555555555555555555555555555',
    'cache_path' => __DIR__ . '/cache',
    'token' => '55555555555555555555555555555555',
    'encoding_aes_key' => '55555555555555555555555555555555',
];

// 创建Publisher实例
$publisher = new Publisher($config);

// 模板信息
$template = [
    'type' => 'job',
    'title' => '招聘信息',
    'content' => '职位描述...',
    'cover_path' => 'path/to/image.jpg'
];

// 发布文章
$result = $publisher->publishArticleWithImage($template); 

// 输出结果
print_r($result);
