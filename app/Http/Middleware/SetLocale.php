<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   SetLocale.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Closure;

class SetLocale
{
    
    public function handle($request, Closure $next)
    {
        if ($locale = $this->getLocale($request)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    
    protected function getLocale($request)
    {
        $locales = config('app.locales');

        $locale = $request->cookie('locale') ?: (
            config('app.detect_browser_locale')
                ? substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2)
                : config('app.locale')
        );

        if (array_key_exists($locale, $locales)) {
            return $locale;
        }

        return NULL;
    }
}
