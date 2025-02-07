<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 text-center">
                Witaj, {{ explode(' ', $users->name)[0] }}!
            </div>

            <!-- Wyświetlanie wiadomości o sukcesie -->
            @if (session('animal-add-success'))
                <div style="background: yellow; color: red;">
                    {{ session('animal-add-success') }}
                </div>
            @endif
            @if (session('success_delete'))
                <div style="background: lightgreen; color: black; padding: 10px; border: 1px solid green; margin-top: 10px;">
                    {{ session('success_delete') }}
                </div>
            @endif
            @if (session('success_edit'))
                <div style="background: lightgreen; color: black; padding: 10px; border: 1px solid green; margin-top: 10px;">
                    {{ session('success_edit') }}
                </div>
            @endif

            @if ($animals->isEmpty())
                <p>{{ __('Nie masz jeszcze dodanych zwierząt.') }}</p>
                <a href="{{ route('add-animal') }}" class="btn btn-primary">
                    {{ __('Dodaj Zwierzaka') }}
                </a>
            @else
                <h3>{{ __('Twoje Zwierzęta') }}</h3>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">{{ __('Zdjęcie') }}</th>
                        <th class="border px-4 py-2">{{ __('Imię') }}</th>
                        <th class="border px-4 py-2">{{ __('Typ') }}</th>
                        <th class="border px-4 py-2">{{ __('Rasa') }}</th>
                        <th class="border px-4 py-2">{{ __('Wiek') }}</th>
                        <th class="border px-4 py-2">{{ __('Kolory') }}</th>
                        <th class="border px-4 py-2">{{ __('Opis') }}</th>
                        <th class="border px-4 py-2">{{ __('Kraj') }}</th>
                        <th class="border px-4 py-2">{{ __('Miasto') }}</th>
                        <th class="border px-4 py-2">{{ __('Polubienia') }}</th>
                        <th class="border px-4 py-2">{{ __('Status') }}</th>
                        <th class="border px-4 py-2">{{ __('Akcje') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($animals as $animal)
                        <tr>
                            <td class="border px-4 py-4 text-center">
                                @if ($animal->photo)
                                    <img src="{{ asset($animal->photo) }}" alt="Zdjęcie {{ $animal->name }}" class="w-12 h-16 rounded-full object-cover">
                                @else
                                    <img src="{{ asset('storage/profile_pictures/lapka.jpg') }}" alt="Domyślne zdjęcie" class="w-16 h-16 rounded-full object-cover">
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $animal->name }}</td>
                            <td class="border px-4 py-2">
                                @if ($animal->type === 'dog')
                                    {{ __('pies') }}
                                @elseif ($animal->type === 'cat')
                                    {{ __('kot') }}
                                @else
                                    {{ $animal->type }}
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                @if ($animal->type === 'cat')
                                    {{ \App\Models\Cat::find($animal->breed_id)?->breed }}
                                @elseif ($animal->type === 'dog')
                                    {{ \App\Models\Dog::find($animal->breed_id)?->breed }}
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $animal->age }}</td>
                            <td class="border px-4 py-2">
                                @php
                                    $colors = [];
                                    if ($animal->c_black) $colors[] = 'Czarny';
                                    if ($animal->c_white) $colors[] = 'Biały';
                                    if ($animal->c_ginger) $colors[] = 'Rudy';
                                    if ($animal->c_tricolor) $colors[] = 'Trójkolorowy';
                                    if ($animal->c_grey) $colors[] = 'Szary';
                                    if ($animal->c_brown) $colors[] = 'Brązowy';
                                    if ($animal->c_golden) $colors[] = 'Złoty';
                                    if ($animal->c_other) $colors[] = 'Inny';
                                @endphp
                                {{ implode(', ', $colors) }}
                            </td>
                            <td class="border px-4 py-2">{{ $animal->description }}</td>
                            <td class="border px-4 py-2">{{ $animal->country ?? __('Brak danych') }}</td>
                            <td class="border px-4 py-2">{{ $animal->city ?? __('Brak danych') }}</td>
                            <td class="border px-4 py-2">{{ $animal->likes_count }}</td> <!-- Wyświetlanie liczby polubień -->
                            <td class="border px-4 py-2">
                                @if ($animal->isApproved === 'waiting')
                                    <span class="px-2 py-1 text-black bg-yellow-500 rounded-full">
                                            {{ __('Czeka na akceptację') }}
                                        </span>
                                @elseif ($animal->isApproved === 'yes')
                                    <span class="px-2 py-1 text-black bg-green-500 rounded-full">
                                            {{ __('Zatwierdzony') }}
                                        </span>
                                @elseif ($animal->isApproved === 'no')
                                    <span class="px-2 py-1 text-black bg-red-500 rounded-full">
                                            {{ __('Odrzucony') }}
                                        </span>
                                @else
                                    <span class="px-2 py-1 text-black bg-gray-500 rounded-full">
                                            {{ __('Brak statusu') }}
                                        </span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <a href="{{ route('animals.edit', $animal->id) }}" class="text-blue-500 hover:underline">
                                    {{ __('Edytuj') }}
                                </a>
                                |
                                <a href="{{ route('animals.confirmDelete', $animal->id) }}" class="text-red-500 hover:underline">
                                    {{ __('Usuń') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
