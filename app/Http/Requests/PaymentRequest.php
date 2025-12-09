<?php
// app/Http/Requests/PaymentRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $paymentModes = implode(',', array_keys(config('fedapay.modes')));
        
        return [
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => [
                'required',
                'string',
                'regex:/^(?:\+229|229|0)[0-9]{8}$/',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:100',
                'max:1000000',
            ],
            'payment_mode' => "required|in:{$paymentModes},automatic,direct",
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => 'Le prénom est obligatoire',
            'lastname.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'phone.required' => 'Le numéro de téléphone est obligatoire',
            'phone.regex' => 'Le format du téléphone est invalide. Ex: +229XXXXXXXX, 0XXXXXXXX',
            'amount.required' => 'Le montant est obligatoire',
            'amount.min' => 'Le montant minimum est de 100 FCFA',
            'amount.max' => 'Le montant maximum est de 1,000,000 FCFA',
            'payment_mode.required' => 'Le mode de paiement est obligatoire',
            'payment_mode.in' => 'Le mode de paiement sélectionné est invalide',
        ];
    }

    public function attributes()
    {
        return [
            'firstname' => 'prénom',
            'lastname' => 'nom',
            'email' => 'email',
            'phone' => 'téléphone',
            'amount' => 'montant',
            'payment_mode' => 'mode de paiement',
        ];
    }
}