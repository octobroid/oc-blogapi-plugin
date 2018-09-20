<?php namespace Octobro\BlogAPI\Transformers;

use Rainlab\Blog\Models\Category;
use Octobro\API\Classes\Transformer;

class CategoryTransformer extends Transformer
{
    public $availableIncludes = [
        'products',
    ];

    public function data(Category $category)
    {
        return [
            'id'          => (int) $category->id,
            'name'        => $category->name,
            'slug'        => $category->slug,
            'description' => $category->description,
        ];
    }

    public function includeProducts(Category $category)
    {
        // return $this->collection($post->categories, new CategoryTransformer);
    }
}
