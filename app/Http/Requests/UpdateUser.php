<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   UpdateUser.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
    
    public function authorize()
    {
        return TRUE;
    }

    
    public function rules()
    {
        $rules = [
            'name'      => 'required|min:3|max:100|unique:users,name,' . $this->user()->id,
            'email'     => 'required|email|max:100|unique:users,email,' . $this->user()->id,
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        foreach (config('settings.users.fields') as $field) {
            if ($field->validation) {
                $rules['fields.' . $field->id] = $field->validation;
            }
        }

        return $rules;
    }
}
