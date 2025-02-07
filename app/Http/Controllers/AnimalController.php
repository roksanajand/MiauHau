<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnimalRequest;
use App\Http\Requests\UpdateAnimalRequest;
use App\Models\Animal;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\EditInfo;
use App\Http\Controllers\AdminController;

class AnimalController extends Controller
{
    public function index(): View
    {
        /*$animals = Animal::all();
        return view('animals.index', ['animals' => Animal::all()]);*/
        \Log::info('Metoda index została wywołana przez użytkownika.', ['user_id' => auth()->id()]);
        app(AdminController::class)->checkAccessForAdmin();

        $userAnimals = Animal::where('owner_id', auth()->id())->get();

        \Log::info('Pobrano zwierzęta użytkownika.', ['user_id' => auth()->id(), 'animals_count' => $userAnimals->count()]);
        return view('animals.index', [
            'animals' => $userAnimals,
        ]);
    }


    public function create(): View
    {
        app(AdminController::class)->checkAccessForAdmin();
        \Log::info('Metoda create została wywołana przez użytkownika.', ['user_id' => auth()->id()]);
        $cats = \App\Models\Cat::all(); // Pobranie wszystkich ras kotów
        $dogs = \App\Models\Dog::all(); // Pobranie wszystkich ras psów

        \Log::info('Pobrano rasy kotów i psów.', ['cats_count' => $cats->count(), 'dogs_count' => $dogs->count()]);

        return view('animals.create', compact('cats', 'dogs'));



    }

    public function store(Request $request): RedirectResponse
    {
        app(AdminController::class)->checkAccessForAdmin();
        // dd($request->all());

        \Log::info('Metoda store została wywołana przez użytkownika.', ['user_id' => auth()->id()]);

        try {
            // Dodanie ID właściciela
            $request->merge(['owner_id' => auth()->id()]);



            /** @var array<string, mixed> $validated */
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:cat,dog',
                'breed_id' => 'required|integer',
                'age' => 'required|integer|min:0',
                'description' => 'required|string',
                'country' => 'nullable|string',
                'city' => 'nullable|string',
                'owner_id' => 'required|integer',
                'c_black' => 'nullable|in:0,1',
                'c_white' => 'nullable|in:0,1',
                'c_ginger' => 'nullable|in:0,1',
                'c_tricolor' => 'nullable|in:0,1',
                'c_grey' => 'nullable|in:0,1',
                'c_brown' => 'nullable|in:0,1',
                'c_golden' => 'nullable|in:0,1',
                'c_other' => 'nullable|in:0,1',
                'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp',


            ]);


            \Log::info('Walidacja zakończona sukcesem.', ['validated_data' => $validated]);

            $filename = null;
            if ($request->has('photo')) {
                $file = $request->file('photo');
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $extension = $file->getClientOriginalExtension();

                    $animalName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $validated['name']);
                    $filename = $animalName . '_' . time() . '.' . $extension;

                    $path = 'storage/profile_pictures/';
                    $file->move(public_path($path), $filename);
                    $validated['photo'] = $path.$filename;
                } else {
                    \Log::error('Invalid file uploaded.');
                }
            } else {
                // Przypisanie domyślnego zdjęcia, jeśli brak przesłanego pliku
                $validated['photo'] = 'storage/profile_pictures/lapka.jpg';
            }



            $animal = Animal::create($validated);

            \Log::info('Dodano zwierzę do bazy danych.', ['animal_id' => $animal->id]);
            return redirect()->route('dashboard')->with('animal-add-success', 'Pupil został pomyślnie dodany!');
        } catch (\Exception $e) {
            \Log::error('Error while adding animal: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Nie udało się dodać zwierzaka: ' . $e->getMessage()]);
        }
    }



    public function show(Animal $animal): View
    {
        app(AdminController::class)->checkAccessForAdmin();
        return view('animals.show', compact('animal'));
    }

    public function edit(Animal $animal): View
    {
        app(AdminController::class)->checkAccessForAdmin();
        $cats = \App\Models\Cat::all();
        $dogs = \App\Models\Dog::all();
        return view('animals.edit', compact('animal', 'cats', 'dogs'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        app(AdminController::class)->checkAccessForAdmin();
        $animal = Animal::findOrFail($id);

        // Zapisanie danych przed edycją w tabeli `prev_info`
        $prevInfo = $animal->prevInfo()->create([
            'data' => $animal->toJson(),
        ]);

        // Walidacja danych wejściowych
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cat,dog',
            'breed_id' => 'required|integer',
            'age' => 'required|integer|min:0',
            'description' => 'required|string',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'c_black' => 'nullable|boolean',
            'c_white' => 'nullable|boolean',
            'c_ginger' => 'nullable|boolean',
            'c_tricolor' => 'nullable|boolean',
            'c_grey' => 'nullable|boolean',
            'c_brown' => 'nullable|boolean',
            'c_golden' => 'nullable|boolean',
            'c_other' => 'nullable|boolean',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp',
        ]);

        // Ustawienie wartości 0 dla odznaczonych checkboxów
        $colorCheckboxes = [
            'c_black',
            'c_white',
            'c_ginger',
            'c_tricolor',
            'c_grey',
            'c_brown',
            'c_golden',
            'c_other',
        ];

        foreach ($colorCheckboxes as $color) {
            if (!isset($validated[$color])) {
                $validated[$color] = 0; // Jeśli checkbox nie jest zaznaczony, ustaw wartość 0
            }
        }


        if ($request->has('delete_photo') && $request->delete_photo  && $animal->photo !== 'storage/profile_pictures/lapka.jpg') {
            if ($animal->photo && file_exists(public_path($animal->photo))) {
                unlink(public_path($animal->photo));
            }
            $validated['photo'] = 'storage/profile_pictures/lapka.jpg'; // Przypisanie zdjęcia domyślnego
        }


        // Obsługa przesyłania nowego zdjęcia
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . $animal->id . '.' . $extension;
                $path = 'storage/profile_pictures/';
                $file->move(public_path($path), $filename);
                $validated['photo'] = $path . $filename;

                // Usunięcie starego zdjęcia, jeśli istnieje
                if ($animal->photo && file_exists(public_path($animal->photo))) {
                    unlink(public_path($animal->photo));
                }
            } else {
                \Log::error('Błąd podczas przesyłania zdjęcia.');
            }
        } elseif (!isset($validated['photo']) && $request->has('delete_photo') && $request->delete_photo) {
            // Jeśli zdjęcie zostało usunięte, ale nowe nie zostało przesłane
            $validated['photo'] = 'storage/profile_pictures/lapka.jpg';
        } elseif (!isset($validated['photo'])) {
            // Jeśli brak zmiany w zdjęciu, zachowaj obecne zdjęcie
            $validated['photo'] = $animal->photo;
        }

        $validated['isApproved'] = 'waiting';

        // Aktualizacja danych zwierzaka w tabeli `animals`
        $animal->update($validated);

        // Zapisanie informacji o edycji w tabeli `edited_info`
        EditInfo::create([
            'prev_info_id' => $prevInfo->id,
            'edited_at' => now(),
            'accepted_at' => null, // Możesz ustawić datę, jeśli edycja wymaga akceptacji
        ]);

        // Przekierowanie z komunikatem o sukcesie
        return redirect()->route('dashboard')->with('success_edit', 'Zwierzak został zaktualizowany.');
    }






    public function confirmDelete(int $id): View
    {
        app(AdminController::class)->checkAccessForAdmin();
        $animal = Animal::findOrFail($id);
        return view('animals.delete-animal', compact('animal'));
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        app(AdminController::class)->checkAccessForAdmin();
        $request->validate([
            'password' => 'required', // Hasło jest wymagane
        ]);

        // Sprawdzenie, czy użytkownik jest zalogowany
        $user = auth()->user();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Niepoprawne hasło lub brak autoryzacji.']); // Jeśli hasło jest błędne lub brak użytkownika
        }

        // Znalezienie i usunięcie zwierzaka
        $animal = Animal::findOrFail($id);
        $animal->delete();

        // Przekierowanie z komunikatem sukcesu
        return redirect()->route('dashboard')->with('success_delete', 'Zwierzak został usunięty.');
    }
}
