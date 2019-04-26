<?php

namespace webignition\CreateTaskCollectionPayload\Tests;

use PHPUnit\Framework\TestCase;
use webignition\CreateTaskCollectionPayload\Payload;
use webignition\CreateTaskCollectionPayload\TaskPayload;
use webignition\CreateTaskCollectionPayload\TypeInterface;
use webignition\Uri\Uri;

class PayloadTest extends TestCase
{
    public function testGetJobIdentifier()
    {
        $jobIdentifier = 'x123';

        $payload = new Payload($jobIdentifier, []);

        $this->assertSame($jobIdentifier, $payload->getJobIdentifier());
    }

    public function testGetTaskPayloads()
    {
        $taskPayload = new TaskPayload(new Uri('http://example.com/'), TypeInterface::HTML_VALIDATION, '');

        $payload = new Payload('x123', [$taskPayload]);

        $this->assertSame([$taskPayload], $payload->getTaskPayloads());
    }

    public function testJsonSerialize()
    {
        $jobIdentifier = 'x123';

        $uriString = 'http://example.com/';
        $uri = new Uri($uriString);
        $type = TypeInterface::HTML_VALIDATION;
        $parameters = (string) json_encode([
            'foo' => 'bar',
        ]);

        $taskPayload = new TaskPayload($uri, $type, $parameters);

        $payload = new Payload($jobIdentifier, [$taskPayload]);

        $expectedSerializedPayload = [
            Payload::KEY_JOB_IDENTIFIER => $jobIdentifier,
            Payload::KEY_TASKS => [
                $taskPayload,
            ],
        ];

        $this->assertEquals(
            $expectedSerializedPayload,
            $payload->jsonSerialize()
        );

        $this->assertEquals(
            json_encode($expectedSerializedPayload),
            json_encode($payload)
        );
    }
}
