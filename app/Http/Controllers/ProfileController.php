<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $orders = $user->orders()
            ->with(['items.product'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('profile.show', compact('user', 'orders'));
    }
}
