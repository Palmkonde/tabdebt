<?php

namespace App\View\Components;

use App\Models\Tag;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TagPill extends Component
{
    public function __construct(
        public Tag $tag,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tag-pill');
    }
}
