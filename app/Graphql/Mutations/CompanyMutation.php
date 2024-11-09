<?php

namespace App\GraphQL\Mutations;

use App\Models\Company;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;
use Log;
use Hash;
use Config;
use Crypt;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CompanyMutation extends Mutation
{

   protected $attributes = [
      'name' => 'CompanyMutation'
   ];


   public function args(): array
   {
      return [
         'company' => ['type' => GraphQL::type('company_input')],
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
         'company.name.required' => 'Please enter name',
      ];
   }

   public function rules(array $args = []): array
   {
      $rules = [];
      $company = $args['company'];

      if ($company['action_type'] == "add_record" || $company['action_type'] == "update_record") {
         $rules['company.name'] = ['required'];
      }

      return $rules;
   }


   public function resolve($root, $args)
   {
      $company = $args['company'];
      $response_obj = new \stdClass();
      $company_model = new Company();

      if ($company["action_type"] == "add_record") {
         $response_obj = $company_model->addUpdateRecord(0, $company, $args['file']);
      }

      if ($company["action_type"] == "update_record") {
         $company_id = Crypt::decryptString($company['company_id']);

         $company_rec = $company_model->find($company_id);

         if ($company_rec) {
            $response_obj = $company_model->addUpdateRecord($company_id, $company, $args['file']);
         } else {
            $response_obj = new \stdClass();
            $response_obj->error = true;
            $response_obj->message = Config::get('Constants.MESSAGES')['RECORD_NOT_FOUND'];
         }
      }

      if ($company["action_type"] == "delete_record") {
         $response_obj = $company_model->deleteRecord($company);
      }


      return $response_obj;
   }
}
