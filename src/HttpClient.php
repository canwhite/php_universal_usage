<?php

namespace Zack\PhpUniversalUsage;

class HttpClient
{
    // 同步HTTP请求 - 使用cURL
    public function makeGetRequest($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            throw new \Exception("cURL Error: " . $error);
        }

        return [
            'status_code' => $httpCode,
            'body' => $response,
            'success' => $httpCode >= 200 && $httpCode < 300
        ];
    }

    // POST请求
    public function makePostRequest($url, $data = [], $headers = [])
    {
        $ch = curl_init();

        $jsonData = json_encode($data);

        $defaultHeaders = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ];
        $headers = array_merge($defaultHeaders, $headers);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            throw new \Exception("cURL Error: " . $error);
        }

        return [
            'status_code' => $httpCode,
            'body' => $response,
            'success' => $httpCode >= 200 && $httpCode < 300
        ];
    }

    // 文件上传
    public function uploadFile($url, $filePath, $fieldName = 'file')
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: " . $filePath);
        }

        $ch = curl_init();

        $postFields = [
            $fieldName => new \CURLFile($filePath)
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            throw new \Exception("cURL Error: " . $error);
        }

        return [
            'status_code' => $httpCode,
            'body' => $response,
            'success' => $httpCode >= 200 && $httpCode < 300
        ];
    }

    public function demonstrateApiCalls()
    {
        echo "=== HTTP请求演示 ===" . PHP_EOL;

        // 演示GET请求（使用公共API）
        try {
            echo "发送GET请求..." . PHP_EOL;
            $response = $this->makeGetRequest('https://httpbin.org/get');

            if ($response['success']) {
                $data = json_decode($response['body'], true);
                echo "GET请求成功!" . PHP_EOL;
                echo "状态码: " . $response['status_code'] . PHP_EOL;
            } else {
                echo "GET请求失败，状态码: " . $response['status_code'] . PHP_EOL;
            }
        } catch (\Exception $e) {
            echo "GET请求异常: " . $e->getMessage() . PHP_EOL;
        }

        // 演示POST请求
        try {
            echo PHP_EOL . "发送POST请求..." . PHP_EOL;
            $postData = [
                'name' => '张三',
                'email' => 'zhangsan@example.com',
                'message' => 'Hello from PHP'
            ];

            $response = $this->makePostRequest('https://httpbin.org/post', $postData);

            if ($response['success']) {
                $data = json_decode($response['body'], true);
                echo "POST请求成功!" . PHP_EOL;
                echo "发送的数据: " . json_encode($data['json'], JSON_UNESCAPED_UNICODE) . PHP_EOL;
            } else {
                echo "POST请求失败，状态码: " . $response['status_code'] . PHP_EOL;
            }
        } catch (\Exception $e) {
            echo "POST请求异常: " . $e->getMessage() . PHP_EOL;
        }
    }
}