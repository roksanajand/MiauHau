@extends('layouts.admin')
@vite(['resources/css/animal-history.css'])

@section('content')
    <div class="centered-content">
    <h2 class="text-2xl font-bold mb-4">{{ __('Lista Zwierząt') }}</h2>

    @if (session('status'))
        <div class="bg-green-500 text-white p-2 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    @if ($animals->isEmpty())
        <p>{{ __('Brak zwierząt czekających na akcpetacje.') }}</p>
    @else
            <div class="table-container">
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">{{ __('Zdjęcie') }}</th>
                <th class="border px-4 py-2">{{ __('Imię') }}</th>
                <th class="border px-4 py-2">{{ __('Typ') }}</th>
                <th class="border px-4 py-2">{{ __('Opis') }}</th>
                <th class="border px-4 py-2">{{ __('Status') }}</th>
                <th class="border px-4 py-2">{{ __('Typ operacji') }}</th>
                <th class="border px-4 py-2">{{ __('Historia zmian') }}</th>
                <th class="border px-4 py-2">{{ __('Akcje') }}</th>

            </tr>
            </thead>
            <tbody>
            @foreach ($animals as $animal)
                <tr>
                    <td class="border px-4 py-2 text-center">
                        @if ($animal->photo)
                            <img src="{{ asset($animal->photo) }}" alt="Zdjęcie {{ $animal->name }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        @else
                            <img src="{{ asset('storage/profile_pictures/lapka.jpg') }}" alt="Domyślne zdjęcie" class="w-16 h-16 rounded-full object-cover">
                        @endif
                    </td>
                    <td class="border px-4 py-2">{{ $animal->name }}</td>
                    <td class="border px-4 py-2">
                        @if ($animal->type == 'dog')
                            Pies
                        @elseif ($animal->type == 'cat')
                            Kot
                        @else
                            {{ $animal->type }}
                        @endif
                    </td>
                    <td class="border px-4 py-2">{{ $animal->description }}</td>
                    <td class="border px-4 py-2">
                        @if ($animal->isApproved === 'waiting')
                            <span class="bg-yellow-500 text-white px-2 py-1 rounded">{{ __('Oczekuje') }}</span>
                        @elseif ($animal->isApproved === 'yes')
                            <span class="bg-green-500 text-white px-2 py-1 rounded">{{ __('Zaakceptowane') }}</span>
                        @elseif ($animal->isApproved === 'no')
                            <span class="bg-red-500 text-white px-2 py-1 rounded">{{ __('Odrzucone') }}</span>
                        @endif
                    </td>

                    <td class="border px-4 py-2">
                        @if ($animal->prev_info_count > 0)
                            <span class="bg-blue-500 text-white px-2 py-1 rounded">{{ __('Edycja') }}</span>
                        @else
                            <span class="bg-green-500 text-white px-2 py-1 rounded">{{ __('Dodanie') }}</span>
                        @endif
                    </td>


                    <td class="border px-4 py-2">
                        @if ($animal->prev_info_count > 0)
                            <a href="{{ route('admin.animals.history', $animal->id) }}" style="text-decoration: none; color: inherit;" class="bg-blue-500 text-white px-4 py-2 rounded">
                                {{ __('Zobacz zmiany') }}
                            </a>
                        @else
                            <span class="bg-green-500 text-white px-2 py-1 rounded">{{ __('Brak ') }}</span>
                        @endif
                    </td>





                    <td class="border px-4 py-2 flex space-x-2">
                        <!-- Przycisk Akceptacji -->
                        <form action="{{ route('admin.animals.approve', $animal->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit"  class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                {{ __('Zatwierdź zwierzaka') }}
                            </button>
                        </form>

                        <!-- Przycisk Odrzucenia -->
                        <form action="{{ route('admin.animals.reject', $animal->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                {{ __('Odrzuć zwierzaka') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
            </div>
    @endif
            </div>
@endsection
