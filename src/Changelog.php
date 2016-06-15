<?php

/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 10.03.16
 * Time: 17:48
 */
namespace Nebo15\Changelog;

use MongoDB\BSON\ObjectID;
use Nebo15\REST\Traits\ListableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class Changelog
 * @package Nebo15\Changelog
 */
class Changelog extends \Jenssegers\Mongodb\Eloquent\Model
{
    use ListableTrait;

    protected $primaryKey = '_id';

    protected $fillable = ['author', 'model'];

    protected $visible = [];

    protected $perPage = 20;

    protected $dateFormat = "Y-m-d\TH:i:sO";

    public static function createFromModel(Model $model, $author)
    {
        return self::create([
            'author' => $author,
            'model' => [
                '_id' => new ObjectID($model->{$model->getKeyName()}),
                'class' => get_class($model),
                'table' => $model->getTable(),
                'attributes' => $model->getAttributes()
            ]
        ]);
    }

    /**
     * @param $id
     * @param null $table
     * @param null $model_id
     * @param array $where
     * @return Changelog
     */
    public function findById($id, $table = null, $model_id = null, $where = [])
    {

        $where[$this->getKeyName()] = $id;
        if ($table) {
            $where['model.table'] = $table;
        }
        if ($model_id) {
            $where['model._id'] = $model_id;
        }

        return self::where($where)->firstOrFail();
    }

    /**
     * @param $table
     * @param $model_id
     * @param $where
     * @return Changelog
     */
    public function findFirst($table, $model_id, $where = [])
    {
        $where['model._id'] = $model_id;
        $where['model.table'] = $table;
        return self::where($where)->orderBy('created_at', 'DESC')->firstOrFail();
    }

    /**
     * @param $table
     * @param $model_id
     * @param null $perPage
     * @param array $where
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws ModelNotFoundException
     */
    public function findAll($table, $model_id, $perPage = null, $where = [])
    {

        $where['model.table'] = $table;
        if ($model_id) {
            $where['model._id'] = $model_id;
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
        $paginator = self::where($where)
            ->orderBy('model.table')
            ->orderBy(self::CREATED_AT, 'DESC')
            ->paginate($perPage, ['author', 'model._id', 'model.table', self::CREATED_AT, self::UPDATED_AT]);
        if ($paginator->count() == 0) {
            throw (new ModelNotFoundException)->setModel(get_class($this));
        }

        return $paginator;
    }

    public function diff($table, $model_id, $compareWithId, $originalId = null, $where = [])
    {
        $original = $originalId ? $this->findById($originalId, $table, $model_id, $where) : $this->findFirst($table, $model_id, $where);

        return [
            'original' => $original->toArray(),
            'compare_with' => $this->findById($compareWithId, $table, $model_id, $where)->toArray(),
        ];
    }

    public function rollback($table, $model_id, $changelogId, $where = [])
    {
        $changelog = $this->findById($changelogId, $table, $model_id, $where);


        $result = $changelog->getModelClassById(strval($changelog->model['_id']))
            ->setRawAttributes($changelog->model['attributes'])
            ->save();

        return ['reverted' => $result];
    }

    /**
     * @param $id
     * @return Model
     * @throws Exception
     */
    public function getModelClassById($id)
    {
        $modelClass = $this->getModelClass();

        return call_user_func_array([get_class($modelClass), 'where'], [$modelClass->getKeyName(), $id])->firstOrFail();
    }

    /**
     * @return Model
     * @throws Exception
     */
    public function getModelClass()
    {
        if (!class_exists($class = $this->model['class'])) {
            throw new Exception("Class '$class' does not exists");
        }

        return new $class;
    }

    public function toArray()
    {
        $array = parent::toArray();
        array_walk_recursive($array, function (&$item, $key) {
            if ($key == $this->getKeyName()) {
                $item = strval($item);
            }
        });

        return $array;
    }
}
