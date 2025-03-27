<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
		RateLimiter::for('api', function (Request $request) {
			return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip())->response(function() {
				return response()->json([
					'message' => 'Too many requests. Please wait and try again later.',
					'retry_after' => 60 . ' seconds',
				], Response::HTTP_TOO_MANY_REQUESTS);
			});
		});
    }
}
