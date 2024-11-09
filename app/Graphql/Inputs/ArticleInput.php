<?php

namespace App\GraphQL\Inputs;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class ArticleInput extends InputType
{


   protected $attributes = [
      'name' => 'article_input',
      'description' => 'Article information'
   ];

   public function fields(): array
   {
      return [
         'article_id' => [
            'type' => Type::string(),
         ],
         'title' => [
            'type' => Type::string(),
         ],
         'link' => [
            'type' => Type::string(),
         ],
         'date' => [
            'type' => Type::string(),
         ],
         'company_id' => [
            'type' => Type::string(),
         ],
         'content' => [
            'type' => Type::string(),
         ],
         'status' => [
            'type' => Type::string(),
         ],
         'action_type' => [
            'type' => Type::string(),
         ],
      ];
   }
}
