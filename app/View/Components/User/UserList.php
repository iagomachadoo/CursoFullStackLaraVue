<?php

namespace App\View\Components\User;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserList extends Component
{
    public $users;
    public $type;
    public $class;
    /**
     * Create a new component instance.
     */
    public function __construct($type = 'lista', $class = 'bg-initial')
    {
        $this->users = User::all();
        $this->type = $type;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user.user-list');
    }
}
