<?php

namespace OU\ZendExpressive\Module\Common\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use OU\Logger\LoggerHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\TextResponse;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @var LoggerHelper
     */
    protected $loggerHelper;

    /**
     * @param LoggerHelper $loggerHelper
     */
    public function __construct(LoggerHelper $loggerHelper)
    {
        $this->loggerHelper = $loggerHelper;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface|TextResponse
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if (!(error_reporting() & $errno)) {
                return;
            }
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        try {
            return $delegate->process($request);
        } catch (\Throwable $exception) {
        } catch (\Exception $exception) {
        }
        $this->loggerHelper->getDefaultLogger()->error($exception);
        restore_error_handler();
        return new TextResponse('An error occurred!');
    }
}
