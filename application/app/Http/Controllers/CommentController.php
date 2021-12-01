<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request, Project $project, Task $task)
    {
        $cutComment = $request->validated();
        if (Comment::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'comment' => $cutComment['comment'],
        ])) {
            $flash = ['success' => __('Comment created successfully.')];
        } else {
            $flash = ['error' => __('Failed to create the comment.')];
        }

        return redirect()->route('tasks.edit', ['project' => $project->id, 'task' => $task->id])
            ->with($flash);
    }

    public function destroy(Project $project, Task $task, Comment $comment)
    {
        if ($comment->delete()) {
            $flash = ['success' => __('Comment deleted successfully.')];
        } else {
            $flash = ['error' => __('Failed to delete the comment.')];
        }

        return redirect()
            ->route('tasks.edit', ['project' => $project->id, 'task' => $task->id])
            ->with($flash);
    }
}
