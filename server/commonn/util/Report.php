<?php
class Report
{
    public bool $isReport; // REPORTAR ERRO (modo desenvolvimento)

    public function __construct()
    {
        $this->isReport = false;
    }

    public function exception($except): void
    {
        $obj = object();
        $obj->error = $this->_errorCode($except->getCode());
        $obj->message = $except->getMessage();
        $obj->file = $except->getFile();
        $obj->line = $except->getLine();
        $obj->trace = [];
        foreach ($except->getTrace() as $trace) {
            $obj->trace[] = $trace;
        }
        if ($this->isReport) {
            $this->callback($obj);
        }
    }

    public function error(int $errno, string $errstr, $errfile, $errline): void
    {
        $obj = object();
        $obj->error = $this->_errorCode($errno);
        $obj->message = $errstr;
        $obj->file = $errfile;
        $obj->line = $errline;
        if ($this->isReport) {
            $this->callback($obj);
        }
    }

    private function _errorCode(int $code): string
    {
        switch ($code) {
            case 0:
                return 'ERROR';
                break;
            case E_ERROR:
                return 'ERROR';
                break;
            case E_WARNING:
                return 'WARNING:';
                break;
            default:
                return 'undefined';
                break;
        }
    }

    public function __invoke(): void
    {
        $GLOBALS['_report'] = $this;
        set_exception_handler('_exception_callback');
        set_error_handler('_error_callback');
    }

    protected function callback(object $obj): void
    {
    }
}

function _exception_callback($exception): void
{
    if (isset($GLOBALS['_report'])) {
        $report = $GLOBALS['_report'];
        $report->exception($exception);
    }
}

function _error_callback(int $errno, string $errstr, $errfile, $errline): void
{
    if (0 === error_reporting()) {
        if (isset($GLOBALS['_report'])) {
            $report = $GLOBALS['_report'];
            $report->error($errno, $errstr, $errfile, $errline);
        }
    } else {
        throw new ErrorException($errstr, $errno, $errno, $errfile, $errline);
    }
}
