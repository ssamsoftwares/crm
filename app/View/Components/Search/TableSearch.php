<?php

namespace App\View\Components\Search;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableSearch extends Component
{
    /**
     * Create a new component instance.
     */
    public $action, $method, $id, $name,$value, $class, $placeholder,$btn_type,$btnClass,$catVal,$roleName;
    public function __construct($action=null,$method=null,$id=null,$name=null,$value=null,$class=null,$placeholder="Search....",$btn_type="submit",$btnClass=null,$catVal=null,$roleName=null)
    {
        $this->action = $action;
        $this->method = $method;
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->class = $class;
        $this->placeholder = $placeholder;
        $this->btn_type = $btn_type;
        $this->btnClass = $btnClass;
        $this->catVal = $catVal;
        $this->roleName = $roleName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.search.table-search');
    }
}
