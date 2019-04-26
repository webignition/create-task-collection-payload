<?php

namespace webignition\CreateTaskCollectionPayload;

use Psr\Http\Message\UriInterface;

class TaskPayload implements \JsonSerializable
{
    const KEY_URI = 'uri';
    const KEY_TYPE = 'type';
    const KEY_PARAMETERS = 'parameters';

    private $uri;
    private $type;
    private $parameters;

    public function __construct(UriInterface $uri, string $type, string $parameters)
    {
        $this->uri = $uri;
        $this->type = $type;
        $this->parameters = $parameters;
    }

    public function jsonSerialize(): array
    {
        return [
            self::KEY_URI => (string) $this->uri,
            self::KEY_TYPE => $this->type,
            self::KEY_PARAMETERS => $this->parameters,
        ];
    }
}
