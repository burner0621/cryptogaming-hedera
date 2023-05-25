<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   DotEnvService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services;

use Illuminate\Support\Str;

class DotEnvService
{
    const ENV = '.env';
    const ENV_INSTALL = '.env.install';

    private $env;

    public function __construct()
    {
        $this->env = collect($_ENV)->except(['argc', 'argv'])->toArray();

        return $this;
    }

    public function exists()
    {
        return file_exists($this->getEnvFilePath());
    }

    
    public function create(): DotEnvService
    {
        $baseEnvFilePath = base_path() . '/' . self::ENV_INSTALL;

        if (!file_exists($baseEnvFilePath)) {
            throw new \Exception('.env.install file does not exist. Please make sure you copied all files to the server.');
        }

        if (!is_writable(base_path())) {
            throw new \Exception('Please make sure the web root folder is writable: ' . base_path());
        }

        if (!copy($baseEnvFilePath, $this->getEnvFilePath())) {
            throw new \Exception('Could not create .env file, please check permissions.');
        }

        return $this;
    }

    protected function getEnvFilePath()
    {
        return base_path() . '/' . self::ENV;
    }

    public function get()
    {
        return $this->env;
    }

    
    public function save(array $params): bool
    {
        if (!is_writable($this->getEnvFilePath())) {
            return FALSE;
        }

        
        $variableToString = function($value) {
            $type = gettype($value);

            $string = in_array($type, ['array','object'])
                ? json_encode(array_map(function($v) {
                        return is_string($v) && Str::startsWith($v, ['[','{']) ? json_decode($v) : $v;
                    }, $value), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) 
                : $value;

            
            if ($type == 'string'
                && !Str::contains($string, '"')
                && (preg_match('#^\#[a-f\d]{3,6}$#i', $string) || Str::contains($string, '#'))) {
                $string = '"' . $string . '"';
            } else {
                
                if (Str::contains($string, [' ', '#']) && !Str::contains($string, '\\"')) {
                    $string = '"' . addcslashes($string, '"') . '"';
                }
            }

            return $string;
        };

        $this->env = array_map($variableToString, array_merge($this->env, $params));

        return file_put_contents($this->getEnvFilePath(), implode("\n", array_map(function ($key, $value) {
            return $key . '=' . $value;
        }, array_keys($this->env), $this->env)));
    }
}
