<?php

namespace App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Crypt;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ArticleType extends GraphQLType
{

   protected $attributes = [
      'name' =>  'article_type',
      'description' => 'A response article response'
   ];

   public function fields(): array
   {
      return [
         'article_id' => [
            'type' => Type::string(),
            'resolve' => function ($root, $args) {
               return Crypt::encryptString($root->fldArticleID);
            },
            'alias' => 'fldArticleID',
         ],
         'original_id' => [
            'type' => Type::string(),
            'alias' => 'fldArticleID',
         ],
         'title' => [
            'type' => Type::string(),
            'alias' => 'fldArticleTitle',
         ],
         'content' => [
            'type' => Type::string(),
            'alias' => 'fldArticleContent',
         ],
         'image' => [
            'type' => Type::string(),
            'alias' => 'fldArticleImage',
         ],
         'link' => [
            'type' => Type::string(),
            'alias' => 'fldArticleLink',
         ],
         'status' => [
            'type' => Type::boolean(),
            'alias' => 'fldArticleStatus',
         ],
         'writer' => [
            'type' =>  GraphQL::type('user_type'),
         ],
         'editor' => [
            'type' =>  GraphQL::type('user_type'),
         ],
         'date' => [
            'type' => Type::string(),
            'alias' => 'fldArticleDate',
         ],
         'company' => [
            'type' =>  GraphQL::type('company_type'),
         ],
      ];
   }
}
