<?php

namespace App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Crypt;

class CompanyType extends GraphQLType
{

   protected $attributes = [
      'name' =>  'company_type',
      'description' => 'A response company response'
   ];

   public function fields(): array
   {
        return [
            'company_id' => [
                'type' => Type::string(),
                'alias' => 'fldCompanyID',
                'resolve' => function ($root, $args) {
                    return Crypt::encryptString($root->fldCompanyID);
                },
            ],
            'original_id' => [
                'type' => Type::string(),
                'alias' => 'fldCompanyID',
            ],
            'name' => [
                'type' => Type::string(),
                'alias' => 'fldCompanyName',
            ],
            'logo' => [
                'type' => Type::string(),
                'alias' => 'fldCompanyLogo',
            ],
            'status' => [
                'type' => Type::string(),
                'alias' => 'fldCompanyStatus',
            ],
        ];
   }
}
