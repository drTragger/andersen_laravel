<?php


namespace App\Services;


use App\Models\Comment;

class CommentService
{
    public function addComment($request)
    {
        $data = [
          'text' => $request->text,
          'user_id' =>   $request->user()->id,
        ];
        return Comment::create($data);
    }
}
