<?php namespace Octobro\BlogAPI\APIControllers;

use Input;
use Rainlab\Blog\Models\Post;
use Octobro\API\Classes\ApiController;
use Octobro\BlogAPI\Transformers\PostTransformer;

class Posts extends ApiController
{

    public function index()
    {
        $posts = Post::with('categories')->isPublished();

        //TODO:
        // - Filter by category
        // - Filter by author
        // - Filter by search query

        // Order
        if ($orderBy = Input::get('orderBy') && $orderDirection = Input::get('orderDirection', 'asc')) {
            $posts->orderBy($orderBy, $orderDirection);
        } else {
            $posts->orderBy('published_at', 'desc');
        }

        // Paginate
        $paginator = $posts->paginate(Input::get('number', 15));

        return $this->respondWithPaginator($paginator, new PostTransformer);
    }

    public function show($id)
    {
    	$post = Post::find($id);

        if (! $post) {
            return $this->errorNotFound('Post not found.');
        }

    	return $this->respondwithItem($post, new PostTransformer);
    }
}
