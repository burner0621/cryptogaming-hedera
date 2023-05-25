<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AccountController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\User;

use App\Helpers\Queries\AccountTransactionQuery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function transactions(Request $request, AccountTransactionQuery $query)
    {
        $account = $request->user()->account;

        $items = $query
            ->addWhere(['account_id', '=', $account->id])
            ->get();

        return [
            'count' => $query->getRowsCount(),
            'items' => $items
        ];
    }
}
