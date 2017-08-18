<?php

namespace Daijulong\Sms\Supports;


class CurlRequest
{

    /**
     * 发起请求
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return array [code, content]
     */
    public function request(string $method, string $url, $options = []): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (isset($options['body'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($options['body']) ? http_build_query($options['body']) : $options['body']);
        }
        if (SmsConfig::getTimeout() > 0) {
            curl_setopt($ch, CURLOPT_TIMEOUT, SmsConfig::getTimeout());
        }
        if (SmsConfig::getConnectTimeout()) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, SmsConfig::getConnectTimeout());
        }
        //https request
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if (isset($options['headers']) && is_array($options['headers']) && 0 < count($options['headers'])) {
            $http_headers = $this->getHttpHearders($options['headers']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
        }
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [$code, $content];
    }

    /**
     * 组装header信息
     *
     * @param array $headers
     * @return array
     */
    private function getHttpHearders(array $headers)
    {
        $http_headers = array();
        foreach ($headers as $key => $value) {
            array_push($http_headers, $key . ":" . $value);
        }
        return $http_headers;
    }
}