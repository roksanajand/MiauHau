<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Dodaj to
use App\Http\Controllers\AdminController;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        app(AdminController::class)->checkAccessForAdmin();
        $cats = \App\Models\Cat::all(); // Pobranie wszystkich ras kotów
        $dogs = \App\Models\Dog::all(); // Pobranie wszystkich ras psów
        // Pobranie filtrów z żądania
        $type = $request->input('type'); // Kot/pies
        $age = $request->input('age');   // Wiek w latach
        $city = $request->input('city'); // Dodano filtrację według miasta
        $breedCat = $request->input('breed_cat'); // Rasa kota
        $breedDog = $request->input('breed_dog'); // Rasa psa

        $colors = ['c_black', 'c_white', 'c_ginger', 'c_tricolor', 'c_grey', 'c_brown', 'c_golden', 'c_other'];


        // Tworzenie zapytania bazowego
        $query = Animal::where('isApproved', 'yes')
            ->where('owner_id', '!=', Auth::id()); // Wyklucz zwierzęta dodane przez aktualnego użytkownika


        // Filtracja według typu zwierzęcia (kot/pies)
        if ($type) {
            $query->where('type', $type);
        }

        // Filtracja według wieku
        if ($age) {
            $query->where('age', $age);
        }
        if ($city) {
            $query->whereRaw('LOWER(city) LIKE ?', ['%' . strtolower($city) . '%']);
        }
        // Filtracja według rasy
        if ($type === 'cat' && $breedCat) {
            $query->where('breed_id', $breedCat);
        } elseif ($type === 'dog' && $breedDog) {
            $query->where('breed_id', $breedDog);
        }

        // Filtracja według koloru
        $colorFilterApplied = false;
        foreach ($colors as $color) {
            if ($request->has($color) && $request->input($color)) {
                $query->where($color, true);
                $colorFilterApplied = true;
            }
        }

        // Pobranie wyników
        $animals = $query->get();

        // Zwracamy widok z wynikami
        return view('animals.search', compact('animals', 'cats', 'dogs'));
    }
}
