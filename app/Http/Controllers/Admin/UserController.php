<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Mostrar la lista de usuarios.
     */
    public function index(): View
    {
        $users = User::with('role')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }
}
