<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class UniqueMultilanguageRealtyName implements Rule
{
    private $table;
    private $column;
    private $exceptId;

    public function __construct($table, $column, $exceptId = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->exceptId = $exceptId;
    }

    public function passes($attribute, $value)
    {
        foreach ($value as $lang => $name) {
            // Assuming $value is structured as ['en' => 'Name', 'fr' => 'Nom']
            $query = DB::table($this->table)
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT($this->column, '$.$lang')) = ?", [$name]);

            if ($this->exceptId) {
                $query->where('id', '<>', $this->exceptId);
            }

            if ($query->exists()) {
                return false;
            }
        }
        return true;
    }

    public function message()
    {
        return 'The :attribute is already taken in one or more languages.';
    }
}
