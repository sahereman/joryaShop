<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     * @var array
     */
    protected $dontReport = [
        InvalidRequestException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /*protected function unauthenticated($request, AuthenticationException $exception)
    {
        if (\Browser::isMobile()) {
            return $request->expectsJson()
                ? response()->json(['message' => $exception->getMessage()], 401)
                : redirect()->guest(route('mobile.login.show'));
        } else {
            $parsedUrl = parse_url(redirect()->back()->getTargetUrl());
            $urlArray = [];
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $urlArray);
            }
            $urlArray['action'] = 'login';
            $redirectUrl = '';
            $redirectUrl .= isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : 'http://';
            $redirectUrl .= $parsedUrl['host'];
            $redirectUrl .= isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
            $redirectUrl .= isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
            $redirectUrl .= '?' . http_build_query($urlArray);
            $redirectUrl .= isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';
            return $request->expectsJson()
                ? response()->json([
                    'action' => 'login',
                    'message' => $exception->getMessage(),
                ], 401)
                : redirect()->intended($redirectUrl);
        }
    }*/
}
