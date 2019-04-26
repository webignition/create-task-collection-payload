<?php

namespace webignition\CreateTaskCollectionPayload\Tests;

use PHPUnit\Framework\TestCase;
use webignition\CreateTaskCollectionPayload\TaskPayload;
use webignition\CreateTaskCollectionPayload\TypeInterface;
use webignition\Uri\Uri;

class TaskPayloadTest extends TestCase
{
    public function testGetUri()
    {
        $uri = new Uri('http://example.com/');

        $taskPayload = new TaskPayload($uri, TypeInterface::HTML_VALIDATION, '');

        $this->assertSame($uri, $taskPayload->getUri());
    }

    public function testGetType()
    {
        $type = TypeInterface::HTML_VALIDATION;

        $taskPayload = new TaskPayload(new Uri('http://example.com/'), $type, '');

        $this->assertSame($type, $taskPayload->getType());
    }

    public function testGetParameters()
    {
        $parameters = (string) json_encode([
            'foo' => 'bar',
        ]);

        $taskPayload = new TaskPayload(new Uri('http://example.com/'), TypeInterface::HTML_VALIDATION, $parameters);

        $this->assertSame($parameters, $taskPayload->getParameters());
    }

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
