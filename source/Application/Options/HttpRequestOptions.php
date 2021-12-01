<?php

declare(strict_types=1);

namespace UUP\Application\Options;

use UUP\Application\Filter\HttpRequestFilter;

class HttpRequestOptions extends ApplicationOptionsBase implements ApplicationOptionsInterface
{
    public function __construct(HttpRequestFilter $filters = null)
    {
        parent::setOptions($this->getParameters($_GET, $filters));
    }

    public function getScript(): string
    {
        return basename(filter_input(INPUT_SERVER, 'PHP_SELF'));
    }

    private function getParameters(array $options, ?HttpRequestFilter $filter): array
    {
        if (isset($filter)) {
            return $filter->apply($options);
        } else {
            return $options;
        }
    }
}
