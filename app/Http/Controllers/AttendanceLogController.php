<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceLogRequest;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AttendanceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceLogRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $log = DB::transaction(function () use ($validated) {
                // Look up employee by employee number
                $employee = Employee::where('employee_number', $validated['employee_number'])->firstOrFail();

                // Create the attendance log
                return AttendanceLog::create([
                    'employee_id' => $employee->id,
                    'log_time'    => $validated['log_time'],
                    'device_name' => $validated['device_name'],
                    'log_type'    => $validated['log_type'],
                    'log_method'  => $validated['log_method'],
                ]);
            });

            return response()->json([
                'employee' => $validated['employee_number'],
                'type'     => $validated['log_type'] === 1 ? 'in' : 'out',
                'time'     => $log->log_time,
            ]);
        } catch (Throwable $e) {
            // Optional: log the error to storage/logs/laravel.log
            Log::error('Attendance log failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Something went wrong while logging attendance.',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceLog $attendanceLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceLog $attendanceLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceLog $attendanceLog)
    {
        //
    }

    public function latest(Request $request)
    {

        $deviceName = $request->query('device_name'); // Optional filter

        Log::info('Device Name:', ['device_name' => $deviceName]);

        $query = AttendanceLog::with('employee.user') // eager-load user info
            ->orderByDesc('log_time')
            ->limit(10);
        Log::info('Device Name:', ['device_name' => $query]);


        if ($deviceName) {
            $query->where('device_name', $deviceName);
        }

        $logs = $query->get()->map(function ($log) {
            return [
                'employee' => $log->employee->employee_number ?? 'Unknown',
                'type' => $log->log_type === 1 ? 'in' : 'out',
                'time' => is_string($log->log_time) ? $log->log_time : $log->log_time->toDateTimeString(),
            ];
        });

        return response()->json($logs);
    }
}
