<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyReport;
use App\Models\ReportDetail;


class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::where('id', '!=', Auth::id())->get();

             // Fetch all users from the database
            return response()->json([
                'success' => true,
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            // Handle exceptions (if any error occurs)
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500); // Return HTTP status 500 (internal server error) in case of failure
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->team_code = $request->team_code;
            $user->slack_name = $request->slack_name;
            $user->email = $request->email;
            $user->user_role = $request->user_role;
            $user->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $reports = DailyReport::where('user_id', $user->id)->get();
        foreach ($reports as $report) {
            ReportDetail::where('daily_report_id', $report->id)->delete();
            $report->delete();
        }
        $user->delete();
    
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
    
}

