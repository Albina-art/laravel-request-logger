<?php
namespace Prettus\RequestLogger\Middlewares;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Prettus\RequestLogger\Jobs\LogTask;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Closure;
use Route;

class ResponseLoggerMiddleware
{
    use DispatchesJobs;
    public function handle($request, Closure $next)
    {
        $this->start = microtime(true);

        return $next($request);
    }

    public function terminate($request, $response)
    {
        $this->end = microtime(true);

        $this->log($request);
    }

    protected function log($request)
    {
        $path = storage_path("logs".DIRECTORY_SEPARATOR."time_work");

        if( !file_exists($path)){
            mkdir($path, 0777, true);
        }
        $s = number_format($this->end-$_SERVER['REQUEST_TIME_FLOAT'], 3);
        $s .=','. $request->fullUrl().",".(string) http_response_code()."\n";
        $file = fopen($path.DIRECTORY_SEPARATOR."response-".date('Y-m-d'), "a");
        fwrite($file, $s);
    }
}
