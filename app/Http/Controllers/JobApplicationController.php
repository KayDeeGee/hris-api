<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Http\Requests\StoreJobApplicationRequest;
use App\Http\Requests\UpdateJobApplicationRequest;
use App\Models\Employee;
use App\Models\User;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = JobApplication::with(['user', 'jobPost'])->where('status', 'pending')->get();

        return response()->json($applications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobApplicationRequest $request)
    {
        $data = $request->validated();

        try {
            $application = DB::transaction(function () use ($request, $data) {
                if (!auth()->check()) {
                    $user = User::firstOrCreate(
                        ['email' => $data['email']],
                        [
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name'],
                            'phone' => $data['phone'] ?? null,
                            'password' => null,
                        ]
                    );
                    $user->assignRole('applicant');
                } else {
                    $user = auth()->user();
                }

                // ✅ Correct: check and handle file from $request
                if ($request->hasFile('resume_path')) {
                    $data['resume_path'] = $request->file('resume_path')->store('resumes', 'public');
                }

                return JobApplication::create([
                    'user_id' => $user->id,
                    'job_post_id' => $data['job_id'],
                    'cover_letter' => $data['cover_letter'] ?? null,
                    'resume_path' => $data['resume_path'] ?? null,
                    'applied_at' => now(),
                ]);
            });

            return response()->json([
                'message' => 'Application submitted successfully.',
                'application' => $application,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(JobApplication $jobApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobApplicationRequest $request, JobApplication $jobApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $jobApplication)
    {
        //
    }

    public function updateStatus(JobApplication $jobApplication, Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:reviewed,shortlisted,hired,rejected']
        ]);

        DB::beginTransaction();

        $jobApplication->update(['status' => $validated['status']]);

        try {
            $jobApplication->update(['status' => $validated['status']]);

            if ($validated['status'] === 'hired') {
                $this->createEmployee($jobApplication->user);
            }

            DB::commit();

            return response()->json([
                'message' => "Application {$validated['status']} successfully.",
                'application' => $jobApplication,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => "Application {$validated['status']} successfully.",
            'application' => $jobApplication,
        ]);
    }

    private function createEmployee($user)
    {
        // Check if employee already exists
        $existing = Employee::where('user_id', $user->id)->first();
        if ($existing) return $existing;

        $emp_no = $this->generateEmployeeNumber();
        $qr_code_path = $this->generateQRCode($emp_no);

        return Employee::create([
            'user_id' => $user->id,
            'employee_number' => $emp_no,
            'qr_code_path' => $qr_code_path
        ]);
    }


    private function generateEmployeeNumber()
    {
        $last = Employee::latest()->first();
        $number = $last ? $last->id + 1 : 1;
        return 'EMP-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    private function generateQRCode($emp_no)
    {
        $qrCode = new QrCode((string) $emp_no);
        $writer = new PngWriter();

        $result = $writer->write($qrCode);

        $path = "qr_codes/employee_{$emp_no}.png";
        Storage::disk('public')->put($path, $result->getString());

        return $path;
    }
}
