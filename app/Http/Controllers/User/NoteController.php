<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $request->validate([
                'title' => 'required|string',
                'content' => 'required|string',
            ]);
            $id_note = self::generateIdNote();
            $create = Note::create_note(
                Auth::user()->id,
                $request->title,
                $request->content,
                $id_note
            );

            if ($create) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Note created successfully',
                    'id_note' => $id_note
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create note'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_note()
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $notes = Note::get_note_by_user(Auth::user()->id);
            $notes = $notes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'id_note' => $note->id_note,
                    'title' => $note->title,
                    'view' => $note->view,
                    'created_at' => $note->created_at,
                    'updated_at' => $note->updated_at,
                    'link' => env('APP_URL') . '/note/' . $note->id_note
                ];
            });
            return response()->json([
                'status' => 'success',
                'message' => 'Success get notes',
                'data' => $notes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_note_by_id(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $request->validate([
                'id_note' => 'required|string'
            ]);
            $note = Note::get_note_by_id($request->id_note);
            return response()->json([
                'status' => 'success',
                'message' => 'Success get note',
                'data' => $note
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    static private function generateIdNote()
    {
        $letter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $number = '0123456789';
        $id_note = '';
        for ($i = 0; $i < 10; $i++) {
            $id_note .= $letter[rand(0, strlen($letter) - 1)];
            $id_note .= $number[rand(0, strlen($number) - 1)];
        }
        return $id_note;
    }
}
