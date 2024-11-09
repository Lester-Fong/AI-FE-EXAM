<?php

namespace App\GraphQL\Queries;

use App\Models\Article;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Log;
use Crypt;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ArticleQuery extends Query
{

    protected $attributes = [
        'name' => 'The article query',
        'description' => 'Retrieves article',
    ];

    public function args(): array
    {
        return [
            'action_type' => ['type' => Type::string()],
            'article_id' => ['type' => Type::string()],
            'slug' => ['type' => Type::string()],
            'company' => ['type' => Type::string()],
        ];
    }

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('article_type'));
    }

    public function resolve($root, $args)
    {
        $article_model = new Article();

        if ($args['action_type'] == "display_articles") {
            $response  = $article_model->displayAllArticles();
        }

        if ($args['action_type'] == "display_article_by_slug") {
            $slug = $args['slug'];
            $response = $article_model->displayArticleBySlug($slug);
        }

        return $response;
    }
}
