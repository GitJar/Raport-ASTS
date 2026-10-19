<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['parent_id', 'code', 'name', 'order_no'];

    // Hubungan ke Sub-Mapel (Parent-Child PAI & Mulok)
    public function children()
    {
        return $this->hasMany(Subject::class, 'parent_id')->orderBy('order_no');
    }

    public function parent()
    {
        return $this->belongsTo(Subject::class, 'parent_id');
    }
}