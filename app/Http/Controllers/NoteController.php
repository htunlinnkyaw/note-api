<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', auth()->id())->get();
        return response()->json(['notes' => $notes, 'message' => 'Notes retrieved successfully']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note = Note::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => auth()->id(),
        ]);


        return response()->json(["data" => $note, "message" => "Note created successfully"], 201);

    }

    public function show(Note $note)
    {
        if ($note->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['note' => $note, 'message' => 'Note retrieved successfully']);
    }

    public function update(Note $note, Request $request)
    {
        if ($note->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $note->update($data);

        return response()->json(['note' => $note, 'message' => 'Note updated successfully']);
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json(['message' => 'Note deleted successfully']);

    }
}
