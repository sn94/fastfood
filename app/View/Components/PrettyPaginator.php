<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PrettyPaginator extends Component
{



    public $datos= [];
    public $callback=  "fill_grill";

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(   $datos,  $callback )
    {
        $this->datos=  $datos;
      
        $this->callback =  $callback ;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.pretty-paginator');
    }
}
