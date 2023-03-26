<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Services\CommentService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function createComment(CreateCommentRequest $request, $blogId)
    {
        $commentData = $request->validated();
        $createdComment = $this->commentService->create($commentData, $blogId, Auth::user()->id);

        return response([
            'data' => $createdComment
        ], Response::HTTP_CREATED);
    }

    public function deleteComment($blogId, $commentId)
    {
        $this->commentService->delete($blogId, $commentId);

        return response([
            'message' => 'comment deleted'
        ], Response::HTTP_OK);
    }
}
