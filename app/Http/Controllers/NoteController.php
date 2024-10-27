<?php

namespace App\Http\Controllers;

use App\Enums\Pinned;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NoteCreateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Exceptions\HttpResponseException;

class NoteController extends Controller
{
    public function create(NoteCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();

        $note = new Note($data);
        $note->user_id = $user->id;
        $note->save();

        return (new NoteResource($note))->response()->setStatusCode(201);
    }

    public function get(Request $request): JsonResponse
    {
        $user = Auth::user();

        $notes = Note::where("user_id", $user->id)->get();
        if ($notes->isEmpty()) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "No notes found."
                    ]
                ]
            ])->setStatusCode(404));
        }

        return (NoteResource::collection($notes))->response()->setStatusCode(200);
    }
}
