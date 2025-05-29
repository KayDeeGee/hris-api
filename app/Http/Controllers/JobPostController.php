<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Http\Requests\StoreJobPostRequest;
use App\Http\Requests\UpdateJobPostRequest;
use Illuminate\Support\Facades\DB;

class JobPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return JobPost::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobPostRequest $request)
    {
        //
        $job_post = $request->validated();

        try {
            DB::transaction(function () use ($job_post) {
                JobPost::create($job_post);
            });
            return response()->json(['message' => 'Account has been created!'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An Error occured', 'error' => $e->getMessage()], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JobPost $jobPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobPostRequest $request, JobPost $jobPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobPost $jobPost)
    {
        //
    }
}
