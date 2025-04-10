<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage; // Import the Storage facade

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //compact used only when i pass more than one parameter
    //with used to pass 1 parameter
    public function index(): view //return type
    {
        $jobs = Job::all();


        return view('jobs.index')->with('jobs', $jobs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): view
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description'=> 'required|string',
            'salary'=> 'required|numeric', // Use numeric for potentially decimal salaries
            'tags' => 'nullable|string',
            'job_type' => 'required|string',
            'remote_work' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|email', // Use email validation rule
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_website' => 'nullable|url',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048' // Max 2MB
        ]);

        // --- Handle File Upload ---
        $logoPath = null; // Initialize path as null
        if ($request->hasFile('company_logo')) {
            // Store the file in 'public/logos' directory and get the path
            // Make sure 'logos' directory exists in storage/app/public
            $logoPath = $request->file('company_logo')->store('logos', 'public');
        }
        // --------------------------

        // Hardcoded user ID (Consider replacing with auth()->id() if users are logged in)
        $validatedData['user_id'] = 1;

        // --- Use Mass Assignment (More concise) ---
        // Add the stored logo path to the data array
        $validatedData['company_logo'] = $logoPath;

        Job::create($validatedData); // Create the job using mass assignment

        /*
        // --- Original Manual Assignment (Less concise) ---
        $job = new Job();
        $job->title = $validatedData['title'];
        $job->description = $validatedData['description'];
        // $job->description = $validatedData['description']; // Duplicate line removed
        $job->salary = $validatedData['salary'];
        $job->tags = $validatedData['tags'];
        $job->job_type = $validatedData['job_type'];
        $job->remote_work = $validatedData['remote_work'];
        $job->requirements = $validatedData['requirements'];
        $job->benefits = $validatedData['benefits'];
        $job->address = $validatedData['address'];
        $job->city = $validatedData['city'];
        $job->state = $validatedData['state'];
        $job->zipcode = $validatedData['zipcode'];
        $job->contact_email = $validatedData['contact_email'];
        $job->contact_phone = $validatedData['contact_phone'];
        $job->company_name = $validatedData['company_name'];
        $job->company_website = $validatedData['company_website'];
        $job->company_description = $validatedData['company_description'];
        $job->company_logo = $logoPath; // Assign the stored path
        $job->user_id = $validatedData['user_id']; // Assign user_id
        $job->save();
        */

        // --- Important: Link Storage ---
        // If you haven't already, run `php artisan storage:link` in your terminal.
        // This creates a symbolic link from `public/storage` to `storage/app/public`
        // so files stored in `storage/app/public` are publicly accessible.

        return redirect()->route('jobs.index')->with('success', 'Job listing created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job): view
    {
        return view('jobs.show')->with('job', $job);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): string
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): string
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): string
    {
        return 'destroy';
    }
}
