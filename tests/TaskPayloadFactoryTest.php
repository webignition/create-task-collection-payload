<?php
/** @noinspection PhpDocSignatureInspection */
/** @noinspection PhpUnhandledExceptionInspection */

namespace webignition\CreateTaskCollectionPayload\Tests;

use PHPUnit\Framework\TestCase;
use webignition\CreateTaskCollectionPayload\InvalidTaskPayloadDataException;
use webignition\CreateTaskCollectionPayload\TaskPayload;
use webignition\CreateTaskCollectionPayload\TaskPayloadFactory;
use webignition\CreateTaskCollectionPayload\TypeInterface;

class TaskPayloadFactoryTest extends TestCase
{
    /**
     * @var TaskPayloadFactory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new TaskPayloadFactory();
    }

    /**
     * @dataProvider createFromArrayThrowsExceptionDataProvider
     */
    public function testCreateFromArrayThrowsException(
        array $data,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->factory->createFromArray($data);
    }

    public function createFromArrayThrowsExceptionDataProvider(): array
    {
        return [
            'missing uri' => [
                'data' => [],
                'expectedException' => InvalidTaskPayloadDataException::class,
                'expectedExceptionMessage' => 'Missing or empty "uri"',
                'expectedExceptionCode' => InvalidTaskPayloadDataException::CODE_MISSING_URI,
            ],
            'missing type' => [
                'data' => [
                    TaskPayload::KEY_URI => 'http://example.com/',
                ],
                'expectedException' => InvalidTaskPayloadDataException::class,
                'expectedExceptionMessage' => 'Missing or empty "type"',
                'expectedExceptionCode' => InvalidTaskPayloadDataException::CODE_MISSING_TYPE,
            ],
        ];
    }

    /**
     * @dataProvider createSuccessDataProvider
     */
    public function testCreateSuccess(
        array $data,
        array $expectedSerializedData
    ) {
        $taskPayload = $this->factory->createFromArray($data);

        $this->assertInstanceOf(TaskPayload::class, $taskPayload);
        $this->assertEquals($expectedSerializedData, $taskPayload->jsonSerialize());
    }

    public function createSuccessDataProvider(): array
    {
        return [
            'missing parameters' => [
                'data' => [
                    TaskPayload::KEY_URI => 'http://example.com/',
                    TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                ],
                'expectedSerializedData' => [
                    TaskPayload::KEY_URI => 'http://example.com/',
                    TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                    TaskPayload::KEY_PARAMETERS => '',
                ],
            ],
            'empty parameters' => [
                'data' => [
                    TaskPayload::KEY_URI => 'http://example.com/',
                    TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                    TaskPayload::KEY_PARAMETERS => '',
                ],
                'expectedSerializedData' => [
                    TaskPayload::KEY_URI => 'http://example.com/',
                    TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                    TaskPayload::KEY_PARAMETERS => '',
                ],
            ],
            'non-empty parameters' => [
                'data' => [
                    TaskPayload::KEY_URI => 'http://example.com/',
                    TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                    TaskPayload::KEY_PARAMETERS => '[]',
                ],
                'expectedSerializedData' => [
                    TaskPayload::KEY_URI => 'http://example.com/',
                    TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                    TaskPayload::KEY_PARAMETERS => '[]',
                ],
            ],
            'uri is normalized' => [
                'data' => [
                    TaskPayload::KEY_URI => 'http://example.com/../../',
                    TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                ],
                'expectedSerializedData' => [
                    TaskPayload::KEY_URI => 'http://example.com/',
                    TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                    TaskPayload::KEY_PARAMETERS => '',
                ],
            ],
        ];
    }
}