<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->validate([
            'rating'  => ['required','integer','between:1,5'],
            'comment' => ['nullable','string','max:1000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => auth()->id()],
            $data
        );

        return back()->with('info', 'Terima kasih! Review kamu tersimpan.');
    }

    public function destroy()
    {
        Review::where('user_id', auth()->id())->delete();
        return back()->with('info', 'Review kamu dihapus.');
    }
}