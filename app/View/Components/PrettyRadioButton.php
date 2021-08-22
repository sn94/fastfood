<?php

namespace App\View\Components;

use Illuminate\View\Component;

use function PHPUnit\Framework\callback;

class PrettyRadioButton extends Component
{

    public  $name =  "";

    public  $value = "";

    public  $label = "";

    public $checked= "";

    public $size= "32";//tamanio de los iconos

    public $callback= "";
 

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $value, $label, $checked= "no", $size="32", $callback= "" )
    {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->checked = $checked == "si" ? "true" : "false";
        $this->size = $size;
        $this->callback= $callback;
      
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.pretty-radio-button');
    }
}
