<?php

namespace Marqant\MarqantPayGraphQL\Tests;

use Marqant\MarqantPay\Tests\MarqantPayTestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

// TODO: make this test autoload - which it isn't for some reason right now ...
abstract class MarqantPayGraphQLTestCase extends MarqantPayTestCase
{
    use MakesGraphQLRequests;
}