<?php

namespace App\View\Components\Design;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    /**
     * Create a new component instance.
     */
    public $heading, $value, $icon, $color,$desc;
    public function __construct( $heading="Heading", $value="0", $icon="mdi-currency-inr" , $color="success", $desc="")  
    {
        $this->heading = $heading;
        $this->value = $value;
        $this->icon = $icon;        
        $this->color = $color;
        $this->desc = $desc;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.design.card');
    }
}
