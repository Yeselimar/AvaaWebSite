<?php

namespace avaa\Exports;

use avaa\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    public function view(): View
    {
        return view('excel.usuarios.todos', [
            'usuarios' => User::all()
        ]);
    }
}