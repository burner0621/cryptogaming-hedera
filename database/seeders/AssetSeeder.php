<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AssetSeeder.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace Database\Seeders;

use App\Helpers\Api\CryptoApi;
use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    protected $api;

    public function __construct(CryptoApi $api)
    {
        $this->api = $api;
    }

    
    public function run()
    {
        Asset::active()->crypto()->update(['status' => Asset::STATUS_INACTIVE]);

        ($this->api->getList() ?: collect())->each(function ($asset) {
            if ($asset->symbol && $asset->name) {
                Asset::updateOrCreate(
                    [
                        'type'      => Asset::TYPE_CRYPTO,
                        'symbol'    => $asset->symbol
                    ],
                    [
                        'name'          => $asset->name,
                        'rank'          => $asset->rank,
                        'external_id'   => $asset->id,
                        'price'         => $asset->price,
                        'status'        => Asset::STATUS_ACTIVE
                    ]
                );
            }
        });
    }
}
