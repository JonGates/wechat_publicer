<?php

namespace WechatPublicer\Publisher\Common;


class HttpClient
{
    /**
     * 发送GET请求
     * @param string $url
     * @return string
     * @throws \Exception
     */
    public static function get(string $url): string 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
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
    public static function post(string $url, $data): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
        
        if (is_array($data)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new \Exception('CURL错误: ' . $error);
        }
        
        $result = json_decode($response, true);
        if (!$result) {
            throw new \Exception('解析响应失败: ' . $response);
        }
        
        if (isset($result['errcode']) && $result['errcode'] != 0) {
            throw new \Exception('微信API错误: ' . json_encode($result));
        }
        
        return $result;
    }
} 