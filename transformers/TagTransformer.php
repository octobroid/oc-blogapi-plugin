<?php namespace Octobro\BlogAPI\Transformers;

use Bedard\BlogTags\Models\Tag;
use Octobro\API\Classes\Transformer;

class TagTransformer extends Transformer
{
    public function data(Tag $tag)
    {
        return [
            'id'            => (int) $tag->id,
            'name'          => $tag->name
        ];
    }

}

