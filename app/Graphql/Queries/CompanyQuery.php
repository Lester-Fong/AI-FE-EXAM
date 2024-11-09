<?php

namespace App\GraphQL\Queries;

use App\Models\Company;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class CompanyQuery extends Query
{

    protected $attributes = [
        'name' => 'The company query',
        'description' => 'Retrieves company',
    ];

    public function args(): array
    {
        return [
            'action_type' => ['type' => Type::string()],
            'company_id' => ['type' => Type::string()],
        ];
    }

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('company_type'));
    }

    public function resolve($root, $args)
    {
        $company_model = new Company();


        if ($args['action_type'] == "display_companies") {
            $response  = $company_model->displayAllCompanies();
        }


        return $response;
    }
}
