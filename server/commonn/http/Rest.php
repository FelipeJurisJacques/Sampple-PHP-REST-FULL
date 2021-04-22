<?php
class Rest
{
    private HttpRequest $_request;
    private HttpReport $_report;
    private ?HttpResponse $_response;
    private string $_service;
    private array $_path;
    private array $_paths;
    private string $_local;

    public function __construct(string $dir)
    {
        $report = new HttpReport();
        $report->isReport = true;
        $report();
        $this->_report = $report;
        $this->_request = new HttpRequest();
        $this->_response = null;
        $this->_service = basename($dir);
        $arr = explode('/', $this->_request->uri->path);
        $b = false;
        $this->_path = [];
        $this->_local = $this->_request->uri->origin;
        for ($i = 0; $i < count($arr); $i++) {
            $v = $arr[$i];
            if (!$b) {
                $b = $v === $this->_service;
                $this->_local .= "/$v";
            } else {
                $this->_path[] = $v;
            }
        }
        if (Address::$isDebug) {
            $this->_paths = [];
        }
    }

    public function filter(...$options): void
    {
        if (is_null($this->_response)) {
            $l = count($options);
            if ($l < 2) {
                throw new Exception('required a origin of resource class and url to filter');
            } else {
                for ($i = 1; $i < $l; $i++) {
                    if ($this->_filter($options[$i])) {
                        $controller = new $options[0]();
                        $this->_response = new HttpResponse();
                        if ($this->_request->method === 'GET') {
                            $controller->get($this->_request, $this->_response);
                        } else if ($this->_request->method === 'HEAD') {
                            $controller->head($this->_request, $this->_response);
                        } else if ($this->_request->method === 'POST') {
                            $controller->post($this->_request, $this->_response);
                        } else if ($this->_request->method === 'PUT') {
                            $controller->put($this->_request, $this->_response);
                        } else if ($this->_request->method === 'DELETE') {
                            $controller->delete($this->_request, $this->_response);
                        } else if ($this->_request->method === 'OPTIONS') {
                            $controller->options($this->_request, $this->_response);
                        } else if ($this->_request->method === 'PATCH') {
                            $controller->patch($this->_request, $this->_response);
                        } else {
                            $this->_response->code = 501;
                        }
                        break;
                    }
                }
            }
        }
    }

    private function _filter(string $path): bool
    {
        if (Address::$isDebug) {
            $this->_paths[] = $this->_local . '/' . $path;
        }
        $this->_request->args = [];
        $arr = explode('/', $path);
        $compare = null;
        $l1 = count($this->_path);
        $l2 = count($arr);
        $isEntered = $l1 >= $l2;
        if ($isEntered) {
            for ($i = 0; $i < $l1; $i++) {
                $part = $this->_path[$i];
                if ($i < $l2) {
                    $compare = $arr[$i];
                } else if (!($compare === '<n>' || $compare === '<m>')) {
                    $isEntered = false;
                    break;
                }
                if ($compare === '<i>' || $compare === '<n>') {
                    if (is_numeric($part)) {
                        $this->_request->args[] = intval($part);
                    } else {
                        $isEntered = false;
                        break;
                    }
                } else if ($compare === '<d>') {
                    if (is_numeric($part)) {
                        $this->_request->args[] = floatval($part);
                    } else {
                        $isEntered = false;
                        break;
                    }
                } else if ($compare === '<s>' || $compare === '<m>') {
                    if (!empty($part)) {
                        $this->_request->args[] = $part;
                    }
                } else if ($compare !== $part) {
                    $isEntered = false;
                    break;
                }
            }
        }
        return $isEntered;
    }

    public function __invoke(): void
    {
        if (is_null($this->_response)) {
            $this->_response = new HttpResponse();
            $this->_response->code = 404;
            if (Address::$isDebug) {
                $this->_response->body = $this->_paths;
            }
        }
        $response = $this->_response;
        $response();
    }
}
