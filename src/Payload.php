<?php

namespace webignition\CreateTaskCollectionPayload;

class Payload implements \JsonSerializable
{
    const KEY_JOB_IDENTIFIER = 'job-identifier';
    const KEY_TASKS = 'tasks';

    private $jobIdentifier;
    private $taskPayloads;

    public function __construct(string $jobIdentifier, array $taskPayloads)
    {
        $this->jobIdentifier = $jobIdentifier;
        foreach ($taskPayloads as $taskPayload) {
            if ($taskPayload instanceof TaskPayload) {
                $this->taskPayloads[] = $taskPayload;
            }
        }
    }

    public function jsonSerialize(): array
    {
        return [
            self::KEY_JOB_IDENTIFIER => $this->jobIdentifier,
            self::KEY_TASKS => $this->taskPayloads,
        ];
    }
}
