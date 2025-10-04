<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 text-white text-shadow-light">
            {{ __('Edit Catatan Lari') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            
            <div class="card glass-card">
                <div class="card-body">
                    
                    <h3 class="card-title h5 mb-3">Edit Detail Lari</h3>

                    <form method="POST" action="{{ route('runs.update', $run) }}">
                        @csrf
                        @method('patch')
                        
                        <div class="mb-3">
                            <label for="date" class="form-label text-white">{{ __('Tanggal') }}</label>
                            <input id="date" class="form-control" type="date" name="date" 
                                value="{{ old('date', $run->date) }}" required autofocus />
                            @error('date')
                                <div class="mt-2 text-warning small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="distance" class="form-label text-white">{{ __('Jarak (km)') }}</label>
                            <input id="distance" class="form-control" type="number" name="distance" 
                                value="{{ old('distance', $run->distance) }}" step="0.01" required placeholder="Contoh: 5.25"/>
                            @error('distance')
                                <div class="mt-2 text-warning small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label text-white">{{ __('Durasi (menit)') }}</label>
                            <input id="duration" class="form-control" type="number" name="duration" 
                                value="{{ old('duration', $run->duration) }}" required placeholder="Contoh: 30 (30 menit)"/>
                            @error('duration')
                                <div class="mt-2 text-warning small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label text-white">{{ __('Catatan') }}</label>
                            <textarea id="notes" class="form-control" name="notes" rows="3" placeholder="Contoh: Lari pagi di taman, cuaca cerah.">{{ old('notes', $run->notes) }}</textarea>
                            @error('notes')
                                <div class="mt-2 text-warning small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('runs.index') }}" class="btn btn-outline-light shadow">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg text-dark fw-bold shadow">
                                {{ __('Update Catatan') }}
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
