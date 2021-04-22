<?php
class Main
{
    private string $_dir;
    private array $_scripts;

    public function __construct(string $dir)
    {
        $this->_dir = $dir;
        $this->_scripts = [];
        $this->_import(__DIR__ . DIRECTORY_SEPARATOR . 'commonn');
        $this->_import($this->_dir);
        $GLOBALS["_scripts"] = $this->_scripts;
        spl_autoload_register('_script_autoload');
        $this->_htaccess();
        require_once __DIR__ . '/Address.php';
        // $arr = explode(DIRECTORY_SEPARATOR, (pathinfo($dir)['basename']));
        // $this->service = $arr[count($arr) - 1];
        // $arr = explode('/', $this->request->uri->path);
        // $use = false;
        // for ($i = 0; $i < count($arr); $i++) {
        //     $path = $arr[$i];
        //     if ($path === $this->service) {
        //         $use = true;
        //     } else if ($use) {
        //         $this->_path[] = $path;
        //     }
        // }
    }

    private function _import(string $root): void
    {
        $dir = dir($root);
        while ($p = $dir->read()) {
            if ($p !== '.' && $p !== '..') {
                $patchh = $root . DIRECTORY_SEPARATOR . $p;
                if (is_dir($patchh)) {
                    $this->_import($patchh);
                } else if (file_exists($patchh)) {
                    $info = pathinfo($patchh);
                    if ($info['extension'] === 'php' && $info['filename'] !== 'index') {
                        $this->_scripts[] = $info;
                    }
                }
            }
        }
        $dir->close();
    }

    private function _htaccess(): void
    {
        $patch = $this->_dir . DIRECTORY_SEPARATOR . '.htaccess';
        if (!file_exists($patch)) {
            $content = "RewriteEngine on\r\n";
            $content .= "RewriteCond %{REQUEST_FILENAME} !-f\r\n";
            $content .= "RewriteCond %{REQUEST_FILENAME} !-d\r\n";
            $content .= "RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]\r\n";
            $file = fopen($patch, 'w');
            fwrite($file, $content);
            fclose($file);
        }
    }
}

function _script_autoload(string $class): void
{
    $scripts = $GLOBALS["_scripts"];
    $l = count($scripts);
    for ($i = 0; $i < $l; $i++) {
        $patch = $scripts[$i];
        if ($patch['filename'] === $class) {
            require_once $patch['dirname'] . DIRECTORY_SEPARATOR . $patch['basename'];
            break;
        }
    }
}

function object(): object
{
    return (object) array();
}
