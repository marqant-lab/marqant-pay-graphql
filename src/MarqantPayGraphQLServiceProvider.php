<?php

namespace Marqant\MarqantPayGraphQL;

use Illuminate\Support\ServiceProvider;

/**
 * Class MarqantPayGraphQLServiceProvider
 *
 * @package Marqant\MarqantPayGraphQL
 */
class MarqantPayGraphQLServiceProvider extends ServiceProvider
{

    public function boot()
    {
      //////////////////////////////////
      // Custom Queries //
      //////////////////////////////////

      $this->registerQueries();

      //////////////////////////////////
      // Custom Mutations //
      //////////////////////////////////

      $this->registerMutations();
    }

    public function registerQueries()
    {
        config([
            'lighthouse.namespaces.queries' => array_merge((array) config('lighthouse.namespaces.queries'),
                (array) 'Marqant\\MarqantPayGraphQL\\GraphQL\\Queries'),
        ]);
    }

    public function registerMutations()
    {
        config([
            'lighthouse.namespaces.mutations' => array_merge((array) config('lighthouse.namespaces.mutations'),
                (array) 'Marqant\\MarqantPayGraphQL\\GraphQL\\Mutations'),
        ]);
    }

}
