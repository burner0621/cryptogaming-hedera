<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ApproveAffiliateCommissions.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Console\Commands;

use App\Facades\AccountTransaction;
use App\Models\AffiliateCommission;
use App\Models\GenericAccountTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApproveAffiliateCommissions extends Command
{
    
    protected $signature = 'commission:approve';

    
    protected $description = 'Approve pending affiliate commissions';

    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle()
    {
        
        $items = AffiliateCommission::select('account_id', DB::raw('SUM(amount) AS commissions_total'))
            ->pending()
            ->with('account')
            ->groupBy('account_id')
            ->havingRaw('SUM(amount) > ?', [0])
            ->get()
            ->map
            ->setAppends([]);

        
        $items->each(function ($item) {
            $item->account->commissions()->pending()->update(['status' => AffiliateCommission::STATUS_APPROVED]);
            AccountTransaction::createGeneric($item->account, GenericAccountTransaction::TYPE_AFFILIATE_COMMISSION, $item->commissions_total);
        });

        return 0;
    }
}
