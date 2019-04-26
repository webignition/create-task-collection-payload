<?php

namespace webignition\CreateTaskCollectionPayload;

use webignition\Uri\Normalizer;
use webignition\Uri\Uri;

class TaskPayloadFactory
{
    /**
     * @param array $data
     *
     * @return TaskPayload
     *
     * @throws InvalidTaskPayloadDataException
     */
    public function createFromArray(array $data): TaskPayload
    {
        $uriString = $data[TaskPayload::KEY_URI] ?? '';
        $uriString = trim($uriString);

        if (empty($uriString)) {
            throw InvalidTaskPayloadDataException::createMissingUriException();
        }

        $type = $data[TaskPayload::KEY_TYPE] ?? '';
        $type = trim($type);

        if (empty($type)) {
            throw InvalidTaskPayloadDataException::createMissingTypeException();
        }

        $parameters = $data[TaskPayload::KEY_PARAMETERS] ?? '';
        $parameters = trim($parameters);

        $uri = new Uri($uriString);
        $uri = Normalizer::normalize($uri);

        return new TaskPayload($uri, $type, $parameters);
    }
}
