<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TipoStockChooser extends Component
{


    public  $id = "";
    public $name="";
    public $value = "";
    public  $callback = "";
    public $style= "";
    public $readonly= "N";
    public $atributos= "";
    public $class="";
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id="", $name="",  $value="",  $callback="", $style="", $readonly= "N", $atributos="", $class="")
    {
        
        $this->id= $id;
        $this->name= $name;
        $this->value= $value;
        $this->callback= $callback;
        $this->style=  $style;
        $this->readonly=  $readonly;
        $this->atributos= $atributos;
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
