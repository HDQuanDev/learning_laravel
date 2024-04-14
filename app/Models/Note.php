<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'note';
    protected $fillable = ['title', 'content', 'user_id'];

    static public function create_note($user_id, $title, $content, $id_note)
    {
        $note = new Note();
        $note->title = $title;
        $note->content = $content;
        $note->user_id = $user_id;
        $note->id_note = $id_note;
        $note->view = 0;
        $note->time = time();
        $note->save();
        return $note;
    }
    static public function get_note_by_user($id_note)
    {
        return Note::where('user_id', $id_note)->select('id', 'id_note', 'title', 'view', 'created_at', 'updated_at')->get();
    }
    static public function get_note_by_id($id_note)
    {
        return Note::where('id_note', $id_note)->first();
    }
    static public function update_view($id_note)
    {
        return Note::where('id_note', $id_note)->increment('view');
    }
}
