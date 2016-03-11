<?php

/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 10.03.16
 * Time: 17:48
 */
namespace Nebo15\Changelog;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentChangelog
 * @package Nebo15\Changelog
 */
class Changelog extends \Jenssegers\Mongodb\Model
{
    protected $fillable = ['author', 'model'];

    public static function find($table, $model_id)
    {
        return self::where([
            'model._id' => $model_id,
            'model.table' => $table,
        ])->firstOrFail();
    }

    public static function createFromModel(Model $model, $author)
    {
        return self::create([
            'author' => $author,
            'model' => [
                '_id' => $model->_id,
                'table' => $model->getTable(),
                'attributes' => $model->getAttributes()
            ]
        ]);
    }
}
