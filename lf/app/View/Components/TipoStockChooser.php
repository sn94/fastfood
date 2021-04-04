<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TipoStockChooser extends Component
{


    public  $id = "";
    public $value = "";
    public  $callback = "";
    public $style= "";
    public $class="";
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id="",  $value="",  $callback="", $style="", $class="")
    {
        
        $this->id= $id;
        $this->value= $value;
        $this->callback= $callback;
        $this->style=  $style;
        $this->class= $class;
        
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.tipo-stock-chooser');
    }
}
