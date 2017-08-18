<?php

namespace Daijulong\Sms\Agents;

use Daijulong\Sms\Interfaces\Agent;
use Daijulong\Sms\Interfaces\Sms;
use Daijulong\Sms\Supports\CurlRequest;
use Daijulong\Sms\Supports\SmsConfig;
use Daijulong\Sms\Traits\AgentSendResult;

class Aliyun implements Agent
{
    use AgentSendResult;

    /**
     * URL
     */
    const ENDPOINT_URL = 'http://dysmsapi.aliyuncs.com/';

    /**
     * 地域
     */
    const ENDPOINT_REGION = 'cn-hangzhou';


    /**
     * 代理器名称
     */
    private $agent_name = 'Aliyun';

    /**
     * 发送短信
     *
     * @param string $to
     * @param Sms $sms
     * @param array $params
     * @return bool
     */
    public function send(string $to, Sms $sms, array $params = []): bool
    {
        $content = $sms->content($this->agent_name);
        if (!$content) {
            $this->result->setStatus(false)->setMessage('The agent : ' . $this->agent_name . ' not supported by SMS');
            return false;
        }

        $send_params = [
            'RegionId' => self::ENDPOINT_REGION,
            'AccessKeyId' => $this->config['access_key_id'],
            'Format' => 'JSON',
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid(),
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'Action' => 'SendSms',
            'Version' => '2017-05-25',
            'PhoneNumbers' => $to,
            'SignName' => SmsConfig::getSign(),
            'TemplateCode' => $content,
            'TemplateParam' => json_encode($params, JSON_FORCE_OBJECT),
            'OutId' => '',
        ];
        $send_params['Signature'] = $this->signature($send_params);

        $curl = new CurlRequest();
        list($code,$res) = $curl->request('POST',self::ENDPOINT_URL,['body' =>$send_params ]);

        $this->result->setReceiptData($res)->setContent($content)->setParams($params);

        $receipt_data = json_decode($res,true);
        if ($receipt_data === false) {
            $this->result->setStatus(false)->setMessage('Request failed!');
            return false;
        }
        $this->result->setMessage($receipt_data['Message'])->setReceiptId($receipt_data['RequestId']);
        if ($code == 200) {
            if($receipt_data['Code'] != 'OK') {
                $this->result->setStatus(false);
                return false;
            } else{
                $this->result->setStatus(true)->setReceiptId($receipt_data['BizId']);
                return true;
            }
        } else {
            $this->result->setStatus(false);
            return false;
        }

    }

    /**
     * 生成签名
     *
     * @param array $params
     * @return string
     */
    private function signature(array $params)
    {
        ksort($params);
        $accessKeySecret = $this->config['access_key_secret'];
        $stringToSign = 'POST&%2F&' . urlencode(http_build_query($params, null, '&', PHP_QUERY_RFC3986));
        return base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
    }
}