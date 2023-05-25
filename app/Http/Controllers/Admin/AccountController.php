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

namespace App\Http\Controllers\Admin;

use App\Facades\AccountTransaction;
use App\Helpers\Queries\AccountQuery;
use App\Helpers\Queries\AccountTransactionQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountCredit;
use App\Http\Requests\Admin\AccountDebit;
use App\Models\Account;
use App\Models\GenericAccountTransaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(AccountQuery $query)
    {
        $items = $query
            ->get()
            ->map(function ($account) {
                $account->user->makeVisible('referrer');
                $account->user->append('two_factor_auth_enabled', 'two_factor_auth_passed', 'is_admin', 'is_bot', 'is_active');
                return $account;
            });

        return [
            'count' => $query->getRowsCount(),
            'items' => $items
        ];
    }

    public function show(Account $account)
    {
        return [
            'account' => $account->load('user')
        ];
    }

    
    public function debit(AccountDebit $request, Account $account)
    {
        AccountTransaction::createGeneric($account, GenericAccountTransaction::TYPE_DEBIT, -$request->amount);

        return TRUE;
    }

    
    public function credit(AccountCredit $request, Account $account)
    {
        AccountTransaction::createGeneric($account, GenericAccountTransaction::TYPE_CREDIT, $request->amount);

        return TRUE;
    }

    public function transactions(AccountTransactionQuery $query, Account $account)
    {
        $items = $query
            ->addWhere(['account_id', '=', $account->id])
            ->get();

        return [
            'count' => $query->getRowsCount(),
            'items' => $items
        ];
    }
}
