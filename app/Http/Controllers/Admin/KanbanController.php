<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Photo;
use App\Models\User;
use App\Models\Event;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    public function index()
    {
        // Recupera dati per le colonne del kanban di sviluppo
        $data = [
            'todo_tasks' => Task::where('status', 'todo')->with(['assignedTo', 'createdBy'])->get(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->with(['assignedTo', 'createdBy'])->get(),
            'review_tasks' => Task::where('status', 'review')->with(['assignedTo', 'createdBy', 'reviewedBy'])->get(),
            'testing_tasks' => Task::where('status', 'testing')->with(['assignedTo', 'createdBy'])->get(),
            'done_tasks' => Task::where('status', 'done')->with(['assignedTo', 'createdBy'])->get(),
            'overdue_tasks' => Task::overdue()->with(['assignedTo', 'createdBy'])->get(),
            'due_today_tasks' => Task::dueToday()->with(['assignedTo', 'createdBy'])->get(),
        ];

        // Statistiche
        $stats = [
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', 'done')->count(),
            'overdue_tasks' => Task::overdue()->count(),
            'tasks_due_today' => Task::dueToday()->count(),
            'total_hours_estimated' => Task::sum('estimated_hours'),
            'total_hours_actual' => Task::sum('actual_hours'),
        ];

        // Get users for assignment dropdown (users with admin or user roles)
        $users = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'user']);
        })->get();

        return view('admin.kanban.index', compact('data', 'stats', 'users'));
    }

    public function updateTaskStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer',
            'new_status' => 'required|in:todo,in_progress,review,testing,done'
        ]);

        try {
            $task = Task::findOrFail($request->task_id);
            $oldStatus = $task->status;
            
            $task->update([
                'status' => $request->new_status,
                'started_at' => $request->new_status === 'in_progress' && $oldStatus === 'todo' ? now() : $task->started_at,
                'completed_at' => $request->new_status === 'done' ? now() : null,
            ]);

            // Aggiungi commento di aggiornamento status
            TaskComment::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'content' => "Status cambiato da {$oldStatus} a {$request->new_status}",
                'type' => 'status_update',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'task' => $task->load(['assignedTo', 'createdBy'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating task status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTaskDetails(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer'
        ]);

        try {
            $task = Task::with(['assignedTo', 'createdBy', 'reviewedBy', 'comments.user', 'subtasks'])
                ->findOrFail($request->task_id);

            return response()->json([
                'success' => true,
                'task' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving task details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:frontend,backend,database,design,testing,deployment,documentation,bug_fix,feature,maintenance',
            'status' => 'required|in:todo,in_progress,review,testing,done',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        try {
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'category' => $request->category,
                'status' => $request->status,
                'assigned_to' => $request->assigned_to,
                'created_by' => Auth::id(),
                'due_date' => $request->due_date,
                'estimated_hours' => $request->estimated_hours,
                'progress_percentage' => $request->progress_percentage ?? 0,
                'notes' => $request->notes,
                'tags' => $request->tags,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task->load(['assignedTo', 'createdBy'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating task: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addComment(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer|exists:tasks,id',
            'content' => 'required|string',
            'type' => 'required|in:comment,status_update,time_log,review',
            'is_internal' => 'boolean',
        ]);

        try {
            $comment = TaskComment::create([
                'task_id' => $request->task_id,
                'user_id' => Auth::id(),
                'content' => $request->content,
                'type' => $request->type,
                'is_internal' => $request->is_internal ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => $comment->load('user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding comment: ' . $e->getMessage()
            ], 500);
        }
    }
} 