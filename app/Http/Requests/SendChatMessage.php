<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   SendChatMessage.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendChatMessage extends FormRequest
{
    
    public function authorize()
    {
        return $this->room->enabled && !$this->user()->banned_from_chat;
    }

    
    public function rules()
    {
        return [
            'message' => 'required|min:1|max:' . config('settings.interface.chat.message_max_length'),
            'recipients' => 'nullable|array',
            'recipients.*' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('status', User::STATUS_ACTIVE);
                }),
            ]
        ];
    }
}
