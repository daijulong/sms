<?php

namespace Daijulong\Sms\Traits;


use Daijulong\Sms\Supports\SmsConfig;
use Daijulong\Sms\Supports\SmsResult;

trait AgentSendResult
{
    /**
     * 发送结果
     */
    private $result;

    /**
     * 配置信息
     */
    private $config = [];

    /**
     * Content constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
        $this->result = new SmsResult($this->agent_name);
    }

    /**
     * 获取发送结果
     *
     * 在发送后获取才有意义
     *
     * @return SmsResult
     */
    public function getResult(): SmsResult
    {
        return $this->result;
    }
}