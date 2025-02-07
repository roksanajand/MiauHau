<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wyszukaj Zwierzęta') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Wyszukaj Zwierzęta') }}</h1>

        <!-- Formularz wyszukiwania -->
        <form action="{{ route('search') }}" method="GET" class="mb-6">
            <div class="flex flex-col gap-4">
                <!-- Filtracja według typu zwierzęcia -->
                <div>
                    <label for="type" class="block font-medium mb-1">{{ __('Typ Zwierzęcia') }}</label>
                    <select name="type" id="type" class="w-full border border-gray-300 rounded-md">
                        <option value="">{{ __('Wybierz') }}</option>
                        <option value="cat" {{ request('type') === 'cat' ? 'selected' : '' }}>{{ __('Kot') }}</option>
                        <option value="dog" {{ request('type') === 'dog' ? 'selected' : '' }}>{{ __('Pies') }}</option>
                    </select>
                </div>
                <!-- Filtracja według rasy -->
                <!-- Rasy kotów -->
                <div id="breed-cat" style="display: {{ request('type') === 'cat' ? 'block' : 'none' }};">
                    <label for="breed_id_cat">{{ __('Rasa Kota') }}</label>
                    <select name="breed_cat" id="breed_id_cat" class="w-full border border-gray-300 rounded-md">
                        <option value="">{{ __('Wybierz') }}</option>
                        @foreach ($cats as $cat)
                            <option value="{{ $cat->id }}" {{ request('type') === 'cat' && (int)request('breed_cat') === $cat->id ? 'selected' : '' }}>
                                {{ $cat->breed }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Rasy psów -->
                <div id="breed-dog" style="display: {{ request('type') === 'dog' ? 'block' : 'none' }};">
                    <label for="breed_id_dog">{{ __('Rasa Psa') }}</label>
                    <select name="breed_dog" id="breed_id_dog" class="w-full border border-gray-300 rounded-md">
                        <option value="">{{ __('Wybierz') }}</option>
                        @foreach ($dogs as $dog)
                            <option value="{{ $dog->id }}" {{ request('type') === 'dog' && (int)request('breed_dog') === $dog->id ? 'selected' : '' }}>
                                {{ $dog->breed }}
                            </option>
                        @endforeach
                    </select>
                </div>



                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const typeSelect = document.getElementById('type');
                        const breedCat = document.getElementById('breed-cat');
                        const breedDog = document.getElementById('breed-dog');

                        function toggleBreedOptions() {
                            const type = typeSelect.value;
                            breedCat.style.display = type === 'cat' ? 'block' : 'none';
                            breedDog.style.display = type === 'dog' ? 'block' : 'none';
                        }

                        typeSelect.addEventListener('change', toggleBreedOptions);
                        toggleBreedOptions();
                    });
                </script>

                <!-- Filtracja według wieku -->
                <div>
                    <label for="age" class="block font-medium mb-1">{{ __('Wiek (lata)') }}</label>
                    <input type="number" name="age" id="age" class="w-full border border-gray-300 rounded-md" value="{{ request('age') }}" min="0">
                </div>
                <!-- Filtracja według miasta -->
                <div>
                    <label for="city" class="block font-medium mb-1">{{ __('Miasto') }}</label>
                    <input
                        type="text"
                        name="city"
                        id="city"
                        class="w-full border border-gray-300 rounded-md"
                        value="{{ request('city') }}"
                        placeholder="{{ __('Wpisz miasto') }}"
                    >
                </div>
            </div>
            <!-- Filtracja według koloru -->
            <div>
                <label class="block font-medium mb-1">{{ __('Kolor') }}</label>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <input type="checkbox" name="c_black" id="c_black" value="1" {{ request('c_black') ? 'checked' : '' }}>
                        <label for="c_black">{{ __('Czarny') }}</label>
                    </div>
                    <div>
                        <input type="checkbox" name="c_white" id="c_white" value="1" {{ request('c_white') ? 'checked' : '' }}>
                        <label for="c_white">{{ __('Biały') }}</label>
                    </div>
                    <div>
                        <input type="checkbox" name="c_ginger" id="c_ginger" value="1" {{ request('c_ginger') ? 'checked' : '' }}>
                        <label for="c_ginger">{{ __('Rudy') }}</label>
                    </div>
                    <div>
                        <input type="checkbox" name="c_tricolor" id="c_tricolor" value="1" {{ request('c_tricolor') ? 'checked' : '' }}>
                        <label for="c_tricolor">{{ __('Trójkolorowy') }}</label>
                    </div>
                    <div>
                        <input type="checkbox" name="c_grey" id="c_grey" value="1" {{ request('c_grey') ? 'checked' : '' }}>
                        <label for="c_grey">{{ __('Szary') }}</label>
                    </div>
                    <div>
                        <input type="checkbox" name="c_brown" id="c_brown" value="1" {{ request('c_brown') ? 'checked' : '' }}>
                        <label for="c_brown">{{ __('Brązowy') }}</label>
                    </div>
                    <div>
                        <input type="checkbox" name="c_golden" id="c_golden" value="1" {{ request('c_golden') ? 'checked' : '' }}>
                        <label for="c_golden">{{ __('Złoty') }}</label>
                    </div>
                    <div>
                        <input type="checkbox" name="c_other" id="c_other" value="1" {{ request('c_other') ? 'checked' : '' }}>
                        <label for="c_other">{{ __('Inny') }}</label>
                    </div>
                </div>
            </div>



            <!-- Przycisk wyszukiwania -->
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded-md hover:bg-blue-600">
                    {{ __('Wyszukaj') }}
                </button>
            </div>
        </form>

        <!-- Wyniki wyszukiwania -->
        @if ($animals->isEmpty())
            <p>{{ __('Brak wyników spełniających kryteria wyszukiwania.') }}</p>
        @else
            <h2 class="text-xl font-bold mb-4">{{ __('Wyniki wyszukiwania') }}</h2>
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">{{ __('Zdjęcie') }}</th>
                    <th class="border px-4 py-2">{{ __('Imię') }}</th>
                    <th class="border px-4 py-2">{{ __('Typ') }}</th>
                    <th class="border px-4 py-2">{{ __('Wiek') }}</th>
                    <th class="border px-4 py-2">{{ __('Miasto') }}</th>
                    <th class="border px-4 py-2">{{ __('Opis') }}</th>
                    <th class="border px-4 py-2">{{ __('Polubienia') }}</th>
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
                        <td class="border px-4 py-2">{{ $animal->type === 'cat' ? __('Kot') : __('Pies') }}</td>
                        <td class="border px-4 py-2">{{ $animal->age }} {{ __('lat') }}</td>
                        <td class="border px-4 py-2">{{ $animal->city }}</td>
                        <td class="border px-4 py-2">{{ $animal->description }}</td>
                        <td class="border px-4 py-2 text-center">
                            <!-- Liczba polubień -->
                            <p>Liczba polubień: {{ $animal->likes->count() }}</p>

                            @if ($animal->likes->contains('user_id', Auth::id()))
                                <!-- Usuń polubienie -->
                                <form action="{{ route('animals.unlike', $animal->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Usuń polubienie
                                    </button>
                                </form>
                            @else
                                <!-- Polub -->
                                <form action="{{ route('animals.like', $animal->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        Lubię to
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>

</x-app-layout>
<!-- Dodanie stopki -->
@include('components.footer')
