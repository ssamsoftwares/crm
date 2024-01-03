<?php

namespace App\View\Components\Snippets;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MegaMenu extends Component
{
    /**
     * Create a new component instance.
     */

    public $show ;

    public function __construct( $show )
    {
        $this->show = $show;   
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.snippets.mega-menu');
    }
}
