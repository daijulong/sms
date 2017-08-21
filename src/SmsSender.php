<?php

namespace Daijulong\Sms;

use Daijulong\Sms\Exceptions\SmsSendException;
use Daijulong\Sms\Interfaces\Sms;
use Daijulong\Sms\Supports\SmsAgent;
use Daijulong\Sms\Supports\SmsConfig;
use Daijulong\Sms\Supports\SmsResult;

class SmsSender
{

    /**
     * 接收者
     */
    protected $receiver;

    /**
     * 默认代理器
     */
    protected $default_agent;

    /**
     * 备用代理器
     */
    protected $spare_agents = [];

    /**
     * 要发送的短信
     */
    protected $sms;

    /**
     * 短信参数
     */
    protected $sms_params = [];

    /**
     * 发送结果
     */
    protected $send_results = [];

    /**
     * SmsSender constructor.
     */
    public function __construct()
    {
        $this->default_agent = SmsConfig::getDefaultAgent();
        $this->spare_agents = SmsConfig::getSpareAgents();
    }

    /**
     * 指定接收者
     *
     * @param string $mobile
     * @return $this
     */
    public function to(string $mobile): self
    {
        $this->receiver = $mobile;
        return $this;
    }

    /**
     * 指定代理器
     *
     * @param string $agent
     * @param array $spare_agents
     * @return $this
     */
    public function agent(string $agent, array $spare_agents = []): self
    {
        $this->default_agent = $agent;
        if (!empty($spare_agents)) {
            $this->spare_agents = $spare_agents;
        }
        return $this;
    }

    /**
     * 指定唯一代理器
     *
     * 如果指定的代理器发送失败，将不再尝试使用备用代理器
     *
     * @param string $agent
     * @return $this
     */
    public function onlyAgent(string $agent): self
    {
        $this->default_agent = $agent;
        $this->spare_agents = [];
        return $this;
    }

    /**
     * 要发送的短信
     *
     * @param Sms $sms
     * @return $this
     */
    public function sms(Sms $sms): self
    {
        $this->sms = $sms;
        return $this;
    }

    /**
     * 短信参数
     *
     * @param array $params
     * @return $this
     */
    public function params(array $params = []): self
    {
        $this->sms_params = $params;
        return $this;
    }

    /**
     * 发送短信
     *
     * @return bool
     * @throws SmsSendException
     */
    public function send(): bool
    {
        if (!$this->receiver) {
            throw new SmsSendException('Missing SMS receiver!');
        }
        if (!$this->sms) {
            throw new SmsSendException('Missing SMS!');
        }

        $this->send_results = [];

        $send_result = false;

        array_unshift($this->spare_agents, $this->default_agent);
        $agents = array_unique($this->spare_agents);
        if (empty($agents)) {
            throw new SmsSendException('No agent available!');
        }

        foreach ($agents as $agent_name) {
            $agent = SmsAgent::getAgent($agent_name);
            $result = $agent->send($this->receiver, $this->sms, $this->sms_params);
            $this->send_results[$agent_name] = $agent->getResult();
            if ($result) {
                $send_result = true;
                break;
            }
        }

        $this->reset();

        return $send_result;
    }

    /**
     * 取得所有发送结果
     *
     * @return array
     */
    public function getResults(): array
    {
        return $this->send_results;
    }

    /**
     * 取得最终发送结果
     *
     * 一般为$this->send_results的最后一条记录
     *
     * @return SmsResult
     */
    public function getResult(): SmsResult
    {
        return end($this->send_results);
    }

    /**
     * 重置
     *
     * 发送成功后恢复初始状态以供下一次发送
     */
    private function reset()
    {
        $this->default_agent = SmsConfig::getDefaultAgent();
        $this->spare_agents = SmsConfig::getSpareAgents();
        $this->receiver = '';
        $this->sms = null;
        $this->sms_params = [];
    }

}