<?php

namespace WechatPublicer\Article;

class ArticleGenerator {
	private $config;

    public function __construct($config) {
		$this->config = $config;
	}

    /**
     * 动态加载模板类
     */
    private function loadTemplate($templateType) {
        try {
			$template_path = $this->config['templates_path'] . '/'. $templateType .'.html';
			if (!file_exists($template_path)) {
				throw new \Exception("模板文件不存在: {$template_path}");
			}
			$template = file_get_contents($template_path);
			return $template;
        } catch (\Exception $e) {
            echo ("模板加载失败: " . $e->getMessage());
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
		try {
			// 获取或加载模板
			$templateType = $data['type'];
			if (!$templateType) {
				throw new \Exception("模板类型不能为空");
			}

			// 获取或加载模板
			$content = $this->loadTemplate($templateType);

			// 替换模板中的变量
			foreach ($data as $key => $value) {
				$content = str_replace('{' . $key . '}', $value, $content);
			}

			// 去除多余的换行和空格
			$content = str_replace("\n", "", $content);
			$content = str_replace("    ", "", $content);
			$content = str_replace("  ", " ", $content);

			// 返回文章数据
			return [
				'title' => $data['title'] ?? '',
				'author' => $data['author'] ?? '',
				'digest' => $data['digest'] ?? '',
				'content' => $content,
				'need_open_comment' => 1,
				'only_fans_can_comment' => 0
			];
		} catch (\Exception $e) {
			echo $e->getMessage();
		}


    }
}
