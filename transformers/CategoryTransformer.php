<?php namespace Octobro\BlogAPI\Transformers;

use Rainlab\Blog\Models\Category;
use Octobro\API\Classes\Transformer;
use Octobro\BlogAPI\Transformers\PostTransformer;
use League\Fractal\ParamBag;
class CategoryTransformer extends Transformer
{
    private $validParams = ['pageParam'];

    public $availableIncludes = [
        'posts',
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

    public function includePosts(Category $category, ParamBag $params = null)
    {
        if ($params === null) {
           return $this->collection($category->posts, new PostTransformer); 
        }
        
        $usedParams = array_keys(iterator_to_array($params));

        if ($invalidParams = array_diff($usedParams, $this->validParams)) {
            throw new \Exception(sprintf(
                'Invalid param(s): "%s". Valid param(s): "%s"', 
                implode(',', $usedParams), 
                implode(',', $this->validParams)
            ));
        }

        list($perPage, $pageNumber) = $params->get('pageParam');

        $pageNumber = $pageNumber ? $pageNumber : 1;
        $perPage = $perPage ? $perPage : 10;
        $posts = $category->posts()->paginate($perPage,$pageNumber);

        return $this->collection($posts, new PostTransformer);
    }
}
