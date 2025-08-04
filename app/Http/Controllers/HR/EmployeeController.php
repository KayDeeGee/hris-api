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
        $employee = Employee::with([
            'user',
            'managers.manager',
            'subordinates.employee',
            'personalDetail',
            'addresses',
            'contacts',
            'emergencyContacts',
            'movements'
        ])->where('employee_number', $employeeNumber)->firstOrFail();

        return [
            'employee_number' => $employee->employee_number,
            'qr_code_path' => $employee->qr_code_path,
            'date_hired' => $employee->created_at->toDateString(),
            'status' => $employee->status ?? 'active',

            'personal_details' => [
                'first_name' => $employee->personalDetail->first_name ?? null,
                'middle_name' => $employee->personalDetail->middle_name ?? null,
                'last_name' => $employee->personalDetail->last_name ?? null,
                'suffix' => $employee->personalDetail->suffix ?? null,
                'gender' => $employee->personalDetail->gender ?? null,
                'birthdate' => optional($employee->personalDetail->birthdate)->toDateString(),
                'civil_status' => $employee->personalDetail->civil_status ?? null,
                'nationality' => $employee->personalDetail->nationality ?? null,
            ],

            'addresses' => [
                'region' => $employee->address->region ?? null,
                'province' => $employee->address->province ?? null,
                'city' => $employee->address->city ?? null,
                'barangay' => $employee->address->barangay ?? null,
                'street' => $employee->address->street ?? null,
                'house_number' => $employee->address->house_number ?? null,
                'zip_code' => $employee->address->zip_code ?? null,
            ],

            'contact' => [
                'type' => $employee->contact->type ?? null,
                'value' => $employee->contact->value ?? null,
            ],

            'emergency_contact' => [
                'name' => $employee->emergencyContact->name ?? null,
                'relationship' => $employee->emergencyContact->relationship ?? null,
                'contact_number' => $employee->emergencyContact->contact_number ?? null,
                'email' => $employee->emergencyContact->email ?? null,
                'address' => $employee->emergencyContact->address ?? null,
            ],

            'employment_details' => [
                'job_title' => $employee->jobPost?->title,
                'department' => $employee->jobPost?->department ?? 'N/A',
                'reports_to' => $employee->managers->map(function ($report) {
                    if (!$report->manager) return null;

                    return [
                        'id' => $report->manager->id,
                        'full_name' => $report->manager->full_name,
                        'relationship_type' => $report->relationship_type,
                    ];
                })->filter()->values(), // filter out nulls

                'reports_from' => $employee->subordinates->map(function ($report) {
                    if (!$report->employee) return null;

                    return [
                        'id' => $report->employee->id,
                        'full_name' => $report->employee->full_name,
                        'relationship_type' => $report->relationship_type,
                    ];
                })->filter()->values(), // filter out nulls
                'movements' => $employee->movements->map(function ($movement) {
                    return [
                        'movement_type' => $movement->movement_type,
                        'description' => $movement->description,
                        'effective_date' => optional($movement->effective_date)->toDateString(),
                        'from_job_id' => $movement->from_job_id,
                        'to_job_id' => $movement->to_job_id,
                    ];
                }),
            ],
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
