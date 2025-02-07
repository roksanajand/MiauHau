<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Sprawdza, czy użytkownik jest administratorem.
     * Jeśli tak, blokuje dostęp do stron, które nie są dla niego przeznaczone.
     */
    public function checkAccessForAdmin(): void
    {
        $user = Auth::user(); // Pobranie zalogowanego użytkownika

        // Sprawdzanie, czy użytkownik jest zalogowany i czy jest administratorem
        if ($user && $user->email === 'adminStrony@gmail.com') {
            abort(403, 'Admin nie ma dostępu do tej strony.');
        }
    }
    public function index(): View
    {
        // Sprawdzenie, czy użytkownik jest adminem
        $user = Auth::user();
        if (!$user || $user->email !== 'adminStrony@gmail.com') {
            abort(403, 'Unauthorized action.');
        }

        // Pobranie zwierząt oczekujących na akceptację
        $animals = Animal::where('isApproved', 'waiting')
            ->with(['catBreed', 'dogBreed']) // Wstępne załadowanie relacji
            ->withCount('prevInfo') // Dodanie licznika wpisów z tabeli `prev_info`
            ->get();
        return view('admin.animals', compact('animals'));
    }
    public function approveAnimal(int $id): RedirectResponse
    {
        $animal = Animal::findOrFail($id); // Pobranie zwierzaka
        $animal->isApproved = 'yes'; // Zmiana statusu na "yes"
        $animal->save(); // Zapis do bazy danych

        return redirect()->route('admin.animals')->with('status', 'Zwierzę zostało zaakceptowane.');
    }

    public function rejectAnimal(int $id): RedirectResponse
    {
        $animal = Animal::findOrFail($id); // Pobranie zwierzaka
        $animal->isApproved = 'no'; // Zmiana statusu na "no"
        $animal->save(); // Zapis do bazy danych

        return redirect()->route('admin.animals')->with('status', 'Zwierzę zostało odrzucone.');
    }


    public function showChangeHistory(int $id): View
    {
        $animal = Animal::findOrFail($id);
        $history = $animal->changeHistory()->orderBy('created_at', 'desc')->get();

        return view('admin.change-history', compact('animal', 'history'));
    }

}
