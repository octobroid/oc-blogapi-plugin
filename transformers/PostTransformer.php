<?php namespace Octobro\BlogAPI\Transformers;

use Rainlab\Blog\Models\Post;
use Octobro\API\Classes\Transformer;

class PostTransformer extends Transformer
{
    public $availableIncludes = [
        'comments',
        'categories',
        'tags'
    ];

    public function data(Post $post)
    {
        $post->setUrl('blog/detail', new \Cms\Classes\Controller);

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
            'url'                   => $post->url,
        ];
    }

    public function includeComments(Post $post)
    {
        return $this->collection($post->comments, new CommentTransformer);
    }

    public function includeCategories(Post $post)
    {
        return $this->collection($post->categories, new CategoryTransformer);
    }

    public function includeTags(Post $post)
    {
        return $this->collection($post->tags, new TagTransformer);
    }
}
