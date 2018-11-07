<?php namespace Octobro\BlogAPI\Transformers;

use Rainlab\Blog\Models\Category;
use Octobro\API\Classes\Transformer;
use Octobro\BlogAPI\Transformers\PostTransformer;
use League\Fractal\ParamBag;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class CategoryTransformer extends Transformer
{
    private $validParams = ['pageParam'];

    public $availableIncludes = [
        'posts',
        'children'
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

    public function includeChildren(Category $category, ParamBag $parameters = null)
    {
        return $this->implementPaginator($category->children(), $this, $parameters);
    }

    public function includePosts(Category $category, ParamBag $params = null)
    {
        $instance = New PostTransformer;

        return $this->implementPaginator($category->posts(), $instance, $params);
    }

    public function implementPaginator($collection, $instance, $parameters)
    {

        if ($parameters === null) {
            return $this->collection($collection->paginate(), $instance); 
        }

        $usedParams = array_keys(iterator_to_array($parameters));

        if ($invalidParams = array_diff($usedParams, $this->validParams)) {
            throw new \Exception(sprintf(
                'Invalid param(s): "%s". Valid param(s): "%s"', 
                implode(',', $usedParams), 
                implode(',', $this->validParams)
            ));
        }

        list($perPage, $pageNumber) = $parameters->get('pageParam');

        $pageNumber = $pageNumber ? $pageNumber : 1;
        $perPage = $perPage ? $perPage : 10;

        $paginator = $collection->paginate($perPage,$pageNumber);

        $collections = $paginator->getCollection();

        $resource = $this->collection($collections, $instance);

        return $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

    }
}
