<?php

namespace App\Policies;

use App\Models\Run;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RunPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat atau mengelola model Run.
     */
    public function view(User $user, Run $run): bool
    {
        // Pengguna hanya bisa melihat lari mereka sendiri
        return $user->id === $run->user_id;
    }
    
    /**
     * Tentukan apakah pengguna dapat memperbarui model Run.
     */
    public function update(User $user, Run $run): bool
    {
        // Pengguna hanya bisa mengedit lari yang mereka buat (milik mereka)
        return $user->id === $run->user_id;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus model Run.
     */
    public function delete(User $user, Run $run): bool
    {
        // Pengguna hanya bisa menghapus lari yang mereka buat (milik mereka)
        return $user->id === $run->user_id;
    }
    
    // Kita tidak perlu metode create/restore/forceDelete untuk saat ini
}
