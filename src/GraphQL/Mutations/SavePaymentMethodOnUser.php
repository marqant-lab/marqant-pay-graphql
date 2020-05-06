<?php

namespace Marqant\MarqantPayGraphQL\GraphQL\Mutations;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Marqant\MarqantPay\Services\MarqantPay;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SavePaymentMethodOnUser
{
    /**
     * Return a value for the field.
     *
     * @param null                                                $rootValue   Usually contains the result returned
     *                                                                         from the parent field. In this case, it
     *                                                                         is always `null`.
     * @param mixed[]                                             $args        The arguments that were passed into the
     *                                                                         field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context     Arbitrary data that is shared between
     *                                                                         all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo                $resolveInfo Information about the query itself, such
     *                                                                         as the execution state, the field name,
     *                                                                         path to the field from the root, and
     *                                                                         more.
     *
     * @return mixed
     * @throws \Exception
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        /**
         * @var \App\User $User
         */

        $email = $args['email'];
        $payment_method = $args['paymentMethod'];

        $User = User::where('email', $email)
            ->firstOrFail();

        $PaymentMethod = MarqantPay::resolvePaymentMethod('card', ['token' => $payment_method]);

        return $User->savePaymentMethod($PaymentMethod);
    }
}
