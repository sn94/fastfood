<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PrettyCheckbox extends Component
{

    public $id= "";

    public  $name=  "";

    public  $value= "";

    public  $label= "";

    public $onValue= "";

    public $offValue= "";

    public $callback="";
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $name, $value, $label, $onValue, $offValue, $id="",  $callback ="")
    {
        $this->name= $name;
        $this->value= $value;
        $this->label= $label;
        $this->onValue= $onValue;
        $this->offValue= $offValue;
        $this->id= $id;
        $this->callback = $callback ;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.pretty-checkbox');
    }
}
