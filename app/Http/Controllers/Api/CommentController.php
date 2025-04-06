<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{

    use AuthorizesRequests;
    
    /**
     * Elenco dei commenti per un video
     */
    public function index(Video $video)
    {
        return response()->json(
            $video->comments()->latest()->paginate(20)
        );
    }

    /**
     * Salva un nuovo commento per un video
     */
    public function store(Request $request, Video $video)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = $video->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->input('body'),
        ]);

        // return response()->json($comment, Response::HTTP_CREATED);
        return new CommentResource($comment->load('user'));
    }

    /**
     * Elimina un commento (solo se appartiene all'utente)
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->noContent();
    }
}
