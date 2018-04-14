<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Debug\Exception\getStatusCode;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

       if ($exception instanceof ModelNotFoundException) {
            // $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not exists any  with the specified identificator", 404);
        }

        if ($exception instanceof NotFoundHttpException){
             return $this->errorResponse("This specified Url Cannot be found", 404);
        }     

        if ($exception instanceof MethodNotAllowedHttpException){
             return $this->errorResponse("This specified Method Cannot be found", 405);
        }      

        if ($exception instanceof HttpException){
             return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }

        if ($exception instanceof QueryException){
             $errorCode = $exception->errorInfo[1];
             if ($errorCode == 1451){
                  return $this->errorResponse('Cannot remove this resource permanently . it is related 
                    with any other resource', 409);  
             }
             //1451
        }
        
        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }
        
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpacted Exception . Try Later', 500);
      // return parent::render($request, $exception);
    }

        /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        /*        if ($e->response) {
            return $e->response;
        }

        return $request->expectsJson()
                    ? $this->invalidJson($request, $e)
                    : $this->invalid($request, $e);*/
        $errors = $e -> validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);            

    }

}
