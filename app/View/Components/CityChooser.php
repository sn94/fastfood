<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CityChooser extends Component
{



    public $name = "";

    public $clase = "";

    public  $style =  "";


    public $onChange =  "";


    public  $value = "";
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name,  $clase,  $style,  $value, $onChange="")
    {

        $this->name = $name;
        $this->clase = $clase;
        $this->style = $style;
        $this->value =  $value;
        $this->onChange = $onChange;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.city-chooser');
    }
}
