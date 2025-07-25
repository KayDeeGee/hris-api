<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEmployeeBasicDetailsRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Employee::with('user')->get()->map(function ($employee) {
            return [
                'employeeNumber' => $employee->employee_number,
                'firstName' => $employee->user->first_name,
                'lastName' => $employee->user->last_name,
                'jobTitle' => $employee->jobPost?->title,
                'phone' => $employee->user->phone,
                'email' => $employee->user->email,
            ];
        });
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
    public function show(string $employeeNumber)
    {
        $employee = Employee::with(['user', 'jobPost'])
            ->where('employee_number', $employeeNumber)
            ->firstOrFail();

        return [
            'employeeNumber' => $employee->employee_number,
            'firstName' => $employee->user->first_name,
            'lastName' => $employee->user->last_name,
            'email' => $employee->user->email,
            'phone' => $employee->user->phone,
            'jobTitle' => $employee->jobPost?->title,
            'department' => $employee->jobPost?->department ?? 'N/A',
            'qrCodePath' => $employee->qr_code_path,
            'dateHired' => $employee->created_at->toDateString(),
            'status' => $employee->user->status ?? 'Active', // if status exists
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeBasicDetailsRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $employee) {
                // Update only job_id in employee
                if (isset($validated['job_id'])) {
                    $employee->update(['job_id' => $validated['job_id']]);
                }

                // Update related user details
                $employee->user->update([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'status' => $validated['status'] ?? 'active',
                ]);
            });

            return response()->json(['message' => 'Employee updated successfully']);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update employee.'], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
