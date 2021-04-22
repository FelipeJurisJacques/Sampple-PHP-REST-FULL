<?php
abstract class HttpResource
{
    public function head(HttpRequest $request, HttpResponse $response): void { }
    public function get(HttpRequest $request, HttpResponse $response): void { }
    public function post(HttpRequest $request, HttpResponse $response): void { }
    public function put(HttpRequest $request, HttpResponse $response): void { }
    public function delete(HttpRequest $request, HttpResponse $response): void { }
    public function options(HttpRequest $request, HttpResponse $response): void { }
    public function patch(HttpRequest $request, HttpResponse $response): void { }
}