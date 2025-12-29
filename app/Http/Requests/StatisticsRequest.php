<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatisticsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'period' => [
                'nullable',
                'string',
                Rule::in(['day', 'week', 'month', 'year', 'extended']),
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'period.in' => 'Период должен быть одним из: day, week, month, year, extended',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Привести period к нижнему регистру
        if ($this->has('period')) {
            $this->merge(['period' => strtolower($this->period)]);
        }

        if (!$this->has('period')) {
            $this->merge(['period' => 'day']);
        }
    }

    /**
     * Get the validated data from the request.
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        $validated = $this->addTimestamps($validated);
        return $validated;
    }

    private function addTimestamps(array $validated): array
    {
        $timezone = $validated['timezone'];

        switch ($validated['period']) {
            case 'day':
                $validated['start_date'] = now($timezone)->startOfDay();
                $validated['end_date'] = now($timezone)->endOfDay();
                break;

            case 'week':
                $validated['start_date'] = now($timezone)->startOfWeek();
                $validated['end_date'] = now($timezone)->endOfWeek();
                break;

            case 'month':
                $validated['start_date'] = now($timezone)->startOfMonth();
                $validated['end_date'] = now($timezone)->endOfMonth();
                break;

            case 'year':
                $validated['start_date'] = now($timezone)->startOfYear();
                $validated['end_date'] = now($timezone)->endOfYear();
                break;

            default: // day
                $validated['start_date'] = now($timezone)->startOfDay();
                $validated['end_date'] = now($timezone)->endOfDay();
        }

        return $validated;
    }
}
