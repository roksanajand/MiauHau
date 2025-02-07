<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuń Zwierzaka') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('animals.delete', $animal->id) }}">
                        @csrf
                        @method('DELETE')

                        <p>Czy na pewno chcesz usunąć zwierzaka: <strong>{{ $animal->name }}</strong>?</p>
                        <label for="password">Podaj hasło, aby potwierdzić:</label>
                        <input type="password" name="password" id="password" class="form-control" required>

                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <button type="submit" class="btn btn-danger mt-3">Usuń</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Anuluj</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
