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
      $path = storage_path("framework".DIRECTORY_SEPARATOR."temp");

        if( !file_exists($path)){
            mkdir($path, 0777, true);
        }
        $s = 'Duration,'.number_format($this->end-$_SERVER['REQUEST_TIME_FLOAT'], 5);
        $s = $s.',URL,'. $request->fullUrl()."\n";
        $file = fopen($path.DIRECTORY_SEPARATOR."response-".date('Y-m-d'), "a");
        fwrite($file, $s);
    }
}