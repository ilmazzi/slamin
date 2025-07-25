<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display the kanban board with tasks
     */
    public function index(): View
    {
        $tasks = Task::with(['assignedTo', 'createdBy', 'comments'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Raggruppa i task per status
        $tasksByStatus = [
            'todo' => $tasks->where('status', 'todo'),
            'in_progress' => $tasks->where('status', 'in_progress'),
            'review' => $tasks->where('status', 'review'),
            'testing' => $tasks->where('status', 'testing'),
            'done' => $tasks->where('status', 'done'),
        ];

        return view('kanban_board', compact('tasksByStatus'));
    }

    /**
     * Store a new task
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->priority = $request->priority;
        $task->category = $request->category;
        $task->assigned_to = $request->assigned_to;
        $task->created_by = Auth::id();
        $task->due_date = $request->due_date;
        $task->estimated_hours = $request->estimated_hours;
        $task->status = 'todo';

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

        $task->load(['assignedTo', 'createdBy']);

        return response()->json([
            'success' => true,
            'message' => 'Task creato con successo',
            'task' => $task
        ]);
    }

    /**
     * Update task status (for drag & drop)
     */
    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:todo,in_progress,review,testing,done',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $oldStatus = $task->status;
        $task->status = $request->status;

        // Aggiorna le date in base al nuovo status
        if ($request->status === 'in_progress' && !$task->started_at) {
            $task->started_at = now();
        } elseif ($request->status === 'done' && !$task->completed_at) {
            $task->completed_at = now();
        }

        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Status del task aggiornato',
            'task' => $task->load(['assignedTo', 'createdBy']),
            'old_status' => $oldStatus,
            'new_status' => $task->status
        ]);
    }

    /**
     * Update task details
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $task->title = $request->title;
        $task->description = $request->description;
        $task->priority = $request->priority;
        $task->category = $request->category;
        $task->assigned_to = $request->assigned_to;
        $task->due_date = $request->due_date;
        $task->estimated_hours = $request->estimated_hours;
        $task->progress_percentage = $request->progress_percentage ?? 0;

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
            'message' => 'Task aggiornato con successo',
            'task' => $task->load(['assignedTo', 'createdBy'])
        ]);
    }

    /**
     * Delete an image from task
     */
    public function deleteImage(Request $request, Task $task): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image_index' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

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
    }

    /**
     * Delete a task
     */
    public function destroy(Task $task): JsonResponse
    {
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
    }

    /**
     * Get task details for modal
     */
    public function show(Task $task): JsonResponse
    {
        $task->load(['assignedTo', 'createdBy', 'comments.user']);

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }
} 