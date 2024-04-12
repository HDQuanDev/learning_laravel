<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function send_chat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required',
                'username' => 'required'
            ]);
            $payload = [
                'message' => $request->message,
                'username' => $request->username
            ];
            $result = Http::post('https://trumview.net/api/chatapi.php?act=send_message', $payload);
            if ($result->successful()) {
                return $result->json();
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gửi tin nhắn thất bại'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gửi tin nhắn thất bại',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function get_chat()
    {
        $result = Http::get('https://trumview.net/api/chatapi.php?act=get_messages');
        if ($result->successful()) {
            $data = $result->body();

            $lines = explode(",", $data);
            $messages = [];
            foreach ($lines as $line) {
                $message = explode(": ", $line);
                if (count($message) == 2) {
                    $time = explode("|", $message[1]);
                    $messages[] = [
                        'username' => $message[0],
                        'message' => $time[0],
                        'time' => $time[1]
                    ];
                }
            }
            return response()->json([
                'status' => 'success',
                'messages' => $messages
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Lấy tin nhắn thất bại'
            ]);
        }
    }
}
