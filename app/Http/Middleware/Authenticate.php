<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class Authenticate extends BaseAuthenticate
{
	/**
	 * Get the path the user should be redirected to when they are not authenticated.
	 */
	protected function redirectTo(Request $request): ?string
	{
		try {
			// Use facade for logging
			Log::info('Auth redirect', [
				'url' => $request->fullUrl(),
				'expectsJson' => $request->expectsJson(),
				'referrer' => $request->headers->get('referer')
			]);

			if ($request->expectsJson()) {
				// For API requests, store referrer URL if available
				$referrer = $request->headers->get('referer');
				if ($referrer) {
					$request->session()->put('url.intended', $referrer);
					Log::info('Stored referrer URL', ['referrer' => $referrer]);
				}
			} else {
				// For web requests, store current URL
				$request->session()->put('url.intended', $request->fullUrl());
				Log::debug('Stored intended URL: ' . $request->fullUrl());
			}
			return
				str_contains($request->fullUrl(), '/admin')
				? route('admin.auth.showLoginForm')
				: route('auth.showLoginForm');
		} catch (\Exception $e) {
			Log::error('Authenticate middleware failed', ['error' => $e->getMessage()]);
			return route('auth.showLoginForm');
		}
	}
}
