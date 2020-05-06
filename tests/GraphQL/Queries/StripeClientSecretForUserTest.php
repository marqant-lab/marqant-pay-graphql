<?php

namespace Marqant\MarqantPayGraphQL\Tests\GraphQL\Queries;

use Marqant\MarqantPayGraphQL\Tests\MarqantPayGraphQLTestCase;

/**
 * Class StripeClientSecretForUserTest
 *
 * @package Marqant\MarqantPayGraphQL\Tests\GraphQL\Queries
 */
class StripeClientSecretForUserTest extends MarqantPayGraphQLTestCase
{
    /**
     * Test if we can receive a client secret through graphql.
     *
     * @test
     *
     * @return void
     * @throws \Exception
     */
    public function test_receive_client_secret_through_graphql(): void
    {
        /**
         * @var \App\User $User
         */

        // create a customer
        $Billable = $this->createBillableUser();

        // fire query to graphql
        $this->graphQL(/** @lang GraphQL */ '
query stripeClientSecretForUser($email: String!) {
    stripeClientSecretForUser(email: $email) {
        clientSecret
    }
}
        ', [
            'email' => $Billable->email,
        ])
            ->assertJsonStructure([
                'data' => [
                    'stripeClientSecretForUser' => [
                        'clientSecret',
                    ],
                ],
            ]);
    }
}