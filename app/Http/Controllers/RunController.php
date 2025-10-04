<?php

namespace App\Http\Controllers;

use App\Models\Run;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class RunController extends Controller
{

    use AuthorizesRequests;
    /**
     * Display a listing of the resource (Dashboard dan Daftar Lari).
     */

    public function index(Request $request) // <-- PASTIKAN ADA (Request $request)
    {
        $user_runs_query = auth()->user()->runs();
        
        // Dapatkan parameter filter dari URL
        $selected_month = $request->query('month'); // <-- INI PENTING
        $selected_year = $request->query('year');   // <-- INI PENTING

        // 1. Terapkan Filter
        if ($selected_month && $selected_year) {
            $user_runs_query = $user_runs_query->whereYear('date', $selected_year)
                                               ->whereMonth('date', $selected_month);
        } elseif ($selected_year) {
            $user_runs_query = $user_runs_query->whereYear('date', $selected_year);
        }
        
        // Klona query untuk statistik
        $runs_stats_query = clone $user_runs_query;
        $runs = $user_runs_query->latest('date')->get();

        // 2. Hitung Statistik Ringkasan
        $total_distance = $runs_stats_query->sum('distance'); 
        $total_duration = $runs_stats_query->sum('duration');
        $run_count = $runs_stats_query->count();

        // 3. Hitung Rata-rata Kecepatan (Pace Global)
        $average_pace = ($run_count > 0 && $total_distance > 0)
            ? (($total_duration / 60) / $total_distance)
            : 0;
            
        $avg_minutes = floor($average_pace);
        $avg_seconds = round(($average_pace - $avg_minutes) * 60);
        $formatted_avg_pace = sprintf('%d:%02d', $avg_minutes, $avg_seconds);

        // 4. Siapkan Data untuk Grafik
        $chart_data = $user_runs_query
            ->orderBy('date', 'asc')
            ->take(7) 
            ->get();

        $chart_labels = $chart_data->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m'));
        $chart_distances = $chart_data->pluck('distance');
        
        $chart_labels_json = $chart_labels->toJson();
        $chart_distances_json = $chart_distances->toJson();

        // 5. Kirim semua variabel ke view (INI SOLUSI UTAMA)
        return view('runs.index', compact(
            'runs', 
            'total_distance', 
            'run_count',
            'formatted_avg_pace',
            'chart_labels_json',     
            'chart_distances_json',
            'selected_month',        // <--- KIRIM VARIABEL INI
            'selected_year'          // <--- KIRIM VARIABEL INI
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('runs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
	
        // 1. Validasi data
        $validated = $request->validate([
            'date' => ['required', 'date', 'before_or_equal:today'],
            'distance' => ['required', 'numeric', 'min:0.01'],
            'duration' => ['required', 'integer', 'min:1'], // Durasi dalam detik
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // 2. Simpan data (menggunakan relasi user)
        $request->user()->runs()->create($validated); 

        // 3. Redirect
        return redirect()->route('runs.index')->with('status', 'Catatan lari berhasil disimpan!');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
     public function edit(Run $run)
    {
        // Panggilan $this->authorize('update', $run) harus berada di sini
        $this->authorize('update', $run); 
        
        return view('runs.edit', compact('run'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Run $run)
    {
        // Otorisasi: Hanya pemilik yang boleh mengupdate
        $this->authorize('update', $run);

        $validated = $request->validate([
            'date' => ['required', 'date', 'before_or_equal:today'],
            'distance' => ['required', 'numeric', 'min:0.01'],
            'duration' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

	$validated['pace'] = $this->calculatePace($validated['distance'], $validated['duration']);

        $run->update($validated);

        return redirect()->route('runs.index')->with('status', 'Catatan lari berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Run $run)
    {
        // Otorisasi: Hanya pemilik yang boleh menghapus
        $this->authorize('delete', $run);

        $run->delete();

        return redirect()->route('runs.index')->with('status', 'Catatan lari berhasil dihapus.');
    }

// ** Tambahkan method calculatePace() di sini **
    protected function calculatePace(float $distance, int $duration): string
    {
    if ($distance <= 0) {
        return '0:00';
    }

    // Pace = Total Durasi (menit) / Total Jarak (km)
    $pace_raw = $duration / $distance;

    // Konversi pace mentah (misalnya 5.50) menjadi format m:ss
    $pace_minutes = floor($pace_raw);
    $pace_seconds = round(($pace_raw - $pace_minutes) * 60);

    // Pastikan detik selalu dua digit (misalnya 5:05)
    return sprintf('%d:%02d', $pace_minutes, $pace_seconds);
    }


}
