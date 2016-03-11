<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 11.03.16
 * Time: 12:30
 */

namespace Nebo15\Changelog;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Controller extends \Laravel\Lumen\Routing\Controller implements ControllerInterface
{
    protected $request;

    protected $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function all($table, $model_id)
    {

    }

    public function diff($table, $model_id)
    {

    }

    public function rollback($table, $model_id, $changelog_id)
    {

    }
}
