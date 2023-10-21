<?php

namespace App;

use Fouladgar\OTP\Contracts\SMSClient;
use Fouladgar\OTP\Notifications\Messages\MessagePayload;

class SampleSMSClient implements SMSClient
{
//    public function __construct(protected SampleSMSService $SMSService)
//    {
//    }

    public function sendMessage(MessagePayload $payload): mixed
    {
        return true;
    }

    // ...
}
