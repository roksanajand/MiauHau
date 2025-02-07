<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Cat;
use App\Models\Dog;

class AddAnimalForm extends Component
{
    /**
     * Lista ras kotów.
     *
     * @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cat>
     */
    public $cats;

    /**
     * Lista ras psów.
     *
     * @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dog>
     */
    public $dogs;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Pobranie wszystkich ras kotów i psów
        $this->cats = Cat::all();
        $this->dogs = Dog::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|string
    {
        return view('components.add-animal-form');
    }
}
