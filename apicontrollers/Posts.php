<?php namespace Octobro\BlogAPI\APIControllers;

use Input;
use Rainlab\Blog\Models\Post;
use Octobro\API\Classes\ApiController;
use Event;
use Octobro\BlogAPI\Transformers\PostTransformer;

class Posts extends ApiController
{

    public function index()
    {
        // scope Exclude, exlcude columns data for reducing data fetch.
        $posts = Post::exclude($columns = explode(',',Input::get('exclude')))->with('categories')->isPublished();

        //TODO:
        // - Filter by category
        // - Filter by author

        // Filter by search query
        if ($q = get('q'))
        {
            $posts->where('title', 'like', "%$q%")
                ->orWhere('content', 'like', "%$q%");
        }

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

        Event::fire('BlogAPI.ApiControllers.Posts.Show',[$post]);

    	return $this->respondwithItem($post, new PostTransformer);
    }
}
