<?php

namespace App\GraphQL\Mutations;

use App\Models\Article;
use App\Models\Helper;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Log;
use Config;
use Crypt;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ArticleMutation extends Mutation
{

   protected $attributes = [
      'name' => 'ArticleMutation'
   ];


   public function args(): array
   {
      return [
         'article' => ['type' => GraphQL::type('article_input')],
         'file' => ['type' => GraphQL::type('Upload')],
      ];
   }

   public function type(): Type
   {
      return GraphQL::type('response_type');
   }


   public function validationErrorMessages(array $args = []): array
   {
      return [
         'article.name.required' => 'Please enter name',
         'article.article_category_id.required' => 'Please select article category',
         'article.description.required' => 'Please enter description',
         'article.meta_title.required' => 'Please provide meta title',
         'article.meta_description.required' => 'Please provide meta description',
         'article.meta_tags.required' => 'Please provide meta tags',
         'article.link.required' => 'Please provide link',
         'article.date_publish.required' => 'Please provide date published',
         'article.link.unique' => 'Link already exists',
         'file.required' => 'Please upload a file',
         'file.mimes' => 'Please upload a valid file type (jpeg, jpg, png, webp)',
         'file1.required' => 'Please upload a file',
         'file1.mimes' => 'Please upload a valid file type (jpeg, jpg, png, webp)',
      ];
   }

   public function rules(array $args = []): array
   {
      $rules = [];

      $article = $args['article'];

      if ($article['action_type'] == "add_article_record" || $article['action_type'] == "update_record") {
         $rules['article.title'] = ['required'];
         $rules['article.company_id'] = ['required'];
         $rules['article.content'] = ['required'];

         if ($article['action_type'] == "add_article_record") {
            $rules['article.link'] = ['required'];
            $rules['file'] = ['required', 'mimes:jpeg,jpg,png,webp'];
         } else {
            // link
            $rules['article.link'] = ['required', 'unique:tblArticles,fldArticleLink,' . Crypt::decryptString($article['article_id']) . ',fldArticleID'];

            if (isset($args['file'])) {
               $rules['file'] = ['mimes:jpeg,jpg,png,webp'];
            }
         }
      }

      return $rules;
   }


   public function resolve($root, $args)
   {
      $article = $args['article'];
      $helper_model = new Helper();
      $response_obj = new \stdClass();

      $article_model = new Article();

      if ($article["action_type"] == "add_article_record") {
         $response_obj = $article_model->addUpdateRecord(0, $article, $args['file']);
      }

      if ($article["action_type"] == "update_record") {
         $article_id = Crypt::decryptString($article['article_id']);

         $article_record = $article_model->find($article_id);

         if ($article_record) {
            $response_obj = $article_model->addUpdateRecord($article_id, $article, $args['file']);
         } else {
            $response_obj = new \stdClass();
            $response_obj->error = true;
            $response_obj->message = Config::get('Constants.MESSAGES')['RECORD_NOT_FOUND'];
         }
      }

      if ($article["action_type"] == "delete_record") {
         $response_obj = $article_model->deleteRecord($article);
      }

      if ($article['action_type'] == "upload_image_to_editor") {
         $file = $args['file1'];
         $filename = $helper_model->ImageUpload($file, 0, "article_summernote");
         $response_obj->error = false;
         $response_obj->filename = $filename;
      }

      return $response_obj;
   }
}
