<?php

namespace App\Http\Middleware\Vendor;

use App\Enum\Status;
use App\Traits\JsonResponseTrait;
use Closure;
use Illuminate\Http\Request;

class ApprovedVendorMiddleware
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
        return match ($request->user()['restaurant']['status']) {
            Status::APPROVED->value => $next($request),
            default => $this->error('Your account has not yet been approved.')
        };
    }
}
