<?php

namespace webignition\CreateTaskCollectionPayload;

class InvalidPayloadDataException extends \Exception
{
    const CODE_MISSING_JOB_IDENTIFIER = 1;
    const MESSAGE_MISSING_JOB_IDENTIFIER = 'Missing or empty "' . Payload::KEY_JOB_IDENTIFIER . '"';
    const CODE_NO_VALID_TASKS = 2;
    const MESSAGE_NO_VALID_TASKS = 'No valid tasks found';

    public static function createMissingJobIdentifierException()
    {
        return new InvalidPayloadDataException(self::MESSAGE_MISSING_JOB_IDENTIFIER, self::CODE_MISSING_JOB_IDENTIFIER);
    }

    public static function createNoValidTasksFoundException()
    {
        return new InvalidPayloadDataException(self::MESSAGE_NO_VALID_TASKS, self::CODE_NO_VALID_TASKS);
    }
}
