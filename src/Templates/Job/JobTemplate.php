<?php

namespace WechatPublicer\Templates\Job;

use WechatPublicer\Templates\BaseTemplate;

class JobTemplate extends BaseTemplate {
    public $author = "招聘小助手";

    public function render($data) {
        // 实现职位模板的渲染逻辑
        return $this->renderTemplate($data);
    }

    private function renderTemplate($data) {
        // 实现具体的模板渲染逻辑
        $content = $data['content'] ?? '';
        // 处理模板内容
        return $content;
    }
} 