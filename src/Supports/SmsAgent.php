<?php

namespace Daijulong\Sms\Supports;

use Daijulong\Sms\Exceptions\SmsAgentException;
use Daijulong\Sms\Interfaces\Agent;

class SmsAgent
{
    private static $agents = [];

    /**
     * 注册代理器
     *
     * @static
     * @throws SmsException
     * @param string $name 代理器名称
     * @param Agent $agent 代理器
     */
    public static function register($name, Agent $agent)
    {
        if (!is_string($name) || $name == '') {
            throw new SmsException('代理器：' . $name . ' 无效！');
        }
        self::$agents[$name] = $agent;
    }

    /**
     * 初始化
     *
     * 注册配置中的agents所声明的代理器
     *
     * @static
     */
    public static function init()
    {
        $agents = SmsConfig::getAgents();
        if (!empty($agents)) {
            foreach ($agents as $name => $config) {
                $agent_classname = 'Daijulong\\Sms\\Agents\\' . $name;
                if (class_exists($agent_classname)) {
                    self::register($name, new $agent_classname($config));
                }
            }
        }
    }

    /**
     * 获取代理器
     *
     * 代理器须已经被注册
     *
     * @static
     * @param string $agent
     * @return Agent
     * @throws SmsAgentException
     */
    public static function getAgent(string $agent): Agent
    {
        if (!isset(self::$agents[$agent])) {
            throw new SmsAgentException('The agent "' . $agent . '" not registered!');
        }
        return self::$agents[$agent];
    }

    /**
     * 取得所有代理器
     *
     * @static
     * @return array
     */
    public static function getAgents(): array
    {
        return self::$agents;
    }

}