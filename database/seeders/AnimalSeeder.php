<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Cat;
use App\Models\Dog;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var User|null $johnDoe */
        $johnDoe = User::where('email', 'john.doe@gmail.com')->first();

        /** @var User|null $doraSmith */
        $doraSmith = User::where('email', 'dora.smith@gmail.com')->first();


        /** @var User|null $aniaKowalska*/
        $aniaKowalska = User::where('email', 'ania.kowalska@gmail.com')->first();

        /** @var Dog|null $Chihuahua */
        $Chihuahua = Dog::where('breed', 'Chihuahua')->first();

        /** @var Dog|null $CockerSpaniel*/
        $CockerSpaniel = Dog::where('breed', 'Cocker Spaniel')->first();

        /** @var Cat|null $inny */
        $inny = Cat::where('breed', 'Inna')->first();

        /** @var Cat|null $Ragdoll */
        $Ragdoll = Cat::where('breed', 'Ragdoll')->first();

        /** @var Dog|null $goldenRetriever */
        $goldenRetriever = Dog::where('breed', 'Golden Retriever')->first();

        /** @var Dog|null $husky */
        $husky = Dog::where('breed', 'Husky Syberyjski')->first();

        /** @var Cat|null $maineCoon */
        $maineCoon = Cat::where('breed', 'Maine Coon')->first();

        // Jedno zwierzę dla John Doe
        if ($johnDoe && $goldenRetriever) {
            DB::table('animals')->updateOrInsert(
                ['name' => 'Buddy', 'owner_id' => $johnDoe->id],
                [
                    'type' => 'dog',
                    'breed_id' => $goldenRetriever->id,
                    'age' => 3,
                    'description' => 'Przyjazny golden retriever.',
                    'country' => 'Stany Zjednoczone',
                    'city' => 'Nowy Jork',
                    'c_black' => 0,
                    'c_white' => 1,
                    'c_ginger' => 0,
                    'c_tricolor' => 0,
                    'c_grey' => 0,
                    'c_brown' => 0,
                    'c_golden' => 1,
                    'c_other' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Dwa zwierzęta dla Dorothéa Smith
        if ($doraSmith && $husky) {
            $animal = DB::table('animals')->updateOrInsert(
                ['name' => 'Shadow', 'owner_id' => $doraSmith->id],
                [
                    'type' => 'dog',
                    'breed_id' => $husky->id,
                    'age' => 5,
                    'description' => 'Energiczny i lojalny husky.',
                    'country' => 'Polska',
                    'city' => 'Warszawa',
                    'c_black' => 1,
                    'c_white' => 1,
                    'c_ginger' => 0,
                    'c_tricolor' => 0,
                    'c_grey' => 0,
                    'c_brown' => 0,
                    'c_golden' => 0,
                    'c_other' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'isApproved' => 'yes',
                    'photo' => 'storage/profile_pictures/pies1.jpg',
                ]
            );

            // Jeśli zwierzak został dodany lub zaktualizowany, pobieramy jego ID
            if ($animal) {
                $animalId = DB::table('animals')
                    ->where('name', 'Shadow')
                    ->where('owner_id', $doraSmith->id)
                    ->value('id');

                /* // Dodajemy like dla zwierzaka od usera o id = 1
                 DB::table('likes')->insert([
                     'user_id' => 1,
                     'animal_id' => $animalId,
                     'created_at' => now(),
                     'updated_at' => now()
                 ]);*/
            }



            if ($maineCoon) {
                DB::table('animals')->updateOrInsert(
                    ['name' => 'Kitty', 'owner_id' => $doraSmith->id],
                    [
                        'type' => 'cat',
                        'breed_id' => $maineCoon->id,
                        'age' => 2,
                        'description' => 'Duży i przyjazny Maine Coon.',
                        'country' => 'Polska',
                        'city' => 'Warszawa',
                        'c_black' => 0,
                        'c_white' => 1,
                        'c_ginger' => 0,
                        'c_tricolor' => 0,
                        'c_grey' => 0,
                        'c_brown' => 1,
                        'c_golden' => 0,
                        'c_other' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'photo' => 'storage/profile_pictures/kot1.jpg',
                    ]
                );
            }

            if ($maineCoon) {
                DB::table('animals')->updateOrInsert(
                    ['name' => 'Śnieżka', 'owner_id' => $doraSmith->id],
                    [
                        'type' => 'cat',
                        'breed_id' => $maineCoon->id,
                        'age' => 2,
                        'description' => 'Duży i przyjazny Maine Coon.',
                        'country' => 'Polska',
                        'city' => 'Warszawa',
                        'c_black' => 0,
                        'c_white' => 0,
                        'c_ginger' => 0,
                        'c_tricolor' => 1,
                        'c_grey' => 0,
                        'c_brown' => 0,
                        'c_golden' => 0,
                        'c_other' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'isApproved' => 'no',

                    ]
                );
            }





        }







        if ($aniaKowalska && $Chihuahua) {
            DB::table('animals')->updateOrInsert(
                ['name' => 'Tiny', 'owner_id' => $aniaKowalska->id], // Unique animal name
                [
                    'type' => 'dog',
                    'breed_id' => $Chihuahua->id,
                    'description' => 'Najukochańsza wredota pańci, jak ugryzie znaczy, że kocha!!!!! ',
                    'country' => 'Polska',
                    'city' => 'Radom',
                    'age' => 10,
                    'c_black' => 0,
                    'c_white' => 1,
                    'c_ginger' => 0,
                    'c_tricolor' => 0,
                    'c_grey' => 0,
                    'c_brown' => 0,
                    'c_golden' => 1,
                    'c_other' => 0,
                    'isApproved' => 'yes',
                    'created_at' => now(),
                    'updated_at' => now(),
                    ]
            );
        }


        if ($aniaKowalska && $CockerSpaniel) {
            DB::table('animals')->updateOrInsert(
                ['name' => 'Buddy', 'owner_id' => $aniaKowalska->id], // Unique animal name
                [
                    'type' => 'dog',
                    'breed_id' => $CockerSpaniel->id,
                    'description' => 'Najukochańszy przyjaciel naszego dzieciaka',
                    'city' => 'Radom',
                    'age' => 7,
                    'c_black' => 1,
                    'c_white' => 0,
                    'c_ginger' => 0,
                    'c_tricolor' => 0,
                    'c_grey' => 0,
                    'c_brown' => 0,
                    'c_golden' => 0,
                    'c_other' => 0,
                    'isApproved' => 'yes',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }


        if ($aniaKowalska && $inny) {
            DB::table('animals')->updateOrInsert(
                ['name' => 'Mystery', 'owner_id' => $aniaKowalska->id],
                [
                    'type' => 'cat',
                    'breed_id' => $inny->id,
                    'description' => 'Koteczek, który przybłąkał się do nas jak byliśmy na wakacjach u babci Stefani.',
                    'country' => 'Polska',
                    'city' => 'Nowa Sarzyna',
                    'age' => 7,
                    'c_black' => 0,
                    'c_white' => 0,
                    'c_ginger' => 0,
                    'c_tricolor' => 0,
                    'c_grey' => 0,
                    'c_brown' => 0,
                    'c_golden' => 0,
                    'c_other' => 1,
                    'isApproved' => 'yes',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }


        if ($aniaKowalska && $Ragdoll) {
            DB::table('animals')->updateOrInsert(
                ['name' => 'Księżniczka', 'owner_id' => $aniaKowalska->id],
                [
                    'type' => 'cat',
                    'breed_id' => $Ragdoll->id,
                    'description' => 'Co tu dużo mówić. Po prostu KSIĘŻNICZKA.',
                    'country' => 'Polska',
                    'city' => 'Gdańsk',
                    'age' => 1,
                    'c_black' => 0,
                    'c_white' => 1,
                    'c_ginger' => 0,
                    'c_tricolor' => 0,
                    'c_grey' => 0,
                    'c_brown' => 0,
                    'c_golden' => 0,
                    'c_other' => 1,
                    'isApproved' => 'yes',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

    }




}
