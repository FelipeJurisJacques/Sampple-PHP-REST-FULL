<?php
class HttpReport extends Report
{
    protected function callback(object $obj): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(500);
        echo json_encode($obj);
    }
}
