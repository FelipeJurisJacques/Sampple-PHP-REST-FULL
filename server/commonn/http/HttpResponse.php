<?php
class HttpResponse
{
    public int $code;
    public $body;

    public function __construct()
    {
        $this->code = 404;
        $this->body = null;
    }

    public function __invoke(): void
    {
        http_response_code($this->code);
        if (!is_null($this->body) && $this->code !== 204) {
            if (is_string($this->body)) {
                header('Content-Type: text/html; charset=UTF-8');
                echo $this->body;
            } else if (is_object($this->body)) {
                if (method_exists($this->body, '__serialize')) {
                    header('Content-Type: application/json; charset=UTF-8');
                    echo json_encode($this->_serialize($this->body));
                } else if (method_exists($this->body, '__toString')) {
                    header('Content-Type: text/html; charset=UTF-8');
                    echo $this->body;
                } else {
                    header('Content-Type: application/json; charset=UTF-8');
                    echo json_encode($this->_serialize($this->body));
                }
            } else {
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode($this->_serialize($this->body));
            }
        } else {
            header('Content-Type: application/json');
        }
    }

    private function _serialize($value)
    {
        if (is_object($value)) {
            if (method_exists($value, '__serialize')) {
                return $value->__serialize();
            } else if (method_exists($value, '__toString')) {
                return $value->__toString();
            } else {
                $obj = object();
                foreach ($value as $k => $v) {
                    $obj->$k = $this->_serialize($v);
                }
                return $obj;
            }
        } else if (is_array($value)) {
            $arr = [];
            foreach ($value as $v) {
                $arr[] = $this->_serialize($v);
            }
            return $arr;
        } else {
            return $value;
        }
    }
}
