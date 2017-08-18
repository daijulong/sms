<?php

namespace Daijulong\Sms\Interfaces;


use Daijulong\Sms\Supports\SmsResult;

interface Agent
{
    public function __construct($config = []);

    public function send(string $to, Sms $sms, array $params = []):bool ;

    public function getResult(): SmsResult;

}