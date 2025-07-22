<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveTypeRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $leaveTypes = LeaveType::all();

        return response()->json($leaveTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveTypeRequest $request)
    {
        $leaveType = LeaveType::create($request->validated());

        return response()->json([
            'message' => 'Leave type created successfully.',
            'data' => $leaveType
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveType $leaveType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLeaveTypeRequest $request, LeaveType $leaveType)
    {
        //
        DB::beginTransaction();

        try {
            $leaveType->update($request->validated());

            DB::commit();

            return response()->json([
                'message' => 'Leave type updated successfully.',
                'data' => $leaveType
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update leave type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $leaveType = LeaveType::find($id);

        if (!$leaveType) {
            return response()->json(['message' => 'Leave type not found.'], 404);
        }

        $leaveType->delete();

        return response()->json(['message' => 'Leave type deleted successfully.']);
    }
}
