<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\NewCommentNotification;

class CommentController extends Controller
{

    use AuthorizesRequests;
    
    /**
     * Elenco dei commenti per un video
     */
    public function index(Video $video)
    {
        $comments = $video->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();

        return CommentResource::collection($comments);
    }

    /**
     * Salva un nuovo commento per un video
     */
    public function store(Request $request, Video $video)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $video->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->input('body'),
            'parent_id' => $request->input('parent_id'),
        ]);

        if (!$comment->parent_id) {
            $video->channel->user->notify(new NewCommentNotification($comment));
        }

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
