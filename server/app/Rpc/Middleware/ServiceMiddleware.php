<?php declare(strict_types=1);


namespace App\Rpc\Middleware;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\CLog;
use Swoft\Rpc\Server\Contract\MiddlewareInterface;
use Swoft\Rpc\Server\Contract\RequestHandlerInterface;
use Swoft\Rpc\Server\Contract\RequestInterface;
use Swoft\Rpc\Server\Contract\ResponseInterface;

/**
 * Class ServiceMiddleware
 *
 * @since 2.0
 *
 * @Bean()
 */
class ServiceMiddleware implements MiddlewareInterface
{
    /**
     * @param RequestInterface        $request
     * @param RequestHandlerInterface $requestHandler
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        CLog::info($request->getVersion(), [$request->getData()]);
        return $requestHandler->handle($request);
    }
}
