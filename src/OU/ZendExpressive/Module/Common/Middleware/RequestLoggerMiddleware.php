<?php

namespace OU\ZendExpressive\Module\Common\Middleware;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use OU\ClientIPAddressFinder;
use OU\Logger\LoggerHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RequestLoggerMiddleware implements MiddlewareInterface
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
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $message = 'New ' . $request->getMethod() . ' request for ' . strval($request->getUri());
        $this->loggerHelper->getDefaultLogger()->info(
            $message,
            [
                'post_params' => $request->getParsedBody(),
                'client_ip' => ClientIPAddressFinder::find($request->getServerParams()),
                'server_ip' => $request->getServerParams()['SERVER_ADDR'] ?? ''
            ]
        );
        return $handler->handle($request);
    }
}
