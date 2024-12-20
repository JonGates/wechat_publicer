<?php

namespace WechatPublicer\Article;

class ArticleGenerator {
    private $templates = [];

    /**
     * 动态加载模板类
     */
    private function loadTemplate($templateName) {
        try {
            // 将模板名称转换为类名
            $className = ucfirst($templateName) . 'Template';
            $classPath = "Templates\\{$templateName}\\{$className}";
            
            if (class_exists($classPath)) {
                return new $classPath();
            }
            
            throw new \Exception("Template class not found: {$classPath}");
        } catch (\Exception $e) {
            error_log("加载模板失败: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 生成文章内容
     * 
     * @param array $data 包含文章内容和模板信息的数组
     * @return array
     */
    public function generateArticle($data) {
        // 获取或加载模板
        $templateType = $data['type'] ?? null;
        if (!isset($this->templates[$templateType])) {
            $this->templates[$templateType] = $this->loadTemplate($templateType);
        }
        
        $template = $this->templates[$templateType];
        if (!$template) {
            throw new \Exception("模板 {$templateType} 不存在");
        }

        // 渲染内容
        $formattedContent = $template->render($data);
        
        return [
            'title' => $data['title'] ?? '',
            'author' => $template->author,
            'digest' => $data['digest'] ?? '',
            'content' => $formattedContent,
            'need_open_comment' => 1,
            'only_fans_can_comment' => 0
        ];
    }
} 