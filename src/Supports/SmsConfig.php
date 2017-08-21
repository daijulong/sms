<?php

namespace Daijulong\Sms\Supports;


class SmsConfig
{
    /**
     * 短信签名
     */
    private static $sign = '';

    /**
     * 默认代理器
     */
    private static $default_agent = '';

    /**
     * 备用代理器
     */
    private static $spare_agents = [];

    /**
     * 代理器
     */
    private static $agents = [];

    /**
     * 超时时间
     */
    private static $timeout = 0;

    /**
     * 连接超时时间
     */
    private static $connect_timeout = 0;

    /**
     * 扩展代理器命名空间
     */
    private static $agent_ext_namespace = '';

    /**
     * 加载配置
     *
     * @static
     * @param array $config 所有配置，一般来自配置文件
     */
    public static function load(array $config = [])
    {
        isset($config['sign']) && is_string($config['sign']) ? self::setSign($config['sign']) : null;
        isset($config['default_agent']) && is_string($config['default_agent']) ? self::setDefaultAgent($config['default_agent']) : null;
        isset($config['spare_agents']) && is_array($config['spare_agents']) ? self::setSpareAgents($config['spare_agents']) : null;
        isset($config['agents']) && is_array($config['agents']) ? self::setAgents($config['agents']) : null;
        isset($config['timeout']) && is_int($config['timeout']) ? self::setTimeout($config['timeout']) : null;
        isset($config['connect_timeout']) && is_int($config['connect_timeout']) ? self::setConnectTimeout($config['connect_timeout']) : null;
        isset($config['agent_ext_namespace']) && is_string($config['agent_ext_namespace']) ? self::setAgentExtNamespace($config['agent_ext_namespace']) : null;
    }

    /**
     * 获取短信签名
     *
     * @static
     * @return string
     */
    public static function getSign(): string
    {
        return self::$sign;
    }

    /**
     * @param string $sign
     */
    public static function setSign(string $sign)
    {
        self::$sign = $sign;
    }

    /**
     * 获取默认代理器
     *
     * @static
     * @return string
     */
    public static function getDefaultAgent(): string
    {
        return self::$default_agent;
    }

    /**
     * 设置默认代理器
     *
     * @static
     * @param string $default_agent
     */
    public static function setDefaultAgent(string $default_agent)
    {
        self::$default_agent = $default_agent;
    }

    /**
     * 获取备用代理器
     *
     * @static
     * @return array
     */
    public static function getSpareAgents(): array
    {
        return self::$spare_agents;
    }

    /**
     * 设置备用代理器
     *
     * @static
     * @param array $spare_agents
     */
    public static function setSpareAgents(array $spare_agents)
    {
        self::$spare_agents = $spare_agents;
    }

    /**
     * 获取所有代理器
     *
     * @static
     * @return array
     */
    public static function getAgents(): array
    {
        return self::$agents;
    }

    /**
     * 设置代理器
     *
     * @static
     * @param array $agents
     */
    public static function setAgents(array $agents)
    {
        self::$agents = $agents;
    }


    /**
     * 获取超时时间
     *
     * @static
     * @return int
     */
    public static function getTimeout(): int
    {
        return self::$timeout;
    }

    /**
     * 设置超时时间
     *
     * @static
     * @param int $timeout
     */
    public static function setTimeout(int $timeout)
    {
        self::$timeout = $timeout > 0 ? $timeout : 0;
    }

    /**
     * 获取连接超时时间
     *
     * @static
     * @return int
     */
    public static function getConnectTimeout(): int
    {
        return self::$connect_timeout;
    }

    /**
     * 设置连接超时时间
     *
     * @static
     * @param int $connect_timeout
     */
    public static function setConnectTimeout(int $connect_timeout)
    {
        self::$connect_timeout = $connect_timeout > 0 ? $connect_timeout : 0;
    }

    /**
     * 获取扩展代理器命名空间
     *
     * @static
     * @return string
     */
    public static function getAgentExtNamespace(): string
    {
        return self::$agent_ext_namespace;
    }

    /**
     * 设置扩展代理器命名空间
     *
     * @static
     * @param string $connect_timeout
     */
    public static function setAgentExtNamespace(string $connect_timeout)
    {
        self::$agent_ext_namespace = $connect_timeout;
    }

}