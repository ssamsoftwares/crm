<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    /**
     * Create a new component instance.
     */
    public $name, $label, $value, $rows;
    public function __construct( $name, $label, $value=null, $rows=2)
    {
        $this->name = $name;  
        $this->label = $label;
        $this->value = $value;
        $this->rows = $rows;
    
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.textarea');
    }
}
