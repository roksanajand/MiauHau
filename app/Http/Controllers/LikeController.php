<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Animal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    public function like(int $animalId): RedirectResponse
    {
        $userId = Auth::id();

        // Sprawdź, czy użytkownik już polubił to zwierzę
        $alreadyLiked = Like::where('user_id', $userId)
            ->where('animal_id', $animalId)
            ->exists();

        if ($alreadyLiked) {
            return redirect()->back()->with('message', 'Już polubiłeś to zwierzę.');
        }

        // Dodaj polubienie
        Like::create([
            'user_id' => $userId,
            'animal_id' => $animalId,
        ]);

        return redirect()->back()->with('message', 'Polubiono zwierzę.');
    }

    public function unlike(int $animalId): RedirectResponse
    {
        $userId = Auth::id();

        // Usuń polubienie
        Like::where('user_id', $userId)
            ->where('animal_id', $animalId)
            ->delete();

        return redirect()->back()->with('message', 'Polubienie usunięte.');
    }
}
