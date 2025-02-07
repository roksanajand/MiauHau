<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Animal;
use App\Http\Controllers\AdminController;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        app(AdminController::class)->checkAccessForAdmin();

        $users = Auth::user();
        // Pobranie zwierząt należących do użytkownika z liczbą polubień
        $animals = Animal::withCount('likes') // Dodanie liczby polubień
        ->where('owner_id', auth()->id())
            ->get();

        // Przekazanie danych do widoku
        return view('dashboard', compact('animals', 'users'));
    }
}
