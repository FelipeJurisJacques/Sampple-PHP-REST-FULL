<?php
class NotasResource extends HttpResource
{
    public function get(HttpRequest $request, HttpResponse $response): void
    {
        $dao = new NotaRepository();
        if (count($request->args) === 0) {
            $response->code = 200;
            $response->body = $dao->list();
        } else {
            $model = $dao->select(intval($request->args[0]));
            if (is_null($model)) {
                $response->code = 404;
            } else {
                $response->code = 200;
                $response->body = $model;
            }
        }
    }

    public function post(HttpRequest $request, HttpResponse $response): void
    {
        if (is_null($request->json)) {
            $response->code = 400;
        } else if (is_object($request->json)) {
            $response->code = 400;
        } else if (!isset($request->json->titulo) || !isset($request->json->mensagem)) {
            $response->code = 400;
        } else {
            $model = new NotaEntity();
            $model->titulo = $request->json->titulo;
            $model->mensagem = $request->json->mensagem;
            $dao = new NotaRepository();
            $id = $dao->insert($model);
            if (is_null($id)) {
                $response->code = 500;
            } else {
                $response->code = 201;
                $response->body = $id;
            }
        }
    }

    public function put(HttpRequest $request, HttpResponse $response): void
    {
        if (is_null($request->json) || count($request->args) === 0) {
            $response->code = 400;
        } else if (is_object($request->json)) {
            $response->code = 400;
        } else if (!isset($request->json->titulo) || !isset($request->json->mensagem)) {
            $response->code = 400;
        } else {
            $model = new NotaEntity();
            $model->id = intval($request->args[0]);
            $model->titulo = $request->json->titulo;
            $model->mensagem = $request->json->mensagem;
            $dao = new NotaRepository();
            if ($dao->update($model)) {
                $response->code = 500;
            } else {
                $response->code = 202;
            }
        }
    }

    public function delete(HttpRequest $request, HttpResponse $response): void
    {
        if (count($request->args) === 0) {
            $response->code = 400;
        } else {
            $dao = new NotaRepository();
            if ($dao->delete(intval($request->args[0]))) {
                $response->code = 500;
            } else {
                $response->code = 202;
            }
        }
    }
}
