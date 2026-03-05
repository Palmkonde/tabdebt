<?php

namespace App\View\Components;

use App\Models\Website;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class WebsiteForm extends Component
{
    public function __construct(
        public Collection $groups,
        public ?Website $website = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.website-form');
    }
}
