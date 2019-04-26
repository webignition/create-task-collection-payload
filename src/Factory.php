<?php

namespace webignition\CreateTaskCollectionPayload;

class Factory
{
    private $taskPayloadFactory;

    public function __construct()
    {
        $this->taskPayloadFactory = new TaskPayloadFactory();
    }

    /**
     * @param array $data
     *
     * @return Payload
     *
     * @throws InvalidPayloadDataException
     */
    public function createFromArray(array $data): Payload
    {
        $jobIdentifier = $data[Payload::KEY_JOB_IDENTIFIER] ?? null;
        $jobIdentifier = trim($jobIdentifier);

        if (empty($jobIdentifier)) {
            throw InvalidPayloadDataException::createMissingJobIdentifierException();
        }

        $tasksData = $data[Payload::KEY_TASKS] ?? [];
        $taskPayloads = [];

        foreach ($tasksData as $taskData) {
            try {
                $taskPayload = $this->taskPayloadFactory->createFromArray($taskData);
            } catch (InvalidTaskPayloadDataException $invalidTaskPayloadDataException) {
                $taskPayload = null;
            }

            if ($taskPayload) {
                $taskPayloads[] = $taskPayload;
            }
        }

        if (empty($taskPayloads)) {
            throw InvalidPayloadDataException::createNoValidTasksFoundException();
        }

        return new Payload($jobIdentifier, $taskPayloads);
    }
}
