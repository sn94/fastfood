<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FamiliaStockChooser extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $name = "";
    public  $value = "";
    public $id="";
    public $callback= "";
    public $style="";
    public $class="";

    public function __construct($name, $value,  $id, $callback, $style, $class="")
    {

        $this->name =  $name;
        $this->value = $value;
        $this->id=  $id;
        $this->callback= $callback;
        $this->style= $style;
        $this->class= $class;
        
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.familia-stock-chooser');
    }
}
