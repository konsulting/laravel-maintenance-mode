<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests;

use Illuminate\Support\Carbon;
use Konsulting\Laravel\MaintenanceMode\DownInformation;

class DownInformationTest extends TestCase
{
    /** @test */
    public function it_stores_the_timestamp()
    {
        $info = new DownInformation(['time' => Carbon::now()->getTimestamp()]);
        $this->assertEquals(Carbon::now(), $info->getTime());
    }

    /** @test */
    public function it_stores_the_message()
    {
        $info = new DownInformation(['message' => 'A message']);
        $this->assertSame('A message', $info->getMessage());
    }

    /** @test */
    public function it_stores_the_retry_time()
    {
        $noRetry = new DownInformation([]);
        $this->assertSame(0, $noRetry->getRetryTime());

        $info = new DownInformation(['retry' => 110]);
        $this->assertSame(110, $info->getRetryTime());
    }

    /** @test */
    public function it_stores_the_allowed_addresses()
    {
        $info = new DownInformation(['allowed' => ['127.0.0.1']]);
        $this->assertSame(['127.0.0.1'], $info->getAllowedAddresses());
    }

    /** @test */
    public function it_gets_the_whole_payload()
    {
        $payload = [
            'time'    => Carbon::now()->getTimestamp(),
            'message' => 'A message',
            'retry'   => 110,
            'allowed' => ['127.0.0.1'],
        ];

        $info = new DownInformation($payload);
        $this->assertSame($payload, $info->getPayload());
    }
}
