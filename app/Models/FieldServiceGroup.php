<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldServiceGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function exportForPdfSource():array
    {
        return [
            'name' => $this->name,
        ];
    }
}
