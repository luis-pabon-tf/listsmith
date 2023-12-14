<?php

namespace App\Http\Controllers;

use App\Mail\CategorySaved;
use App\Models\Category;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Note::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cat_id = $request->input('cat_id');

        if (isset($cat_id)) {
            $category = Category::find($cat_id);
        }

        // the proper way to do this would be to validate the user
        // and THEN use the internally-fetched user's id
        // e.g. $request->user()->id,
        $user = User::find($request->input('user_id'));

        if (!isset($category)) {
            $category = Category::create([
                'name' => $request->input('cat_name'),
            ]);
            $category->save();

            Mail::to($user->email)
                ->send(new CategorySaved($category));
        }

        $note = Note::create([
            'user_id' =>$user->id,
            'category_id' => $category->id,
            'name' => $request->input('name'),
            'text' => $request->input('text'),
        ]);

        return response([
            'note' => $note,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('user.profile', [
            'user' => Note::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $note = Note::findOrFail($id);
        $note->name = $request->input('name');
        $note->text = $request->input('text');
        $note->save();

        return response($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Note::findOrFail($id)::safeDelete();
    }
}
