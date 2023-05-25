<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CreateUsers.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\UserRepository;
use Faker\Factory as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUsers extends Command
{
    
    protected $signature = 'user:create {count=10}';

    
    protected $description = 'Create bots';

    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle()
    {
        
        $faker = Faker::create();

        for ($i=0; $i < intval($this->argument('count')); $i++) {
            UserRepository::create([
                'name'      => $faker->name,
                'email'     => $faker->safeEmail,
                'password'  => Str::random(10),
                'role'      => User::ROLE_BOT
            ]);
        }

        return 0;
    }
}
