<?php

// app/Models/Run.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Run extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Izinkan kolom-kolom ini diisi melalui mass assignment.
     */
    protected $fillable = [
        // Tambahkan semua kolom dari migrasi Lari:
        'user_id',
        'date',
        'distance',
        'duration',
        'notes',
    ];
    
    // ... (relasi user() dan accessor pace yang sudah kita buat)
}
