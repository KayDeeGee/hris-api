<?php

namespace App\Http\Controllers;

use App\Models\LeaveCredit;
use Illuminate\Http\Request;

class LeaveCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $userId = 10; // static for now

        return $this->getEmployeeLeaveCredits($userId);
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
    public function show(LeaveCredit $leaveCredit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveCredit $leaveCredit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveCredit $leaveCredit)
    {
        //
    }

    public function getEmployeeLeaveCredits($userId)
    {
        $credits = LeaveCredit::where('user_id', $userId)
            ->with('leaveType:id,name')
            ->get()
            ->map(function ($credit) {
                return [
                    'leave_type'    => $credit->leaveType->name ?? 'Unknown',
                    'total_credits' => (int) $credit->total_credits,
                    'used_credits'  => (int) $credit->used_credits,
                    'remaining'     => (int) ($credit->total_credits - $credit->used_credits),
                    'valid_from'    => $credit->valid_from,
                    'valid_until'   => $credit->valid_until,
                    'notes'         => $credit->notes ?? '',
                ];
            })
            ->values(); // ensure it’s a clean indexed array

        return response()->json($credits);
    }
}
