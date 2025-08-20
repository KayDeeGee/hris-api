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

        return response()->json([
            'credits' => $this->leaveCreditSummary($userId),
            'requests' => $this->leaveRequestSummary($userId)
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

    public function leaveRequestSummary($userId)
    {
        $leaveRequests = LeaveRequest::where('employee_id', $userId) // adjust if employee_id != user_id
            ->with(['leaveType', 'leaveDetails'])
            ->latest()
            ->paginate(10);

        return $leaveRequests;
    }

    public function leaveCreditSummary($userId)
    {
        $creditsSummary = LeaveCredit::where('user_id', $userId)
            ->selectRaw('
                SUM(total_credits) as total_credits,
                SUM(used_credits) as used_credits,
                SUM(total_credits - used_credits) as remaining
            ')
            ->first();

        return [
            'total_credits' => (int) $creditsSummary->total_credits,
            'used_credits'  => (int) $creditsSummary->used_credits,
            'remaining'     => (int) $creditsSummary->remaining,
        ];
    }
}
