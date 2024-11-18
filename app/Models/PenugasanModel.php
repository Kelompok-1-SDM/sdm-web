<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenugasanModel extends Model
{
    use HasFactory;
    
    protected $table = 't_penugasan';
    protected $primaryKey = 'id';
    protected $fillable = ['nama', 'jadwal', 'lokasi', 'kompetensi', 'created_at', 'updated_at'];
}