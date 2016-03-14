<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 11.03.16
 * Time: 16:25
 */

namespace Nebo15\Changelog;

use Nebo15\REST\Response;
use \Illuminate\Http\Request;

interface ControllerInterface
{
    public function __construct(Request $request, Response $response, Changelog $changelogModel);

    public function all($table);

    public function allWithId($table, $model_id);

    public function diff($table, $model_id);

    public function rollback($table, $model_id, $changelog_id);
}
