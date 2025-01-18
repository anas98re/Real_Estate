<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait BulkDelete
{
    /**
     * Bulk delete records.
     *
     * @return void
     */
    public function bulkDelete(array $in_ids, Model $model)
    {
        $model::whereIn('id', $in_ids)->delete();
    }
}
