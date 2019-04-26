<?php

namespace webignition\SfsResultModels\Tests;

use PHPUnit\Framework\TestCase;
use webignition\CreateTaskCollectionPayload\Payload;
use webignition\CreateTaskCollectionPayload\TaskPayload;
use webignition\CreateTaskCollectionPayload\TypeInterface;
use webignition\Uri\Uri;

class PayloadTest extends TestCase
{
    public function testJsonSerialize()
    {
        $jobIdentifier = 'x123';

        $uriString = 'http://example.com/';
        $uri = new Uri($uriString);
        $type = TypeInterface::HTML_VALIDATION;
        $parameters = json_encode([
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
