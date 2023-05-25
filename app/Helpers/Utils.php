<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Utils.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use ReflectionClass;

class Utils
{
    
    public static function makeChildClass(string $abstractClass, string $name)
    {
        $r = new ReflectionClass($abstractClass);

        
        $class = (string) Str::of($r->getNamespaceName())->append(Str::of($name)->ucfirst()->prepend('\\')->append(class_basename($abstractClass)));

        return app()->make($class);
    }

    
    public static function getDateRange(?string $period): array
    {
        if ($period == 'day') {
            return [Carbon::now()->startOfDay(), Carbon::now()];
        } elseif ($period == 'prev_day') {
            return [Carbon::now()->subDay()->startOfDay(), Carbon::now()->startOfDay()->subSecond()];
        } elseif ($period == 'last24') {
            return [Carbon::now()->subHours(24), Carbon::now()];
        } elseif ($period == 'prev_week') {
            return [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->startOfWeek()->subSecond()];
        } elseif ($period == 'month') {
            return [Carbon::now()->startOfMonth(), Carbon::now()];
        } elseif ($period == 'prev_month') {
            return [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->startOfMonth()->subSecond()];
        } elseif ($period == 'year') {
            return [Carbon::now()->startOfYear(), Carbon::now()];
        } elseif ($period == 'prev_year') {
            return [Carbon::now()->subYear()->startOfYear(), Carbon::now()->startOfYear()->subSecond()];
        
        } else {
            return [Carbon::now()->startOfWeek(), Carbon::now()];
        }
    }

    public static function assert($class, $hash, $cb)
    {
        try {
            return Cache::remember('hash_' . class_basename($class), 300, function () use ($class, $hash) {
                return sha1(preg_replace('#\s+#', '', file_get_contents((new ReflectionClass($class))->getFileName()))) == $hash;
            }) ?: $cb();
        } catch (\Throwable $e) {
            
        }
    }

    
    public static function getConstantNameByValue(string $class, object $instance, $value): string
    {
        $r = new ReflectionClass($class);

        return collect($r->getConstants())
            ->filter(function ($constantValue, $constantName) use ($value) {
                return $value === $constantValue;
            })
            ->keys()
            ->first();
    }

    
    public static function getPathToPhp(): string
    {
        return PHP_BINDIR . DIRECTORY_SEPARATOR . 'php';
    }

    
    public static function getCronJobCommand(): string
    {
        return '* * * * * ' . self::getPathToPhp() . ' -d register_argc_argv=On ' . base_path() . DIRECTORY_SEPARATOR . 'artisan schedule:run';
    }

    public static function generateRandomString(int $numberOfBytes): string
    {
        return bin2hex(random_bytes($numberOfBytes));
    }

    
    public static function bcdechex($decimal): string
    {
        $last = bcmod($decimal, 16);
        $remain = bcdiv(bcsub($decimal, $last), 16);

        if ($remain == 0) {
            return dechex($last);
        } else {
            return self::bcdechex($remain) . dechex($last);
        }
    }

    
    public static function bchexdec($hex): string
    {
        if (strlen($hex) == 1) {
            return ctype_xdigit($hex) ? (string) hexdec($hex) : '0';
        } else {
            $remain = substr($hex, 0, -1);
            $last = substr($hex, -1);
            return bcadd(bcmul(16, self::bchexdec($remain)), ctype_xdigit($last) ? hexdec($last) : 0);
        }
    }

    
    public static function fromUnits($value, int $decimals, int $scale): string
    {
        return bcdiv($value, bcpow(10, $decimals), $scale);
    }

    
    public static function toUnits($value, int $decimals): string
    {
        return bcmul(sprintf("%.{$decimals}f", $value), bcpow(10, $decimals));
    }
}
