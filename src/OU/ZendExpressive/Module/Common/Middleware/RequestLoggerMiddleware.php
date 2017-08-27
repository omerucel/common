<?php

namespace OU\ZendExpressive\Module\Common\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use OU\ClientIPAddressFinder;
use OU\Logger\LoggerHelper;
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
     * @param DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
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
        return $delegate->process($request);
    }
}
