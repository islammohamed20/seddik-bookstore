<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:191'],
            'customer_email' => ['nullable', 'email', 'max:191'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'billing_first_name' => ['required', 'string', 'max:191'],
            'billing_last_name' => ['required', 'string', 'max:191'],
            'billing_email' => ['nullable', 'email', 'max:191'],
            'billing_phone' => ['required', 'string', 'max:30'],
            'billing_address_line1' => ['required', 'string', 'max:255'],
            'billing_address_line2' => ['nullable', 'string', 'max:255'],
            'billing_city' => ['required', 'string', 'max:191'],
            'billing_state' => ['nullable', 'string', 'max:191'],
            'billing_postal_code' => ['nullable', 'string', 'max:30'],
            'billing_country_code' => ['nullable', 'string', 'size:2'],
            'shipping_same_as_billing' => ['nullable', 'boolean'],
            'shipping_first_name' => ['nullable', 'string', 'max:191'],
            'shipping_last_name' => ['nullable', 'string', 'max:191'],
            'shipping_email' => ['nullable', 'email', 'max:191'],
            'shipping_phone' => ['nullable', 'string', 'max:30'],
            'shipping_address_line1' => ['nullable', 'string', 'max:255'],
            'shipping_address_line2' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['nullable', 'string', 'max:191'],
            'shipping_state' => ['nullable', 'string', 'max:191'],
            'shipping_postal_code' => ['nullable', 'string', 'max:30'],
            'shipping_country_code' => ['nullable', 'string', 'size:2'],
            'payment_method' => ['required', 'in:stripe,paymob,fawry,cod,whatsapp'],
            'customer_notes' => ['nullable', 'string', 'max:2000'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ];
    }
}
