<?php

namespace Marqant\MarqantPayGraphQL\Tests\GraphQL\Queries;

use Marqant\MarqantPayGraphQL\Tests\MarqantPayGraphQLTestCase;

class UserPaymentsTest extends MarqantPayGraphQLTestCase
{
    /**
     * Test if we can get a list of payments for a given billable.
     *
     * @test
     *
     * @return void
     */
    public function test_get_list_of_payments(): void
    {
        /**
         * @var \App\User $User
         */

        // Update config so we have the marqant-pay.invoice_service
        // setting set to the PdfInvoice service
        $PdfInvoiceService = \Marqant\MarqantPayInvoices\Services\PdfInvoice::class;
        config(['marqant-pay.invoice_service' => $PdfInvoiceService]);

        $amount = 999; // 9,99 ($|€|...)

        // create fake customer through factory
        $User = $this->createBillableUser();

        // charge the user
        $Payment = $User->charge($amount, 'test_get_list_of_payments');

        // check that we got back an instance of Payment
        $this->assertInstanceOf(config('marqant-pay.payment_model'), $Payment);

        // check the amount
        $this->assertEquals($amount, $Payment->amount);

        // check if we billed the correct user
        $this->assertEquals($User->provider_id, $Payment->customer);

        // create invoice on payment
        $Payment->createInvoice();

        // now fire the graphql query and check if we
        // have access to this payment through grpahql
        $response = $this->graphQL(/** @lang GraphQL */ '
query payments($email: String!) {
    userPayments(email: $email) {
        status
        provider
        amount
        nr
        invoice
    }
}
        ', [
            'email' => $User->email,
        ])
            ->assertJson([
                'data' => [
                    'userPayments' => [
                        0 => [
                            'status'   => 'succeeded',
                            'provider' => 'stripe',
                            'amount'   => $amount,
                        ],
                    ],
                ],
            ]);
    }
}