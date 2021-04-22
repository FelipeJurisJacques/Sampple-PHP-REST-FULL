<?php
class HttpRequest
{
    public string $method;
    public Uri $uri;
    public array $args;
    public ?string $body;
    public $json;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->uri = new Uri($uri);
        $this->args = [];
        $this->body = file_get_contents('php://input');
        if (!is_null($this->body) && !empty($this->body)) {
            $this->json = json_decode($this->body, true);
        } else {
            $this->json = null;
        }
    }
}
