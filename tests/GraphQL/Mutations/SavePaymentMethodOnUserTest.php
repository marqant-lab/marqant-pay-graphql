<?php

namespace Marqant\MarqantPayGraphQL\Tests\GraphQL\Mutations;

use Marqant\MarqantPayGraphQL\Tests\MarqantPayGraphQLTestCase;

class SavePaymentMethodOnUserTest extends MarqantPayGraphQLTestCase
{
    /**
     * Test if we can save a payment method on a user through grpahql.
     *
     * @test
     *
     * @return void
     */
    public function test_save_payment_method_on_user_via_graphql(): void
    {
        /**
         * @var \App\User $User
         */

        // set provider string
        $provider = 'stripe';

        // create fake customer through factory
        $User = $this->createUser();

        // create customer on provider side
        $User->createCustomer($provider);

        // create sample payment method
        $PaymentMethod = $this->createPaymentMethod();

        // save payment method via stripe
        $response = $this->graphQL(/** @lang GraphQL */ '
mutation savePaymentMethodOnUser($email: String!, $paymentMethod: String!) {
    savePaymentMethodOnUser(email: $email, paymentMethod: $paymentMethod) {
        email
    }
}
        ', [
            'email'         => $User->email,
            'paymentMethod' => $PaymentMethod->object->id,
        ])
            ->assertJson([
                'data' => [
                    'savePaymentMethodOnUser' => [
                        'email' => $User->email,
                    ],
                ],
            ]);

        // refresh user with data from database
        $User = $User->refresh();

        // assert that we have a payment method on the user
        $this->assertTrue($User->hasPaymentMethod());

        // save payment method of user in a seperate variable to perform assertions on
        $PaymentMethodUser = $User->getPaymentMethod();

        // assert that the payment method id is the same
        $this->assertEquals($PaymentMethod->object->id, $PaymentMethodUser->object->id);

        // assert that the payment method belongs to the user
        $this->assertEquals($User->stripe_id, $PaymentMethodUser->object->customer);

        // assert that the brand matches
        $this->assertEquals($User->marqant_pay_card_brand, $PaymentMethodUser->object->card->brand);

        // assert that the last four digits saved match
        $this->assertEquals($User->marqant_pay_card_last_four, $PaymentMethodUser->object->card->last4);

        // assert that we can get the payment method back from the billable
        $this->assertInstanceOf(config('marqant-pay.payment_methods.card'), $PaymentMethodUser);
    }
}