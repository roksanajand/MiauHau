<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dodaj Nowe Zwierzę') }}
        </h2>
    </x-slot>

    <div style="margin-top: 20px;">

        <h1>{{ __('Dodaj Nowe Zwierzę') }}</h1>

        <form action="{{ route('add-animal-post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="two-columns">

                <!-- Imię zwierzaka -->
                <div>
                    <label for="name">{{ __('Imię Zwierzaka') }}</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <!-- Wiek -->
                <div>
                    <label for="age">{{ __('Wiek') }}</label>
                    <input type="number" name="age" id="age" required min="0">
                </div>

                <!-- Typ zwierzaka -->
                <div>
                    <label for="type">{{ __('Typ Zwierzaka') }}</label>
                    <select name="type" id="type" onchange="toggleBreedOptions()" required>
                        <option value="">{{ __('Wybierz') }}</option>
                        <option value="cat">{{ __('Kot') }}</option>
                        <option value="dog">{{ __('Pies') }}</option>
                    </select>
                </div>

                <!-- Rasy kotów -->
                <div id="breed-cat" style="display: none;">
                    <label for="breed_id_cat">{{ __('Rasa Kota') }}</label>
                    <select id="breed_id_cat">
                        <option value="">{{ __('Wybierz') }}</option>
                        @foreach ($cats as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->breed }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Rasy psów -->
                <div id="breed-dog" style="display: none;">
                    <label for="breed_id_dog">{{ __('Rasa Psa') }}</label>
                    <select id="breed_id_dog">
                        <option value="">{{ __('Wybierz') }}</option>
                        @foreach ($dogs as $dog)
                            <option value="{{ $dog->id }}">{{ $dog->breed }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ukryte pole breed_id -->
                <input type="hidden" name="breed_id" id="breed_id">

                <div id="trzymaczmiejsca"></div>

                <!-- Lokalizacja -->
                <div>
                    <label for="country">{{ __('Kraj') }}</label>
                    <input type="text" name="country" id="country">
                </div>

                <div>
                    <label for="city">{{ __('Miasto') }}</label>
                    <input type="text" name="city" id="city">
                </div>
            </div>


            <!-- Opis -->
            <div>
                <label for="description">{{ __('Opis') }}</label>
                <textarea name="description" id="description" rows="4" required></textarea>
            </div>


            <label>{{ __('Kolor') }}</label>
            <!-- Kolory -->
            <div id="colors">

                <div>
                    <input type="checkbox" name="c_black" id="c_black" value="1">
                    <label for="c_black">{{ __('Czarny') }}</label>
                </div>
                <div>
                    <input type="checkbox" name="c_white" id="c_white" value="1">
                    <label for="c_white">{{ __('Biały') }}</label>
                </div>
                <div>
                    <input type="checkbox" name="c_ginger" id="c_ginger" value="1">
                    <label for="c_ginger">{{ __('Rudy') }}</label>
                </div>
                <div>
                    <input type="checkbox" name="c_tricolor" id="c_tricolor" value="1">
                    <label for="c_tricolor">{{ __('Trójkolorowy') }}</label>
                </div>
                <div>
                    <input type="checkbox" name="c_grey" id="c_grey" value="1">
                    <label for="c_grey">{{ __('Szary') }}</label>
                </div>
                <div>
                    <input type="checkbox" name="c_brown" id="c_brown" value="1">
                    <label for="c_brown">{{ __('Brązowy') }}</label>
                </div>
                <div>
                    <input type="checkbox" name="c_golden" id="c_golden" value="1">
                    <label for="c_golden">{{ __('Złoty') }}</label>
                </div>
                <div>
                    <input type="checkbox" name="c_other" id="c_other" value="1">
                    <label for="c_other">{{ __('Inny') }}</label>
                </div>
            </div>

            <!-- obraz -->
            <div id="zdj">
                <label for="photo">{{ __('Zdjęcie') }}</label>
                <input type="file" name="photo"  id="photo">
            </div>

            <!-- Przycisk zapisu -->
            <div>
                <button type="submit">{{ __('Dodaj Zwierzaka') }}</button>
            </div>
        </form>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</x-app-layout>

<script>
    function toggleBreedOptions() {
        const type = document.getElementById('type').value;
        const breedCat = document.getElementById('breed-cat');
        const breedDog = document.getElementById('breed-dog');
        const hiddenBreedId = document.getElementById('breed_id');
        const placeholder = document.getElementById('trzymaczmiejsca');

        if (type === 'cat') {
            breedCat.style.display = 'block';
            breedDog.style.display = 'none';
            placeholder.style.display = 'none';
            hiddenBreedId.value = document.getElementById('breed_id_cat').value;
        } else if (type === 'dog') {
            breedCat.style.display = 'none';
            breedDog.style.display = 'block';
            placeholder.style.display = 'none';
            hiddenBreedId.value = document.getElementById('breed_id_dog').value;
        } else {
            breedCat.style.display = 'none';
            breedDog.style.display = 'none';
            placeholder.style.display = '';
            hiddenBreedId.value = '';
        }
    }

    function updateHiddenBreedId() {
        const type = document.getElementById('type').value;
        const hiddenBreedId = document.getElementById('breed_id');

        if (type === 'cat') {
            hiddenBreedId.value = document.getElementById('breed_id_cat').value;
        } else if (type === 'dog') {
            hiddenBreedId.value = document.getElementById('breed_id_dog').value;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleBreedOptions();

        document.getElementById('breed_id_cat').addEventListener('change', updateHiddenBreedId);
        document.getElementById('breed_id_dog').addEventListener('change', updateHiddenBreedId);
    });
</script>

<!-- Dodanie stopki -->
@include('components.footer')

