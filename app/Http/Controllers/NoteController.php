<?php

namespace App\Http\Controllers;

use App\Enums\Pinned;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NoteCreateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;

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
}
