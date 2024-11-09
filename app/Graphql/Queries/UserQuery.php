<?php

namespace App\GraphQL\Queries;

use App\Models\User;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Log;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UserQuery extends Query
{

    protected $attributes = [
        'name' => 'user_query',
        'description' => 'Retrieves user information',
    ];

    public function args(): array
    {
        return [
            'action_type' => ['type' => Type::string()],
            'user_id' => ['type' => Type::string()],
        ];
    }

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('user_type'));
    }

    public function resolve($root, $args)
    {
        $user_model = new User();

        if ($args['action_type'] == "current_user") {
            $response[] = $user_model->getInfo();
        }
        
        if ($args['action_type'] == "display_all_users") {
            $response  = $user_model->displayAllUser();
        }

        return $response;
    }
}