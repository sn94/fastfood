<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FastFoodModal extends Component
{


    public $id = "";
    public $title = ""; 
    public $size= "";
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(  $id, $title, $size="")
    {
       $this->id =  $id;
        $this->title = $title; 
        $this->size=  $size;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.fast-food-modal');
    }
}
