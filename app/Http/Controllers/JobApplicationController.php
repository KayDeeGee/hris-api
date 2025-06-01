<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Http\Requests\StoreJobApplicationRequest;
use App\Http\Requests\UpdateJobApplicationRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return JobApplication::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobApplicationRequest $request)
    {
        $data = $request->validated();
        try {
            $application = DB::transaction(function () use ($data) {
                if (!auth()->check()) {
                    $user = User::firstOrCreate(
                        ['email' => $data['email']],
                        [
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name'],
                            'phone' => $data['phone'] ?? null,
                            'password' => null, // guest user
                        ]
                    );

                    $user->assignRole('applicant');
                } else {
                    $user = auth()->user();
                }

                if ($data->hasFile('resume_path')) {
                    $data['resume_path'] = $data->file('resume_path')->store('resumes', 'public');
                }

                // Create job application
                $application = JobApplication::create([
                    'user_id' => $user->id,
                    'job_id' => $data['job_id'],
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
            return response()->json(['message' => 'An Error occured', 'error' => $e->getMessage()], 201);
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
}
