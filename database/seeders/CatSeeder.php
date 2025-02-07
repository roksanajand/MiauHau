<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cat;

class CatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    /*zmienic polskie zanki albo zmienić kodowanie */
    public function run(): void
    {
        $cats = [
            ['breed' => 'Perski'],
            ['breed' => 'Maine Coon'],
            ['breed' => 'Syberyjski'],
            ['breed' => 'Ragdoll'],
            ['breed' => 'Bengalski'],
            ['breed' => 'Brytyjski krótkowłosy'],
            ['breed' => 'Sfinks'],
            ['breed' => 'Devon Rex'],
            ['breed' => 'Norweski leśny'],
            ['breed' => 'Angora Turecka'],
            ['breed' => 'Kot syjamski'],
            ['breed' => 'Kot himalajski'],
            ['breed' => 'Abisyński'],
            ['breed' => 'Burmski'],
            ['breed' => 'Cornish Rex'],
            ['breed' => 'Somalijski'],
            ['breed' => 'Manx'],
            ['breed' => 'Chartreux (Kartuski)'],
            ['breed' => 'Egipski Mau'],
            ['breed' => 'Kot amerykański krótkowłosy'],
            ['breed' => 'Kot orientalny krótkowłosy'],
            ['breed' => 'Kot rosyjski niebieski'],
            ['breed' => 'Kot singapurski'],
            ['breed' => 'Kot tonkijski'],
            ['breed' => 'Kot europejski krótkowłosy'],
            ['breed' => 'Kot balijski'],
            ['breed' => 'Kot bombajski'],
            ['breed' => 'Kot japoński bobtail'],
            ['breed' => 'Kot turecki Van'],
            ['breed' => 'Kot selkirk rex'],
            ['breed' => 'Kot savannah'],
            ['breed' => 'Inna'],
        ];

        foreach ($cats as $cat) {
            Cat::create($cat);
        }
    }
}
