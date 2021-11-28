<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project, Task $task)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        if (Comment::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'comment' => $request->comment,
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
