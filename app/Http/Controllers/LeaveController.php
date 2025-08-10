<?php

namespace App\Http\Controllers;

use App\Models\LeaveCredit;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $userId = Auth::id();
        $userId = 10;

        // 1️⃣ Get credits summary
        $credits = LeaveCredit::where('user_id', $userId)
            ->with('leaveType') // if you want leave type names
            ->get()
            ->map(function ($credit) {
                return [
                    'leave_type'     => $credit->leaveType->name ?? null,
                    'total_credits'  => $credit->total_credits,
                    'used_credits'   => $credit->used_credits,
                    'remaining'      => $credit->total_credits - $credit->used_credits,
                ];
            });

        // 2️⃣ Get paginated leave requests
        $leaveRequests = LeaveRequest::where('employee_id', $userId) // adjust if employee_id != user_id
            ->with(['leaveType', 'leaveDetails'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'credits' => $credits,
            'requests' => $leaveRequests,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
