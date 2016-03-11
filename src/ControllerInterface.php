<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 11.03.16
 * Time: 16:25
 */

namespace Nebo15\Changelog;


interface ControllerInterface
{
    public function __construct(\Illuminate\Http\Request $request, \Illuminate\Http\Response $response);

    public function all($table, $model_id);

    public function diff($table, $model_id);

    public function rollback($table, $model_id, $changelog_id);
}