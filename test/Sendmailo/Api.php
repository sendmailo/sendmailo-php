<?php

declare(strict_types=1);

/*
 * Copyright (C) 2021 Sendmailo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Sendmailo;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class SendmailoApi extends TestCase
{
    const API_BASE_URL = 'https://sendmailo.com/api';
    const VERSION = '';
    private $publicKey =    'apikey';
    private $secretKey = 'secretkey';
 
    public function testPost()
    {
        $call = new Api([$this->publicKey, $this->secretKey]);

        $emailBody = [
            'FromName' => 'Sendmailo PHP test',
            "from"=> [
                "email"=> "test@sendmailo.com",
                 "name"=> "sendmailo"
            ],
            'subject' => 'PHPunit',
            'templateID' => 11,
            'to' => [['email' => 'sendmailo@gmail.com', "name"=> "sendmailo"]]
            
        ];

        $ret = $call->call("POST","/send",  $emailBody);
        $body= $ret->getBody();
        $stringBody = (string) $body;
        print_r($stringBody);
        $this->assertGetStatus(401, $ret );
      
    }
    private function assertGetStatus($payload, $response)
    {
        static::assertSame($payload, $response->getStatusCode());
    }
 
 
}
