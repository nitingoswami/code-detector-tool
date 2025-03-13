<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyReport;
use App\Models\ProjectList;
use App\Models\ReportDetail;


class ProjectController extends Controller
{

    public function storeProject(Request $request)
    {
        try {
            $project = new ProjectList();
            $project->project_name = $request->project_name;                       
            $project->save();
            return response()->json([
                'success' => true,
                'project' => $project,
                'message' => "Project Added Successfully."
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the request.',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function index(Request $request)
    {
        $user = User::findOrFail(Auth::id());
    
        $projects = ProjectList::with(['dailyReports' => function ($query) use ($user) {
            if ($user->user_role == "client") {
                $query->where('user_id', $user->id);
            }
        }])->get();
    
        if ($projects->isEmpty()) {
            return response()->json(['message' => 'Project list is empty.'], 404);
        }
    
        return response()->json([
            'success' => true,
            'projects' => $projects,
        ]);
    }
    

    public function update(Request $request, $id)
    {
        
        try {
            $project = ProjectList::findOrFail($id);
            
            $project->project_name = $request->project_name;
            $project->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $project = ProjectList::findOrFail($id);
        $project->delete();
    
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
    
}

