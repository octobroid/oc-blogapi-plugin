<?php namespace Octobro\BlogAPI\Transformers;

use Rainlab\Blog\Models\Post;
use Octobro\API\Classes\Transformer;

class PostTransformer extends Transformer
{
    public $availableIncludes = [
        'categories',
    ];

    public function data(Post $post)
    {
        return [
            'id'                    => (int) $post->id,
            'title'                 => $post->title,
            'slug'                  => $post->slug,
            'excerpt'               => $post->excerpt,
            'content'               => $post->content,
            'content_html'          => $post->content_html,
            'published_at'          => date($post->published_at),
            'created_at'            => date($post->created_at),
            'updated_at'            => date($post->updated_at),
            'featured_images'       => $this->images($post->featured_images),
        ];
    }

    public function includeCategories(Post $post)
    {
        return $this->collection($post->categories, new CategoryTransformer);
    }
}
