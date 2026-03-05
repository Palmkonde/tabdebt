<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $links = [
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Workspace', 'url' => '/workspace'],
            ['name' => 'Websites', 'url' => '/websites'],
            ['name' => 'Groups', 'url' => '/groups'],
        ]
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}
