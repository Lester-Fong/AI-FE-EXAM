<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLType;


class UserInput extends GraphQLType
{
   protected $attributes = [
      'name' => 'user_input',
      'description' => 'User information',
   ];

   public function fields(): array
   {
      return [
         'firstname' => [
            'type' => Type::string(),
         ],
         'lastname' => [
            'type' => Type::string(),
         ],
         'email' => [
            'type' => Type::string(),
         ],
         'password' => [
            'type' => Type::string(),
         ],
         'action_type' => [
            'type' => Type::string(),
         ],
         'user_id' => [
            'type' => Type::string(),
         ],
         'user_type' => [
            'type' => Type::int(),
         ],
         'user_status' => [
            'type' => Type::boolean(),
         ],
      ];
   }
}
