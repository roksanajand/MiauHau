<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $photo
 * @property string $name
 * @property string $city
 * @property string $email
 * @property int $likes
 * @property bool $liked
 */
class GuestAndLoggedPhotoCarousel extends Model
{
    protected $table = 'animals'; // Setting the table name

    /**
     * Retrieves a list of approved photos along with associated names, cities, emails, likes, and like status.
     *
     * @return array<int, array<string, string|int|bool>> Array of photo details including 'photo', 'name', 'city', 'email', 'likes', and 'liked'.
     */
    public static function getPhotosWithDetails(): array
    {
        $userId = auth()->id(); // Get the ID of the logged-in user
        $isGuest = !auth()->check(); // Check if the user is not logged in

        $query = self::query()
            ->join('users', 'animals.owner_id', '=', 'users.id') // Join with the users table
            ->leftJoin('likes', 'animals.id', '=', 'likes.animal_id') // Join with the likes table
            ->whereNotNull('animals.photo') // Ensure photo is not NULL
            ->where('animals.isApproved', '=', 'yes') // Only approved photos
            ->groupBy('animals.id', 'animals.photo', 'animals.name', 'animals.city', 'users.email')
            ->select([
                'animals.id',
                'animals.photo',
                'animals.name',
                'animals.city',
                'users.email',
                \DB::raw('COUNT(likes.id) as likes'), // Count likes
            ]);

        // Exclude photos named 'lapka.jpg' for guests
        if ($isGuest) {
            $query->where('animals.photo', 'NOT LIKE', '%lapka.jpg'); // Exclude exact matches for 'lapka.jpg'
        }

        $photos = $query->get();

        // Add 'liked' status for each photo
        $photosWithDetails = $photos->map(function ($photo) use ($userId) {
            $photo->liked = $userId
                ? \DB::table('likes')
                    ->where('animal_id', $photo->id)
                    ->where('user_id', $userId)
                    ->exists()
                : false;

            return $photo;
        });

        return $photosWithDetails->toArray();
    }
}
