<?php

namespace Daijulong\Sms\Supports;


class SmsResult
{

    const SEND_SUCCESS = 'ok';
    const SEND_FAILED = 'err';

    /**
     * 状态
     */
    private $status;

    /**
     * 成功或失败信息
     */
    private $message = '';

    /**
     * 代理器
     */
    private $agent = '';

    /**
     * 发送的内容，文本或短信模板ID
     */
    private $content = '';

    /**
     * 发送时附带参数
     */
    private $params = [];

    /**
     * 发送后所得回执ID
     */
    private $receipt_id = '';

    /**
     * 发送后所得回执的全部数据
     */
    private $receipt_data = [];

    public function __construct(string $agent = '')
    {
        $this->agent = $agent;
    }

    /**
     * 获取状态
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 获取状态名称
     *
     * @return string
     */
    public function getStatusText()
    {
        return $this->status ? self::SEND_SUCCESS : self::SEND_FAILED;
    }

    /**
     * 设置状态
     *
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * 获取消息
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 设置消息
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * 获取代理器
     *
     * @return bool
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * 获取发送内容
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 设置发送内容
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 获取发送参数
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * 设置发送参数
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * 获取回执ID
     *
     * @return string
     */
    public function getReceiptId()
    {
        return $this->receipt_id;
    }

    /**
     * 设置回执ID
     *
     * @param string $receipt_id
     * @return $this
     */
    public function setReceiptId(string $receipt_id)
    {
        $this->receipt_id = $receipt_id;
        return $this;
    }

    /**
     * 获取回执数据
     *
     * @return array
     */
    public function getReceiptData()
    {
        return $this->receipt_data;
    }

    /**
     * 设置回执数据
     *
     * @param array $receipt_data
     * @return $this
     */
    public function setReceiptData($receipt_data)
    {
        $this->receipt_data = $receipt_data;
        return $this;
    }

    /**
     * 打包数据
     *
     * @return array
     */
    private function pack()
    {
        return [
            'status' => $this->getStatusText(),
            'message' => $this->getMessage(),
            'agent' => $this->getAgent(),
            'content' => $this->getContent(),
            'params' => $this->getParams(),
            'receipt_id' => $this->getReceiptId(),
            'receipt_data' => $this->getReceiptData(),
        ];
    }

    /**
     * 默认以JSON方式输出
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * 输出结果（json）
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->pack());
    }

    /**
     * 输出结果（数组）
     *
     * @return array
     */
    public function toArray()
    {
        return $this->pack();
    }

}