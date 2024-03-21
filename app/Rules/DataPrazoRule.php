<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class DataPrazoRule implements Rule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function passes($attribute, $value)
    {
        if ($attribute === 'date') {
            // Verifica se a data está dentro do limite de 30 dias no passado
            $selectedDate = Carbon::createFromFormat('Y-m-d', $value);
            $thirtyDaysAgo = Carbon::now()->subDays(30);
            return $selectedDate->greaterThanOrEqualTo($thirtyDaysAgo);
        } elseif ($attribute === 'term') {
            // Verifica se o prazo está entre 5 e 30 dias
            $selectedDate = Carbon::createFromFormat('Y-m-d', $value);
            $fiveDaysFromNow = Carbon::now()->addDays(5);
            $thirtyDaysFromNow = Carbon::now()->addDays(30);
            return $selectedDate->between($fiveDaysFromNow, $thirtyDaysFromNow);
        }
        
        return false;
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
