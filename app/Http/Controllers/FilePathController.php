<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\ReportDetail;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;



class FilePathController extends Controller
    {
    public function store(Request $request)
    {
        try {
            $user = User::findOrFail(Auth::id());
            $validator = Validator::make($request->all(), [
                'actual_time' => 'required|numeric',
                'description' => 'required|string|min:10',
                'title'       => 'required|string|min:5',
            ]);
        
            $filePaths = is_string($request->file_path) ? json_decode($request->file_path, true) : $request->file_path;
            $one=false;
            $latestReport = DailyReport::latest()->first(); 
            $today = Carbon::today()->toDateString();
            $filePaths = json_decode($request->file_path, true);
            $invalidPaths = [];
            foreach ($filePaths as $filePath) {

                if (!file_exists($filePath)) {
                    $invalidPaths[] = $filePath;
                }
            }
            
            if ($validator->fails() || !empty($invalidPaths) ) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'path' => !empty($invalidPaths)?'Invalid File Paths':"",
                    'invalid_paths' => $invalidPaths
                ], 422);
            }
    
            foreach ($filePaths as $filePath) {
                $lines = file($filePath);
                
                $insideBlock = false;
                $codeBlock = [];
                $newCodeBlock = [];
                $codeBlockLines = [];  // Stores line numbers for old code
                $newCodeBlockLines = []; // Stores line numbers for new code
                $metadata = [];
            
                foreach ($lines as $lineNumber => $line) {  // Track line number
                    $trimmedLine = trim($line);
                    $lineWithoutTags = str_replace(['{{--', '--}}'], '', $trimmedLine);
            
                    if (strpos($lineWithoutTags, '#Team-') !== false) {
                        $lineData = explode('|', $lineWithoutTags);
                        $formattedDate = Carbon::createFromFormat('d M Y', trim($lineData[3]))->format('Y-m-d');
                        $metadata = [
                            'team' => trim(preg_replace('/^\/\/\s*/', '', $lineData[0])),
                            'type' => trim($lineData[1]),
                            'summary' => trim($lineData[2]),
                            'date' => $formattedDate,
                        ];
            
                        if (strpos($lineWithoutTags, 'Start') !== false) {
                            $insideBlock = true;
                            $codeBlock = [];
                            $newCodeBlock = [];
                            $codeBlockLines = [];
                            $newCodeBlockLines = [];
                            continue;
                        }
            
                        if (strpos($lineWithoutTags, 'End') !== false) {
                            $insideBlock = false;
            
                            if (!empty($codeBlock) || !empty($newCodeBlock)) {
                                if ($user->team_code == $metadata['team'] || $user->user_role == "admin") {
                                    if ($one == false) {
                                        $report = new DailyReport();
                                        $report->user_id = Auth::id();
                                        $report->description = $request->description;
                                        $report->title = $request->title;
                                        $report->name = $user->name;
                                        $report->actual_time = $request->actual_time . ' min';
                                        $report->path = json_encode($filePaths);
                                        $report->save();
                                        $one = true;
                                    }
            
                                    $latestReport = DailyReport::latest()->first();
                                    if ($formattedDate == $today) {
                                        ReportDetail::create([
                                            'team' => $metadata['team'] ?? 'Unknown',
                                            'type' => $metadata['type'] ?? 'Unknown',
                                            'summary' => $metadata['summary'] ?? 'No summary provided',
                                            'date' => $metadata['date'],
                                            'file_path' => $filePath,
                                            'daily_report_id' => $latestReport->id,
            
                                            'old_code' => json_encode(array_values(array_filter(array_map(function ($line, $num) {
                                                $trimmed = trim(ltrim($line, '/ '));
                                                return $trimmed !== '' ? "$num: $trimmed" : null;
                                            }, $codeBlock, $codeBlockLines))), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            
                                            'new_code' => json_encode(array_values(array_filter(array_map(function ($line, $num) {
                                                $trimmed = trim($line);
                                                return $trimmed !== '' ? "$num: $trimmed" : null;
                                            }, $newCodeBlock, $newCodeBlockLines))), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                                        ]);
                                    }
                                }
                            }
                            continue;
                        }
                    }
            
                    if ($insideBlock) {
                        if (strpos($trimmedLine, '#') === 0) {
                            $codeBlock[] = $trimmedLine;
                            $codeBlockLines[] = $lineNumber + 1;  // Store 1-based line number
                        } else {
                            $newCodeBlock[] = $trimmedLine;
                            $newCodeBlockLines[] = $lineNumber + 1;  // Store 1-based line number
                        }
                    }
                }
            }
            
    
            return response()->json([
                'success' => true,
                'message' => 'Report stored yy!',
            ]);
        } catch (\Exception $e) {
            dd($e);
            \Log::error('Error in store method: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function get(Request $request)
    { 
        try {
            $user = User::findOrFail(Auth::id());
            $query = DailyReport::query();
    
            if ($user->user_role == "client") {
                $query->where("user_id", $user->id);
            }
    
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
    
            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }
    
            $reports = $query->with(['ReportData.commentList'])->orderBy('created_at', 'desc')->paginate(13);
    
            return response()->json([
                'success' => true,
                'message' => 'Reports retrieved yy!',
                'reports' => $reports
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    

    public function update(Request $request, $id) {
        try {
            $file = DailyReport::findOrFail($id);
            $reportDetails = ReportDetail::where('daily_report_id', $id)->delete();
            $user = User::findOrFail(Auth::id());

            $validator = Validator::make($request->all(), [
                // 'actual_time' => 'required|numeric',
                'description' => 'required|string|min:10',
                'title'       => 'required|string|min:5',
            ]);
        
            $filePaths = is_string($request->file_path) ? json_decode($request->file_path, true) : $request->file_path;
        
            $invalidPaths = [];
            foreach ($filePaths as $filePath) {

                if (!file_exists($filePath)) {
                    $invalidPaths[] = $filePath;
                }
            }

            if ($validator->fails() || !empty($invalidPaths) ) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'path' => !empty($invalidPaths)?'Invalid File Paths':"",
                    'invalid_paths' => $invalidPaths
                ], 422);
            }
            
            if ($file) { 
                $one=false; 
                $today = Carbon::today()->toDateString();
                $filePaths =  json_decode($request->file_path, true);
                foreach ($filePaths as $filePath) {
                
                    $lines = file($filePath);
                    $insideBlock = false;
                    $codeBlock = [];
                    $newCodeBlock = [];
                    $metadata = [];
                    $codeBlockLines = [];  // Stores line numbers for old code
                    $newCodeBlockLines = []; // Stores line numbers for new code
                    
                
                    foreach ($lines as $lineNumber => $line) {
                        $trimmedLine = trim($line);
                        $lineWithoutTags = str_replace(['{{--', '--}}'], '', $trimmedLine);
                        if (strpos($lineWithoutTags, '#Team-') !== false) {
                            $lineData = explode('|', $lineWithoutTags);
                            $formattedDate = Carbon::createFromFormat('d M Y', trim($lineData[3]))->format('Y-m-d');
                            $metadata = [
                                'team' => trim(preg_replace('/^\/\/\s*/', '', $lineData[0])),   
                                'type' => trim($lineData[1]),   
                                'summary' => trim($lineData[2]), 
                                'date' => $formattedDate, 
                            ];
                
                            if (strpos($lineWithoutTags, 'Start') !== false) {
                                $insideBlock = true;
                                $codeBlock = []; 
                                $newCodeBlock = []; 
                                $codeBlockLines = [];
                                $newCodeBlockLines = [];
                                continue;
                            }
                
                            if (strpos($lineWithoutTags, 'End') !== false) {
                                $insideBlock = false;
                    
                                if (!empty($codeBlock) || !empty($newCodeBlock)) {
                                        if($user->team_code==$metadata['team'] || $user->user_role=="admin"){
                                            if($one==false){
                                                $file->name =$file->name;
                                                $file->title = $request->title;
                                                $file->description = $request->description;
                                                $file->actual_time = $request->actual_time;
                                                $file->path = json_encode($filePaths);
                                                $file->save();
                                                $one=true;
                                            }
                                            $latestReport = $id; 
                                            if ($formattedDate == $today) {
                                                ReportDetail::create([
                                                    'team' => $metadata['team'] ?? 'Unknown',
                                                    'type' => $metadata['type'] ?? 'Unknown',
                                                    'summary' => $metadata['summary'] ?? 'No summary provided',
                                                    'date' => $metadata['date'],
                                                    'file_path' => $filePath,
                                                    'daily_report_id'=>  $latestReport,

                                                    'old_code' => json_encode(array_values(array_filter(array_map(function ($line, $num) {
                                                        $trimmed = trim(ltrim($line, '/ '));
                                                        return $trimmed !== '' ? "$num: $trimmed" : null;
                                                    }, $codeBlock, $codeBlockLines))), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                    
                                                    'new_code' => json_encode(array_values(array_filter(array_map(function ($line, $num) {
                                                        $trimmed = trim($line);
                                                        return $trimmed !== '' ? "$num: $trimmed" : null;
                                                    }, $newCodeBlock, $newCodeBlockLines))), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                                                   
                                                ]);
                                            }
                                        }
                                        // else{
                                        //     return response()->json([
                                        //         'success' => false,
                                        //         'message' => 'Access denied for adding this file',
                                                
                                        //     ]);

                                        // }
                                    // }
                                }
                                continue;
                            }
                        }
                
                       
                        if ($insideBlock) {
                            if (strpos($trimmedLine, '#') === 0) {
                                $codeBlock[] = $trimmedLine;
                                $codeBlockLines[] = $lineNumber + 1;  // Store 1-based line number
                            } else {
                                $newCodeBlock[] = $trimmedLine;
                                $newCodeBlockLines[] = $lineNumber + 1;  // Store 1-based line number
                            }
                        }
                    }
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Report stored yy!',
                // 'report' => $reports
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($id)
    {
        ReportDetail::where('daily_report_id', $id)->delete();

        $file = DailyReport::findOrFail($id);
        $file->delete();

        return response()->json(['message' => 'File entry deleted yy.']);
    }


    public function view(Request $request)
    { 
        return view('viewReport', compact('reports'));
    }

    public function viewDetails(Request $request,$id)

    {
        $reportData = DailyReport::with(['ReportData','ReportData.CommentList'])->findOrFail($id);
        $dailyReportData = DailyReport::findOrFail($id);
        
        // dd($reportData);
        return response()->json([
            'success' => true,
            'report' => $reportData,
            'dailyreport' =>$dailyReportData
        ]); 

    } 

    public function storeComment(Request $request)

    {
        // dd($request);
       try{
        $comment = new Comment();
        $comment->comment_text=$request->comment;
        $comment->user_id=$request->report_id;                          
        $comment->save();
        return response()->json([
            'success' => true,
            'comment' => $comment,
            'message' =>"Comment Added Successful"
        ]); 
       }
       catch (\Throwable $th) {
        dd($th);
        return response()->json([
            'success' => false,
            'error' => 'An error occurred while processing the request.',
            'message' => $e->getMessage(),
        ], 500);
    }
        

    } 
    public function getComment(Request $request)
    {
        $comments = Comment::all();
        if ($comments->isEmpty()) {
            return response()->json(['message' => 'No comments found'], 404);
        }
        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]); 
      
    }

    public function updateStatus(Request $request)
    {
        // Fetching all comments, or filter as necessary
        $reportStatus=ReportDetail::findOrFail($request->report_id);
        if( $reportStatus->status==false){
            $reportStatus->status=true;
            $reportStatus->save();
            return response()->json([
                'success' => true,
                'status' => "Report marked as done successfully",
            ]); 
        }
        else{
            $reportStatus->status=false;
            $reportStatus->save();
            return response()->json([
                'success' => true,
                'status' => "Report unmarked successfully",
            ]); 
        }
    } 
}
