<?php

namespace Daijulong\Sms\Interfaces;


interface Sms
{
    public function content($agent) : string;
}