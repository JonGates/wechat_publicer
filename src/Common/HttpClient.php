<?php

namespace Jongates\WechatPublicer\Common;


class HttpClient
{
    /**
     * 发送GET请求
     * @param string $url
     * @return string
     * @throws \Exception
     */
    public static function get(string $url, $headers = []): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		if ($headers) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('CURL错误: ' . $error);
        }

        return $response;
    }

    /**
     * 发送POST请求
     * @param string $url
     * @param array|string $data
     * @return array
     * @throws \Exception
     */
    public static function post(string $url, $data, $headers = []): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);

        // 检查是否包含文件上传
        if (isset($data['media']) && $data['media'] instanceof \CURLFile) {
            // 文件上传不需要 json_encode
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // 文件上传需要设置正确的 Content-Type
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
        } else {
            // 普通数据使用 JSON 格式
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ?
                json_encode($data, JSON_UNESCAPED_UNICODE) : $data);

            if (is_array($data)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=UTF-8']);
            }
        }

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('CURL错误: ' . $error);
        }

        $decoded = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return ['response' => $response];
    }
}
