<?php

namespace Marqant\MarqantPayGraphQL\GraphQL\Queries;

/**
 * Class GetPlansData
 *
 * @package Marqant\MarqantPayGraphQL\GraphQL\Queries
 */
class GetPlansData
{

    /**
     * @param       $rootValue
     * @param array $args
     *
     * @return array
     */
    public function __invoke($rootValue, array $args): array
    {
        return ['test' => 'test'];
    }

}
