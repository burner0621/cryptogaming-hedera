<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CompleteAssetPrediction.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class CompleteAssetPrediction extends FormRequest
{
    
    public function authorize()
    {
        return $this->game->is_in_progress
            && $this->game->account->id == $this->user()->account->id
            && $this->game->gameable->end_time->lte(Carbon::now());
    }

    
    public function rules()
    {
        return [];
    }
}
