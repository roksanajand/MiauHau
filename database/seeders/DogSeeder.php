<?php

namespace Database\Seeders;

use App\Models\Dog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    /*zmienic polskie zanki albo zmienić kodowanie */
    public function run(): void
    {
        $dogs = [
            ['breed' => 'Owczarek Niemiecki'],
            ['breed' => 'Labrador Retriever'],
            ['breed' => 'Golden Retriever'],
            ['breed' => 'Beagle'],
            ['breed' => 'Bulldog'],
            ['breed' => 'Pudel'],
            ['breed' => 'Bokser'],
            ['breed' => 'Rottweiler'],
            ['breed' => 'Yorkshire Terrier'],
            ['breed' => 'Shih Tzu'],
            ['breed' => 'Cocker Spaniel'],
            ['breed' => 'Dachshund (Jamnik)'],
            ['breed' => 'Husky Syberyjski'],
            ['breed' => 'Border Collie'],
            ['breed' => 'Jack Russell Terrier'],
            ['breed' => 'Chihuahua'],
            ['breed' => 'Doberman'],
            ['breed' => 'Akita Inu'],
            ['breed' => 'Samoyed'],
            ['breed' => 'Dalmatyńczyk'],
            ['breed' => 'Alaskan Malamute'],
            ['breed' => 'Cavalier King Charles Spaniel'],
            ['breed' => 'Papillon'],
            ['breed' => 'West Highland White Terrier'],
            ['breed' => 'Whippet'],
            ['breed' => 'Shar Pei'],
            ['breed' => 'Basset Hound'],
            ['breed' => 'Belgian Malinois'],
            ['breed' => 'Mops (Pug)'],
            ['breed' => 'Bernardyn'],
            ['breed' => 'Leonberger'],
            ['breed' => 'Inna'],
        ];

        foreach ($dogs as $dog) {
            Dog::create($dog);
        }


    }
}
