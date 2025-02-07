@extends('layouts.admin')
@vite(['resources/css/animal-history.css'])

@section('content')
    <h2 class="text-2xl font-bold mb-4">{{ __('Historia zmian dla zwierzaka: ') }} {{ $animal->name }}</h2>

    @if ($history->isEmpty())
        <p class="text-gray-500">{{ __('Brak historii zmian dla tego zwierzaka.') }}</p>
    @else
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">{{ __('Data zmiany') }}</th>
                <th class="border px-4 py-2">{{ __('Informacje przed zmianą') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($history as $change)
                @php
                    $data = json_decode($change->data, true); // Dekodowanie JSON
                    $colors = [];
                    if ($data['c_white']) $colors[] = 'biały';
                    if ($data['c_black']) $colors[] = 'czarny';
                    if ($data['c_brown']) $colors[] = 'brązowy';
                    if ($data['c_grey']) $colors[] = 'szary';
                    if ($data['c_golden']) $colors[] = 'złoty';
                    if ($data['c_ginger']) $colors[] = 'rudy';
                    if ($data['c_tricolor']) $colors[] = 'trójkolorowy';
                    if ($data['c_other']) $colors[] = 'inny';
                @endphp
                <tr>
                    <td class="border px-4 py-2 text-center">{{ $change->created_at }}</td>
                    <td class="border px-4 py-2">
                        <p><strong>{{ __('Imię zwierzaka:') }}</strong> {{ $data['name'] }}</p>
                        <p><strong>{{ __('Typ zwierzaka:') }}</strong> {{ $data['type'] }}</p>
                        <p><strong>{{ __('Wiek:') }}</strong> {{ $data['age'] }}</p>
                        <p><strong>{{ __('Miasto:') }}</strong> {{ $data['city'] }}</p>
                        <p><strong>{{ __('Kraj:') }}</strong> {{ $data['country'] }}</p>
                        <p><strong>{{ __('Kolory:') }}</strong> {{ implode(', ', $colors) }}</p>
                        <p><strong>{{ __('Rasa:') }}</strong>
                            @if ($data['type'] === 'cat')
                                {{ \App\Models\Cat::find($data['breed_id'])?->breed }}
                            @elseif ($data['type'] === 'dog')
                                {{ \App\Models\Dog::find($data['breed_id'])?->breed }}
                            @else
                                {{ __('Nieznana rasa') }}
                            @endif
                        </p>
                        <p><strong>{{ __('Opis:') }}</strong> {{ $data['description'] }}</p>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('admin.animals') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4 inline-block">
        {{ __('Powrót do listy zwierząt') }}
    </a>
@endsection
