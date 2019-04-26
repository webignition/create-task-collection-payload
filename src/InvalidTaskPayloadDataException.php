<?php

namespace webignition\CreateTaskCollectionPayload;

class InvalidTaskPayloadDataException extends \Exception
{
    const CODE_MISSING_URI = 1;
    const CODE_MISSING_TYPE = 2;
    const MESSAGE_MISSING_FIELD = 'Missing or empty "%s"';

    public static function createMissingUriException()
    {
        return new InvalidTaskPayloadDataException(
            sprintf(self::MESSAGE_MISSING_FIELD, TaskPayload::KEY_URI),
            self::CODE_MISSING_URI
        );
    }

    public static function createMissingTypeException()
    {
        return new InvalidTaskPayloadDataException(
            sprintf(self::MESSAGE_MISSING_FIELD, TaskPayload::KEY_TYPE),
            self::CODE_MISSING_TYPE
        );
    }
}
