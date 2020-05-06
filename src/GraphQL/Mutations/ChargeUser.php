<?php

namespace Marqant\MarqantPayGraphQL\GraphQL\Mutations;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ChargeUser
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

        // email and amount
        $email = $args['email'];
        $amount = $args['amount'];

        // transform amount from float to int
        $amount = $amount * 100;

        // get user
        $User = User::where('email', $email)
            ->firstOrFail();

        // charge the user
        return $User->charge($amount);
    }
}
