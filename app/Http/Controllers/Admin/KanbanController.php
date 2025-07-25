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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

            // Reload the task with all relationships for frontend update
            $task->load(['assignedTo', 'createdBy', 'reviewedBy']);

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'task' => $task
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
        $validator = Validator::make($request->all(), [
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

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

            // Gestione upload immagini
            $attachments = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('kanban/tasks', $filename, 'public');
                    
                    $attachments[] = [
                        'type' => 'image',
                        'filename' => $filename,
                        'original_name' => $image->getClientOriginalName(),
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                        'uploaded_at' => now()->toISOString(),
                    ];
                }
            }

            $task->attachments = $attachments;
            $task->save();

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

    /**
     * Update task with new images
     */
    public function updateTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|integer|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:frontend,backend,database,design,testing,deployment,documentation,bug_fix,feature,maintenance',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $task = Task::findOrFail($request->task_id);
            
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'category' => $request->category,
                'assigned_to' => $request->assigned_to,
                'due_date' => $request->due_date,
                'estimated_hours' => $request->estimated_hours,
                'progress_percentage' => $request->progress_percentage ?? 0,
                'notes' => $request->notes,
                'tags' => $request->tags,
            ]);

            // Gestione upload nuove immagini
            $currentAttachments = $task->attachments ?? [];
            
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('kanban/tasks', $filename, 'public');
                    
                    $currentAttachments[] = [
                        'type' => 'image',
                        'filename' => $filename,
                        'original_name' => $image->getClientOriginalName(),
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                        'uploaded_at' => now()->toISOString(),
                    ];
                }
            }

            $task->attachments = $currentAttachments;
            $task->save();

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $task->load(['assignedTo', 'createdBy'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an image from task
     */
    public function deleteImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|integer|exists:tasks,id',
            'image_index' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $task = Task::findOrFail($request->task_id);
            $attachments = $task->attachments ?? [];
            $imageIndex = $request->image_index;

            if (!isset($attachments[$imageIndex])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Immagine non trovata'
                ], 404);
            }

            $imageToDelete = $attachments[$imageIndex];

            // Elimina il file dal filesystem
            if (Storage::disk('public')->exists($imageToDelete['path'])) {
                Storage::disk('public')->delete($imageToDelete['path']);
            }

            // Rimuovi l'immagine dall'array
            unset($attachments[$imageIndex]);
            $attachments = array_values($attachments); // Reindex array

            $task->attachments = $attachments;
            $task->save();

            return response()->json([
                'success' => true,
                'message' => 'Immagine eliminata con successo',
                'task' => $task->load(['assignedTo', 'createdBy'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a task
     */
    public function deleteTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|integer|exists:tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $task = Task::findOrFail($request->task_id);

            // Elimina tutte le immagini associate
            $attachments = $task->attachments ?? [];
            foreach ($attachments as $attachment) {
                if ($attachment['type'] === 'image' && Storage::disk('public')->exists($attachment['path'])) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task eliminato con successo'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting task: ' . $e->getMessage()
            ], 500);
        }
    }
}
