<?php

namespace webignition\CreateTaskCollectionPayload\Tests;

use PHPUnit\Framework\TestCase;
use webignition\CreateTaskCollectionPayload\TaskPayload;
use webignition\CreateTaskCollectionPayload\TypeInterface;
use webignition\Uri\Uri;

class TaskPayloadTest extends TestCase
{
    public function testJsonSerialize()
    {
        $uriString = 'http://example.com/';
        $uri = new Uri($uriString);
        $type = TypeInterface::HTML_VALIDATION;
        $parameters = (string) json_encode([
            'foo' => 'bar',
        ]);

        $taskPayload = new TaskPayload($uri, $type, $parameters);

        $expectedSerializedPayload = [
            TaskPayload::KEY_URI => $uriString,
            TaskPayload::KEY_TYPE => $type,
            TaskPayload::KEY_PARAMETERS => $parameters,
        ];

        $this->assertEquals(
            $expectedSerializedPayload,
            $taskPayload->jsonSerialize()
        );

        $this->assertEquals(
            json_encode($expectedSerializedPayload),
            json_encode($taskPayload)
        );
    }
}
