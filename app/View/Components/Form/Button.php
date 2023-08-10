<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    // Atributos públicos do componente
    public $name;
    public $type;
    public $isRed;
    public $variant;

    /**
     * Create a new component instance.
     */
    public function __construct(string $name, string $type, bool $isRed, string $variant)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isRed = $isRed;
        $this->variant = $variant;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.button');
    }
}
