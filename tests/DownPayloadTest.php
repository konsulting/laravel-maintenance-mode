<?php

namespace Konsulting\Laravel\MaintenanceMode\Tests;

use Illuminate\Support\Carbon;
use Konsulting\Laravel\MaintenanceMode\DownPayload;

class DownPayloadTest extends TestCase
{
    /** @test */
    public function it_stores_the_timestamp()
    {
        $info = new DownPayload(['time' => Carbon::now()->getTimestamp()]);
        $this->assertEquals(Carbon::now(), $info->getTime());

        $this->assertEquals(Carbon::now(), (new DownPayload([]))->getTime());
    }

    /** @test */
    public function it_stores_the_message()
    {
        $info = new DownPayload(['message' => 'A message']);
        $this->assertSame('A message', $info->getMessage());
    }

    /** @test */
    public function it_stores_the_retry_time()
    {
        $noRetry = new DownPayload([]);
        $this->assertSame(0, $noRetry->getRetryTime());

        $info = new DownPayload(['retry' => 110]);
        $this->assertSame(110, $info->getRetryTime());
    }

    /** @test */
    public function it_stores_the_allowed_addresses()
    {
        $info = new DownPayload(['allowed' => ['127.0.0.1']]);
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

        $info = new DownPayload($payload);
        $this->assertSame($payload, $info->toArray());
    }
}
