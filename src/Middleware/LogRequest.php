<?php

namespace Porygon\Base\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Porygon\Base\Models\RequestLog;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        return $response;
    }

    /**
     * 在响应发送到浏览器后处理任务。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        $logClass = config("porygon-base.model.request_logs", RequestLog::class);
        if ($logClass) {
            try {
                /** @var RequestLog */
                $log = $logClass::fromRequest($request);

                $log->fillResponse($response)->save();
            } catch (Exception $e) {
                Log::error("[LogRequest] Generate request log fail! " . $e->getMessage(), $e->getTrace());
            }
        } else {
            Log::info("[LogRequest] RequestLog model undefined! ");
        }
    }
}
