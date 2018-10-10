<?php namespace Octobro\BlogAPI;

use System\Classes\PluginBase;
use Rainlab\Blog\Models\Post;

class Plugin extends PluginBase
{
    public $require = ['Octobro.API', 'RainLab.Blog'];

    public function boot()
    {
    	Post::extend(function($model) {
		    $model->addDynamicMethod('scopeExclude', function($query, $columns) use($model){
            $getTable = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
            return $query->select(array_diff($getTable, (array) $columns));
            });
		});
    }
}
