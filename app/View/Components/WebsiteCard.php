<?php

namespace App\View\Components;

use App\Models\Website;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WebsiteCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Website $website
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.website-card');
    }
}
