<?php

namespace App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ResponseType extends GraphQLType
{
   protected $attributes = [
      'name' => 'response_type',
      'description' => 'A response type response'
   ];

   public function fields(): array
   {
      return [
         'error' => [
            'type' => Type::boolean(),
         ],
         'message' => [
            'type' => Type::string(),
         ],
         'access_token' => [
            'type' => Type::string(),
         ],
         'refresh_token' => [
            'type' => Type::string(),
         ],
         'token_expiration' => [
            'type' => Type::string(),
         ],
         'user' => [
            'type' => GraphQL::type('user_type'),
         ],
         'email' => [
            'type' => Type::string(),
         ],
         'login_type' => [
            'type' => Type::string(),
         ],
      ];
   }
}
