<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CaseFileController extends Controller
{
    /**
     * Store new files into an existing batch.
     */
    public function store(Request $request, $batch_id)
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file'], // Removed size limit
        ]);

        // Find proof that this batch exists and we have access
        $firstReport = Report::where('batch_id', $batch_id)->first();
        
        if (!$firstReport) {
            return back()->with('error', 'Case not found.');
        }

        // Authorization: Check if user can add files to this case
        // For now, let's assume auth user can add if they are admin or the case owner
        if (auth()->user()->role !== 'admin' && $firstReport->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        foreach ($request->file('files') as $file) {
            $extension = $file->getClientOriginalExtension() ?: pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename = Str::random(40) . ($extension ? '.' . $extension : '');
            $path = $file->storeAs('reports', $filename, 'public');

            Report::create([
                'user_id' => $firstReport->user_id, // Keep the original owner
                'batch_id' => $batch_id,
                'title' => $firstReport->title,
                'description' => $firstReport->description,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'status' => $firstReport->status,
                'updated_by' => auth()->id(),
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Files uploaded successfully.'
            ]);
        }

        return back()->with('success', 'Files uploaded successfully.');
    }

    /**
     * Remove a specific file from a batch.
     */
    public function destroy(Report $report)
    {
        // Authorization: Admin can delete any. User can only delete THEIR OWN uploads.
        if (auth()->user()->role !== 'admin' && $report->updated_by !== auth()->id()) {
            abort(403, 'Unauthorized action. You can only remove files you uploaded.');
        }

        $batch_id = $report->batch_id;
        
        // Delete from storage
        if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        // Check if any reports remain in the batch
        $remaining = Report::where('batch_id', $batch_id)->count();

        if ($remaining === 0) {
            // Last file deleted, maybe redirect to index?
            $redirect = auth()->user()->role === 'admin' 
                ? route('admin.cases.index') 
                : route('user.reports.index');
            
            return redirect($redirect)->with('success', 'File removed. Since this was the last file, the case has been closed.');
        }

        return back()->with('success', 'File removed successfully.');
    }
}
