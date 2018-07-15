<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function getTasks(Request $request){
        $tasks = Task::orderBy('id','desc')->where('user_id','=', Auth::id())->paginate(3);
        return[
            'pagination' => [
                'total' => $tasks->total(),
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'last_page' => $tasks->lastPage(),
                'from' => $tasks->firstItem(),
                'to' => $tasks->lastPage(),
            ],
            'tasks' => $tasks
        ];
    }

    public function createTask(Request $request)
    {

        $task = New Task;
        $task->name = $request->name;
        $task->priority = $request->priority;
        $task->user_id = Auth::id();
        $task->save();
        return response()->json([
            'mensaje' => 'Tarea guardada'
        ]);
    }

    public function updateTaskState(Request $request)
    {

        $task = Task::findOrFail($request->id);
        $task->state = $request->state;
        $task->save();
        return response()->json([
            'mensaje' => 'Estado Actualizado'
        ]);
    }

    public function updateTask(Request $request)
    {

        $task = Task::findOrFail($request->id);
        $task->name = $request->name;
        $task->priority = $request->priority;
        $task->user_id = Auth::id();
        $task->save();
        return response()->json([
            'mensaje' => 'Tarea Actualizada'
        ]);
    }

    public function deleteTask(Request $request){
        $task = Task::findOrFail($request->id);
        $task->delete();
        return 'La tarea ha sido eliminada';
    }
}
