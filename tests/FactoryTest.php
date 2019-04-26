<?php
/** @noinspection PhpDocSignatureInspection */
/** @noinspection PhpUnhandledExceptionInspection */

namespace webignition\CreateTaskCollectionPayload\Tests;

use PHPUnit\Framework\TestCase;
use webignition\CreateTaskCollectionPayload\Factory;
use webignition\CreateTaskCollectionPayload\InvalidPayloadDataException;
use webignition\CreateTaskCollectionPayload\Payload;
use webignition\CreateTaskCollectionPayload\TaskPayload;
use webignition\CreateTaskCollectionPayload\TypeInterface;
use webignition\Uri\Uri;

class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory();
    }

    /**
     * @dataProvider createThrowsExceptionDataProvider
     */
    public function testCreateThrowsException(
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

    public function createThrowsExceptionDataProvider(): array
    {
        return [
            'missing job identifier' => [
                'data' => [],
                'expectedException' => InvalidPayloadDataException::class,
                'expectedExceptionMessage' => 'Missing or empty "job-identifier"',
                'expectedExceptionCode' => InvalidPayloadDataException::CODE_MISSING_JOB_IDENTIFIER,
            ],
            'no tasks' => [
                'data' => [
                    Payload::KEY_JOB_IDENTIFIER => 'x123',
                ],
                'expectedException' => InvalidPayloadDataException::class,
                'expectedExceptionMessage' => 'No valid tasks found',
                'expectedExceptionCode' => InvalidPayloadDataException::CODE_NO_VALID_TASKS,
            ],
            'empty tasks' => [
                'data' => [
                    Payload::KEY_JOB_IDENTIFIER => 'x123',
                    Payload::KEY_TASKS => [],
                ],
                'expectedException' => InvalidPayloadDataException::class,
                'expectedExceptionMessage' => 'No valid tasks found',
                'expectedExceptionCode' => InvalidPayloadDataException::CODE_NO_VALID_TASKS,
            ],
            'no valid tasks' => [
                'data' => [
                    Payload::KEY_JOB_IDENTIFIER => 'x123',
                    Payload::KEY_TASKS => [
                        [],
                    ],
                ],
                'expectedException' => InvalidPayloadDataException::class,
                'expectedExceptionMessage' => 'No valid tasks found',
                'expectedExceptionCode' => InvalidPayloadDataException::CODE_NO_VALID_TASKS,
            ],
        ];
    }

    /**
     * @dataProvider createSuccessDataProvider
     */
    public function testCreateSuccess(array $data, array $expectedSerializedData)
    {
        $payload = $this->factory->createFromArray($data);

        $this->assertInstanceOf(Payload::class, $payload);
        $this->assertEquals($expectedSerializedData, $payload->jsonSerialize());
    }

    public function createSuccessDataProvider(): array
    {
        return [
            'single task' => [
                'data' => [
                    Payload::KEY_JOB_IDENTIFIER => 'x123',
                    Payload::KEY_TASKS => [
                        [
                            TaskPayload::KEY_URI => 'http://example.com/one/',
                            TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                        ],
                    ],
                ],
                'expectedSerializedData' => [
                    Payload::KEY_JOB_IDENTIFIER => 'x123',
                    Payload::KEY_TASKS => [
                        new TaskPayload(
                            new Uri('http://example.com/one/'),
                            TypeInterface::HTML_VALIDATION,
                            ''
                        ),
                    ],
                ],
            ],
            'two tasks' => [
                'data' => [
                    Payload::KEY_JOB_IDENTIFIER => 'x123',
                    Payload::KEY_TASKS => [
                        [
                            TaskPayload::KEY_URI => 'http://example.com/one/',
                            TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                        ],
                        [
                            TaskPayload::KEY_URI => 'http://example.com/two/',
                            TaskPayload::KEY_TYPE => TypeInterface::CSS_VALIDATION,
                            TaskPayload::KEY_PARAMETERS => '[]',
                        ],
                    ],
                ],
                'expectedSerializedData' => [
                    Payload::KEY_JOB_IDENTIFIER => 'x123',
                    Payload::KEY_TASKS => [
                        new TaskPayload(
                            new Uri('http://example.com/one/'),
                            TypeInterface::HTML_VALIDATION,
                            ''
                        ),
                        new TaskPayload(
                            new Uri('http://example.com/two/'),
                            TypeInterface::CSS_VALIDATION,
                            '[]'
                        ),
                    ],
                ],
            ],
            'two tasks, one invalid' => [
                'data' => [
                    Payload::KEY_JOB_IDENTIFIER => 'x123',
                    Payload::KEY_TASKS => [
                        [
                            TaskPayload::KEY_URI => 'http://example.com/one/',
                            TaskPayload::KEY_TYPE => TypeInterface::HTML_VALIDATION,
                        ],
                        [
                            TaskPayload::KEY_TYPE => TypeInterface::CSS_VALIDATION,
                            TaskPayload::KEY_PARAMETERS => '[]',
                        ],
                    ],
                ],
                'expectedSerializedData' => [
                    Payload::KEY_JOB_IDENTIFIER => 'x123',
                    Payload::KEY_TASKS => [
                        new TaskPayload(
                            new Uri('http://example.com/one/'),
                            TypeInterface::HTML_VALIDATION,
                            ''
                        ),
                    ],
                ],
            ],
        ];
    }
}
