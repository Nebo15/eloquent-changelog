<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 11.03.16
 * Time: 12:30
 */

namespace Nebo15\Changelog;

use Nebo15\REST\Response;
use Illuminate\Http\Request;

class Controller extends \Laravel\Lumen\Routing\Controller implements ControllerInterface
{
    protected $request;

    protected $response;

    protected $changelogModel;

    public function __construct(Request $request, Response $response, Changelog $changelogModel)
    {
        $this->request = $request;
        $this->response = $response;
        $this->changelogModel = $changelogModel;
    }

    public function all($table)
    {
        return $this->response->jsonPaginator(
            $this->changelogModel->findAll($table, null, $this->request->get('size'))
        );
    }

    public function allWithId($table, $model_id)
    {
        return $this->response->jsonPaginator(
            $this->changelogModel->findAll($table, $model_id, $this->request->get('size'))
        );
    }

    public function diff($table, $model_id)
    {
        $this->validate($this->request, [
            'compare_with' => 'required',
            'original' => 'sometimes|required',
        ]);

        return $this->response->json(
            $this->changelogModel->diff(
                $table,
                $model_id,
                $this->request->input('compare_with'),
                $this->request->input('original')
            )
        );
    }

    public function rollback($table, $model_id, $changelog_id)
    {
        return $this->response->json(
            $this->changelogModel->rollback($table, $model_id, $changelog_id)
        );
    }
}
