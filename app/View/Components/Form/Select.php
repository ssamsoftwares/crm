<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Create a new component instance.
     */
    public $name,$label,$options , $values , $selected,$chooseFileComment;
    public function __construct($name, $label, $options=[] , $selected = null,$chooseFileComment=Null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->values =  array_keys($options);
        $this->selected = $selected;
        $this->chooseFileComment = $chooseFileComment;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.select');
    }
}
