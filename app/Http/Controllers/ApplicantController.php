<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use App\Models\Job;
use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;








class ApplicantController extends Controller
{
    // @desc Apply for a job
    // @route POST /jobs/{job}/apply
    public function store(Request $request, Job $job): RedirectResponse
    {
        // Check if the user has already applied for this job
        $existingApplication = Applicant::where('job_id', $job->id)
                                        ->where('user_id', Auth::user()->id)
                                        ->exists();



        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }

        // validate incoming data
        $validatedData = $request->validate([
            'full_name' => 'required|string',
            'contact_phone' => 'string',
            'contact_email' => 'required|string|email',
            'message' => 'string',
            'location' => 'string',
            'resume_path' => 'required|file|mimes:pdf|max:2048',

        ]);

        //handle the resume upload
        if($request->hasFile('resume_path')){
            $resumePath = $request->file('resume_path')->store('resumes','public');
            $validatedData['resume_path'] = $resumePath;
        }

        // store the application
        $application = new Applicant($validatedData);
        $application->job_id = $job->id;
        if (Auth::check()) {
            $application->user_id = Auth::id();
        } else {
            abort(403, 'Unauthorized action.');
        }


        $application->save();


        //Send email to owner



        return redirect()->back()->with('success', 'Application submitted successfully!');

    }

    // @desc Remove an application
    // @route DELETE /applicants/{applicant}
    public function destroy($id): RedirectResponse
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->delete();

        return redirect()->route('dashboard')->with('success', 'Application removed successfully!');

    }
}
