<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Share extends Component
{
    public $url;

    public function __construct($url = null)
    {
        $this->url = $url ?? request()->fullUrl();
    }

    public function render()
    {
        return view('components.share');
    }
}
