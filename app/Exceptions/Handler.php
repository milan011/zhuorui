<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {   
        /*if($e instanceof ApiException) {
            $result = [
                "msg"    => "",
                "data"   => $e->getMessage(),
                "status" => 0
            ];
            // dd($e->code);
            // dd($e->getMessage());

            return response()->json($result);
        }
        if (config('app.debug')) {
            //若非调试环境，则转自定义异常显示，线上环境提示比较友好，线下利于调试
            if($e instanceof ModelNotFoundException) {

                // dd(($e));
                // return response()->view('admin.errors.notfound'); //自定义页面
                return '大哥，没找着啊！';
            }
        }*/

        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();
            p('哥我异常了!');
            dd($code);
            if (view()->exists('errors.' . $code)) {
                $message  = $e->getMessage();
                return response()->view('errors.' . $e->getStatusCode(), ['message'=>$message], $e->getStatusCode());
            }
        }       
        
        if(($e instanceof \Illuminate\Database\QueryException)){
            p('ma ge bi');
            dd($e);
        }

        if(($e instanceof ExcelException)){ //Excel导入异常
            /*p('wo kan xing');
            dd($e);*/
            return redirect()->route('infoDianxin.index')->withErrors($e->getMessage());
        }

        /*p('哥我you异常了!');
        dd(get_parent_class($e));*/
        
        if(strstr($e->getFile(), 'phpexcel')){
            // p($e->getFile());
            // dd($e);
            return redirect()->route('infoDianxin.error');
            
        }

        /*p($e->getCode());
        p($e->getMessage());
        dd($e);*/
        return parent::render($request, $e);
    }
}
