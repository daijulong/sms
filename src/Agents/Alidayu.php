<?php

namespace Daijulong\Sms\Agents;

use Daijulong\Sms\Interfaces\Agent;
use Daijulong\Sms\Interfaces\Sms;
use Daijulong\Sms\SmsException;
use Daijulong\Sms\Supports\SmsResult;
use Daijulong\Sms\Traits\AgentSendResult;

class Alidayu implements Agent
{
    use AgentSendResult;

    /**
     * 代理器名称
     */
    private $agent_name = 'Alidayu';


    public function __construct($config = [])
    {
        $this->result = new SmsResult($this->agent_name);
    }

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

}