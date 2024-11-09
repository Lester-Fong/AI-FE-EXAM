<?php

namespace App\GraphQL\Inputs;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class CompanyInput extends InputType
{


   protected $attributes = [
      'name' => 'company_input',
      'description' => 'Company information'
   ];

   public function fields(): array
   {
      return [
         'company_id' => [
            'type' => Type::string(),
         ],
         'name' => [
            'type' => Type::string(),
         ],
         'status' => [
            'type' => Type::boolean(),
         ],
         'action_type' => [
            'type' => Type::string(),
         ],
      ];
   }
}
