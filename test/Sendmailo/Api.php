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
    private $publicKey = 'apikey';
    private $secretKey = 'secretkey';

    private function testGet()
    {
        $client = new Client($this->publicKey, $this->secretKey, true);

        $this->assertUrl('/REST/contact', $client->get(Endpoints::$Contact));

        $this->assertFilters(['id' => 2], $client->get(Endpoints::$Contact, [
            'filters' => ['id' => 2],
        ], ['version' => 'v1']));

        $response = $client->get(Endpoints::$ContactGetcontactslists, ['id' => 2]);
        $this->assertUrl('/REST/contact/2/getcontactslists', $response);

        // error on sort !
        $response = $client->get(Endpoints::$Contact, [
            'filters' => ['sort' => 'email+DESC'],
        ]);

        $this->assertUrl('/REST/contact', $response);

        $this->assertUrl('/REST/contact/2', $client->get(Endpoints::$Contact, ['id' => 2]));

        $this->assertUrl(
            '/REST/contact/test@sendmailo.com',
            $client->get(Endpoints::$Contact, ['id' => 'test@sendmailo.com'])
        );

        $this->assertHttpMethod('GET', $response);

        $this->assertGetAuth($response);

        $this->assertGetStatus(401, $response);

        $this->assertGetBody(null, '', $response);

        $this->assertGetData(null, '', $response);

        $this->assertGetCount(null, $response);

        $this->assertGetReasonPhrase('Unauthorized', $response);

        $this->assertGetTotal(null, $response);

        $this->assertSuccess(false, $response);

        $this->assertSetSecureProtocol($client);
    }

    public function testPost()
    {
        $client = new Client($this->publicKey, $this->secretKey, true);

        $email = [
            'FromName' => 'Sendmailo PHP test',
            'FromEmail' => 'gbadi@student.42.fr',
            'Text-Part' => 'Simple Email test',
            'Subject' => 'PHPunit',
            'Html-Part' => '<h3>Simple Email Test</h3>',
            'Recipients' => [['Email' => 'test@sendmailo.com']],
            'MJ-custom-ID' => 'Hello ID',
        ];

        $ret = $client->post(Endpoints::$Email, ['body' => $email]);
        print_r($ret->getBody());
        $this->assertUrl('/send', $ret);
        $this->assertPayload($email, $ret);
        $this->assertHttpMethod('POST', $ret);
        $this->assertGetAuth($ret);
        $this->assertGetStatus(401, $ret);
        $this->assertGetBody(null, 'StatusCode', $ret);
        $this->assertGetData(null, 'StatusCode', $ret);
        $this->assertGetCount(null, $ret);
        $this->assertGetReasonPhrase('Unauthorized', $ret);
        $this->assertGetTotal(null, $ret);
        $this->assertSuccess(false, $ret);
    }

    private function testPostV31()
    {
        $client = new Client($this->publicKey, $this->secretKey, false);

        $email = [
            'Messages' => [[
                'From' => ['Email' => 'test@sendmailo.com', 'Name' => 'Sendmailo PHP test'],
                'TextPart' => 'Simple Email test',
                'To' => [['Email' => 'test@sendmailo.com', 'Name' => 'Test']],
            ]],
        ];

        $ret = $client->post(Endpoints::$Email, ['body' => $email], ['version' => 'v1']);
        $this->assertUrl('/send', $ret, 'v1');
        $this->assertPayload($email, $ret);
        $this->assertHttpMethod('POST', $ret);
        $this->assertGetAuth($ret);
        $this->assertGetStatus(401, $ret);
        $this->assertGetBody(401, 'StatusCode', $ret);
        $this->assertGetData(401, 'StatusCode', $ret);
        $this->assertGetCount(null, $ret);
        $this->assertGetReasonPhrase('Unauthorized', $ret);
        $this->assertGetTotal(null, $ret);
        $this->assertSuccess(false, $ret);
    }

    public function testClientHasOptions()
    {
        $client = new Client($this->publicKey, $this->secretKey, false);
        $client->setTimeout(3);
        $client->setConnectionTimeout(5);
        $client->addRequestOption('delay', 23);
        static::assertSame(3, $client->getTimeout());
        static::assertSame(5, $client->getConnectionTimeout());
        static::assertSame(23, $client->getRequestOptions()['delay']);
    }

    private function assertUrl($url, $response, $version = self::VERSION)
    {
        static::assertSame(self::API_BASE_URL.$url, $response->getRequest()->getUrl());
    }

    private function assertPayload($payload, $response)
    {
        static::assertSame($payload, $response->getRequest()->getBody());
    }

    private function assertFilters($shouldBe, $response)
    {
        static::assertSame($shouldBe, $response->getRequest()->getFilters());
    }

    private function assertHttpMethod($payload, $response)
    {
        static::assertSame($payload, $response->getRequest()->getMethod());
    }

    private function assertGetAuth($response)
    {
        static::assertSame($this->publicKey, $response->getRequest()->getAuth()[0]);
        static::assertSame($this->secretKey, $response->getRequest()->getAuth()[1]);
    }

    private function assertGetStatus($payload, $response)
    {
        static::assertSame($payload, $response->getStatus());
    }

    private function assertGetBody($payload, $keyName, $response)
    {
        $result = null;

        if (false === empty($response->getBody()[$keyName])) {
            $result = $response->getBody()[$keyName];
        }

        static::assertSame($payload, $result);
    }

    private function assertGetData($payload, $keyName, $response)
    {
        $result = null;

        if (false === empty($response->getData()[$keyName])) {
            $result = $response->getData()[$keyName];
        }

        static::assertSame($payload, $result);
    }

    private function assertGetCount($payload, $response)
    {
        static::assertSame($payload, $response->getCount());
    }

    private function assertGetReasonPhrase($payload, $response)
    {
        static::assertSame($payload, $response->getReasonPhrase());
    }

    private function assertGetTotal($payload, $response)
    {
        static::assertSame($payload, $response->getTotal());
    }

    private function assertSuccess($payload, $response)
    {
        static::assertSame($payload, $response->success());
    }

    private function assertSetSecureProtocol($client)
    {
        static::assertTrue($client->setSecureProtocol(true));
        static::assertFalse($client->setSecureProtocol('not boolean type'));
    }
}
