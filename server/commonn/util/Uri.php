<?php
class Uri
{
    public int $port; // porta
    public string $host; // hospedagem
    public string $scheme; // sigla do protocolo
    public string $href; // link completo
    public string $origin; // origem (scheme + host + port)
    public string $path; // restante

    public function __construct(string $url)
    {
        $this->host = '';
        $this->port = 443;
        $this->scheme = 'https';
        $uri = [];
        $arr = explode('://', $url);
        if (count($arr) === 2) {
            $this->scheme = $arr[0];
            $arr = explode('/', $arr[1]);
            $l = count($arr);
            for ($i = 0; $i < $l; $i++) {
                if ($i === 0) {
                    $a = explode(':', $arr[0]);
                    if (count($a) === 2) {
                        $this->host = $a[0];
                        $this->port = intval($a[1]);
                    } else {
                        $this->host = $arr[0];
                        if ($this->scheme === 'http') {
                            $this->port = 80;
                        }
                    }
                } else {
                    $uri[] = $arr[$i];
                }
            }
        }
        $origin = $this->scheme . '://' . $this->host;
        if ($this->port !== 80 && $this->port !== 443) {
            $origin .= ':' . $this->port;
        }
        $this->origin = $origin;
        $this->path = implode('/', $uri);
        $this->href = $this->origin . '/' . $this->path;
    }

    public function __toString(): string
    {
        return $this->href;
    }
}
