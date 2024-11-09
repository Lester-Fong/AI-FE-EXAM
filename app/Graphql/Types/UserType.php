<?php

namespace App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\CustomScalarType;
use Crypt;

class UserType extends GraphQLType
{

   protected $attributes = [
      'name' =>  'user_type',
      'description' => 'A response user response'
   ];

   public function fields(): array
   {
      return [

         'user_id' => [
            'type' => Type::string(),
            'resolve' => function ($root, $args) {
               return Crypt::encryptString($root['fldUserID']);
            },
            'alias' => 'fldUserID',
         ],
         'original_id' => [
            'type' => Type::string(),
            'alias' => 'fldUserID',
         ],
         'firstname' => [
            'type' => Type::string(),
            'alias' => 'fldUserFirstname',
         ],
         'lastname' => [
            'type' => Type::string(),
            'alias' => 'fldUserLastname',
         ],
         'email' => [
            'type' => Type::string(),
            'alias' => 'fldUserEmail',
         ],
         'user_type' => [
            'type' => Type::boolean(),
            'alias' => 'fldUserType',
         ],
         'user_status' => [
            'type' => Type::boolean(),
            'alias' => 'fldUserStatus',
         ],
      ];
   }
}
