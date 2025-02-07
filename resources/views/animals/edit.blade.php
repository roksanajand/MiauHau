<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edytuj Zwierzaka') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('animals.update', $animal->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Imię -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">{{ __('Imię') }}</label>
                            <input type="text" name="name" id="name" value="{{ $animal->name }}" class="form-control" required>
                        </div>

                        <!-- Typ zwierzaka -->
                        <div class="mb-4">
                            <label for="type" class="block text-gray-700">{{ __('Typ') }}</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="cat" {{ $animal->type === 'cat' ? 'selected' : '' }}>{{ __('Kot') }}</option>
                                <option value="dog" {{ $animal->type === 'dog' ? 'selected' : '' }}>{{ __('Pies') }}</option>
                            </select>
                        </div>

                        <!-- Rasa kotów -->
                        <div id="breed-cat" style="display: none;">
                            <label for="breed_id_cat">{{ __('Rasa Kota') }}</label>
                            <select name="breed_id" id="breed_id_cat" class="form-control">
                                <option value="">{{ __('Wybierz') }}</option>
                                @foreach ($cats as $cat)
                                    <option value="{{ $cat->id }}" {{ $animal->breed_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->breed }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rasa psów -->
                        <div id="breed-dog" style="display: none;">
                            <label for="breed_id_dog">{{ __('Rasa Psa') }}</label>
                            <select name="breed_id" id="breed_id_dog" class="form-control">
                                <option value="">{{ __('Wybierz') }}</option>
                                @foreach ($dogs as $dog)
                                    <option value="{{ $dog->id }}" {{ $animal->breed_id == $dog->id ? 'selected' : '' }}>
                                        {{ $dog->breed }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Wiek -->
                        <div class="mb-4">
                            <label for="age" class="block text-gray-700">{{ __('Wiek') }}</label>
                            <input type="number" name="age" id="age" value="{{ $animal->age }}" class="form-control" required>
                        </div>

                        <!-- Opis -->
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">{{ __('Opis') }}</label>
                            <textarea name="description" id="description" class="form-control" required>{{ $animal->description }}</textarea>
                        </div>

                        <!-- Kraj -->
                        <div class="mb-4">
                            <label for="country" class="block text-gray-700">{{ __('Kraj') }}</label>
                            <input type="text" name="country" id="country" value="{{ $animal->country }}" class="form-control">
                        </div>

                        <!-- Miasto -->
                        <div class="mb-4">
                            <label for="city" class="block text-gray-700">{{ __('Miasto') }}</label>
                            <input type="text" name="city" id="city" value="{{ $animal->city }}" class="form-control">
                        </div>

                        <!-- Kolory -->
                        <div class="mb-4">
                            <label class="block text-gray-700">{{ __('Kolory') }}</label>
                            @php
                                $colors = [
                                    'c_black' => __('Czarny'),
                                    'c_white' => __('Biały'),
                                    'c_ginger' => __('Rudy'),
                                    'c_tricolor' => __('Trójkolorowy'),
                                    'c_grey' => __('Szary'),
                                    'c_brown' => __('Brązowy'),
                                    'c_golden' => __('Złoty'),
                                    'c_other' => __('Inny'),
                                ];
                            @endphp
                            @foreach ($colors as $key => $label)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="{{ $key }}" id="{{ $key }}" value="1" class="form-checkbox"
                                        {{ $animal->$key ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Zdjęcie -->
                        <div class="mb-4">
                            <label for="photo" class="block text-gray-700">{{ __('Zdjęcie') }}</label>
                            <input type="file" name="photo" id="photo" class="form-control">
                            @if ($animal->photo)
                                <div class="mt-2">
                                    <img src="{{ asset($animal->photo) }}" alt="Zdjęcie {{ $animal->name }}" class="w-16 h-16 rounded-full object-cover">
                                </div>
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="delete_photo" value="1" class="form-checkbox">
                                        <span class="ml-2">{{ __('Usuń obecne zdjęcie') }}</span>
                                    </label>
                                </div>
                            @endif
                        </div>


                        <!-- Przycisk Zapisz -->
                        <button type="submit" class="btn btn-primary">{{ __('Zapisz zmiany') }}</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">{{ __('Anuluj') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript do dynamicznego wyświetlania pól -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('type');
            const breedCatDiv = document.getElementById('breed-cat');
            const breedDogDiv = document.getElementById('breed-dog');

            function toggleBreedFields() {
                const typeValue = typeSelect.value;
                if (typeValue === 'cat') {
                    breedCatDiv.style.display = 'block';
                    breedDogDiv.style.display = 'none';
                } else if (typeValue === 'dog') {
                    breedCatDiv.style.display = 'none';
                    breedDogDiv.style.display = 'block';
                } else {
                    breedCatDiv.style.display = 'none';
                    breedDogDiv.style.display = 'none';
                }
            }

            // Wywołanie przy ładowaniu strony
            toggleBreedFields();

            // Nasłuchiwanie zmian
            typeSelect.addEventListener('change', toggleBreedFields);
        });
    </script>
</x-app-layout>
