<?php

namespace Daijulong\Sms\Agents;

use Daijulong\Sms\Exceptions\SmsException;
use Daijulong\Sms\Interfaces\Agent;
use Daijulong\Sms\Interfaces\Sms;
use Daijulong\Sms\Traits\AgentSendResult;

class Content implements Agent
{

    use AgentSendResult;

    /**
     * 代理器名称
     */
    private $agent_name = 'Content';

    /**
     * 发送短信
     *
     * @param string $to
     * @param Sms $sms
     * @param array $params
     * @return bool
     * @throws SmsException
     */
    public function send(string $to, Sms $sms, array $params = []): bool
    {
        $content = $sms->content($this->agent_name);
        if (!$content) {
            throw new SmsException('The agent : ' . $this->agent_name . ' not supported by SMS');
        }
        $full_content = $this->getFullContent($content, $params);
        $this->result->setStatus(true)->setContent($full_content)->setParams($params);
        return true;
    }

    /**
     * 取得完整短信内容
     *
     * 替换短信中的变量
     *
     * @param string $content
     * @param array $params
     * @return string
     */
    private function getFullContent(string $content, array $params = [])
    {
        return str_replace(array_map(function ($key) {
            return '${' . $key . '}';
        }, array_keys($params)), array_values($params), $content);
    }

}