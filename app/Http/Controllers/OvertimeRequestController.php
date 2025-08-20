<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOvertimeRequest;
use App\Models\OvertimeRequest;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OvertimeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employeeId = 10;

        $requests = OvertimeRequest::where('employee_id', $employeeId)
            ->latest() // order by created_at desc
            ->paginate(15);

        return response()->json($requests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOvertimeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $overtime = OvertimeRequest::create([
                'employee_id' => 10, // static for now
                'duration' => $validated['hours'],
                'reason' => $validated['reason'],
                'status' => 'pending',
                'reviewed_by' => null,
                'reviewed_at' => null,
                'created_at' => $validated['date'], // date of requested OT
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Overtime request submitted successfully.',
                'data' => $overtime,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to submit overtime request.',
                'error'   => $e->getMessage(), // ⚠️ optional, remove in production
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(OvertimeRequest $overtimeRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OvertimeRequest $overtimeRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OvertimeRequest $overtimeRequest)
    {
        //
    }
}
