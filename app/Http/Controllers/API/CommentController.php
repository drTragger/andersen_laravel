<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function create(CreateCommentRequest $request)
    {
        if ($this->commentService->addComment($request))
        {
            return response(['message' => 'Comment created'], 201);
        }
        return response(['message' => 'Failed creating a comment'], 422);
    }
}
