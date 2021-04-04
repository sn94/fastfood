<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchReportDownloader extends Component
{


    public $placeholder= "";
    public $callback=  "";
    public  $showSearcherInput= "S";

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($placeholder,   $callback , $showSearcherInput=  "S")
    {
        $this->placeholder= $placeholder;

        $this->callback= $callback;

        $this->showSearcherInput=  $showSearcherInput;
    
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.search-report-downloader');
    }
}
