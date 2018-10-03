<?php namespace Octobro\BlogAPI\Transformers;

use Merdeka\Blog\Models\Comment;
use Octobro\API\Classes\Transformer;

class CommentTransformer extends Transformer
{
    public function data(Comment $comment)
    {
        return [
            'id'            => (int) $comment->id,
            'name'          => $comment->name,
            'comment'       => $comment->comment
        ];
    }

}

