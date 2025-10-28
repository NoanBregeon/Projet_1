<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class AccueilController extends Controller
{
    public function __construct(public ?string $message = null)
    {
        $this->message = $message ?? 'Hello';
    }

    public function test()
    {
        return 'Ceci est un test';
    }

    public function index(): View
    {
        return view('welcome');
    }

    public function about(?string $message = null): ?string
    {
        return ! empty(Str::ucfirst(string: $message))
         ? Str::ucfirst(string: $message)
         : $this->message;
    }
}
