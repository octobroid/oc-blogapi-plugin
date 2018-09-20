<?php namespace Octobro\BlogAPI\APIControllers;

use Db;
use Input;
use Carbon\Carbon;
use RainLab\Blog\Models\Category;
use Octobro\API\Classes\ApiController;
use Octobro\BlogAPI\Transformers\CategoryTransformer;

class Categories extends ApiController
{

    public function index()
    {
        if (!Input::get('displayEmpty')) {
            $categories = Category::whereHas('posts', function($query) {
                $query->isPublished();
            })->getNested();
        }
        else {
            $categories = Category::getNested();
        }

        return $this->respondWithCollection($categories, new CategoryTransformer);
    }

    public function show($id)
    {
    	$category = Category::find($id);

        if (! $category) {
            return $this->errorNotFound('Category not found.');
        }

    	return $this->respondwithItem($category, new CategoryTransformer);
    }
}
