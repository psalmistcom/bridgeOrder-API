<?php

namespace App\Http\Middleware\Customer;

use App\Enum\Status;
use App\Traits\JsonResponseTrait;
use Closure;
use Illuminate\Http\Request;

class VerifiedCustomerMiddleware
{
    use JsonResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        return match ($request->user()['status']) {
            Status::VERIFIED->value => $next($request),
            default => $this->error('Your email has not yet been verified.')
        };
    }
}
