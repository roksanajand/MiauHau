<?php

namespace App\Http\Controllers;

use App\Models\GuestAndLoggedPhotoCarousel;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class GuestAndLoggedPhotoCarouselController extends Controller
{
    /**
     * Wyświetlanie widoku głównego z karuzelą zdjęć.
     *
     * @return View
     */
    public function index(): View
    {
        // Pobieranie zdjęć i ich szczegółów
        $photosWithDetails = GuestAndLoggedPhotoCarousel::getPhotosWithDetails();

        $photos = array_column($photosWithDetails, 'photo');
        $additionalInfo = array_map(function ($item) {
            return [
                'id' => $item['id'] ?? '',
                'name' => $item['name'] ?? '',
                'city' => $item['city'] ?? '',
                'email' => $item['email'] ?? '',
                'likes' => $item['likes'] ?? 0,
                'liked' => $item['liked'] ?? false,
            ];
        }, $photosWithDetails);

        // Inicjalizacja wartości dla widoku
        $photoCount = count($photos);
        $initialIndex = 0;

        return view('welcome', compact('photos', 'additionalInfo', 'photoCount', 'initialIndex'));
    }

    /**
     * Obsługa polubienia zdjęcia przez użytkownika (AJAX).
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function like(Request $request, $id): JsonResponse
    {
        $animal = Animal::findOrFail($id);

        // Dodawanie lub usuwanie polubienia
        if (!$animal->likes()->where('user_id', auth()->id())->exists()) {
            $animal->likes()->create(['user_id' => auth()->id()]);
            $liked = true;
        } else {
            $animal->likes()->where('user_id', auth()->id())->delete();
            $liked = false;
        }

        // Zwracanie odpowiedzi w formacie JSON
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes' => $animal->likes()->count(),
            'like_url' => route('animals.like', $animal->id),
            'unlike_url' => route('animals.unlike', $animal->id),
        ]);
    }

    /**
     * Obsługa usunięcia polubienia zdjęcia przez użytkownika (AJAX).
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function unlike(Request $request, $id): JsonResponse
    {
        $animal = Animal::findOrFail($id);

        // Usuwanie polubienia
        if ($animal->likes()->where('user_id', auth()->id())->exists()) {
            $animal->likes()->where('user_id', auth()->id())->delete();
        }

        // Zwracanie odpowiedzi w formacie JSON
        return response()->json([
            'success' => true,
            'liked' => false,
            'likes' => $animal->likes()->count(),
            'like_url' => route('animals.like', $animal->id),
            'unlike_url' => route('animals.unlike', $animal->id),
        ]);
    }
}
