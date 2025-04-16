<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage; // Import the Storage facade
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Job;




class JobController extends Controller
{

    use AuthorizesRequests;


    /**
     * Display a listing of the resource.
     */

     // @desc Show all job listings
    // @route GET /jobs

    //compact used only when i pass more than one parameter
    //with used to pass 1 parameter
    public function index(): view //return type
    {


        $jobs = Job::paginate(9);
        //paginate limit the no of jobs but i can scroll across them

        return view('jobs.index')->with('jobs', $jobs);
    }

    /**
     * Show the form for creating a new resource.
     */


     // @desc Show create job form
    // @route GET /jobs/create

    public function create():view
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */

     // @desc Create a new job listing
    // @route POST /jobs

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
        $validatedData['user_id'] = Auth::user()->id; //ide motaw7d

        // --- Use Mass Assignment (More concise) ---
        // Add the stored logo path to the data array
        $validatedData['company_logo'] = $logoPath;

        //check for image
        if($request->hasFile('company_logo')){
            //store the file and get the path
            $logoPath = $request->file('company_logo')->store('logos','public');

            //add pathto validated database
            $validatedData['company_logo'] = $logoPath;
        }

        //submit to datbase
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

     // @desc display all job listings
    // @route GET /jobs/{$id}

    public function show(Job $job): view
    {
        return view('jobs.show')->with('job', $job);
    }

    /**
     * Show the form for editing the specified resource.
     */


     // @desc Show edit job form
    // @route GET /jobs/{$id}/edit


    public function edit(Job $job): view
    {
        //Check if the user is authorized to edit this job
        $this->authorize('update',$job);

        return view('jobs.edit')->with('job', $job);
    }

    /**
     * Update the specified resource in storage.
     */

     // @desc Update a job listing
    // @route PUT /jobs/{$id}


    public function update(Request $request, Job $job): RedirectResponse
    {
        //Check if the user is authorized to update this job
        $this->authorize('update',$job);

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

       // 2. Handle logo upload ONLY if a new file is provided
    if ($request->hasFile('company_logo')) {
        // 2a. Delete the old logo if it exists
        if ($job->company_logo) {
            // Assumes 'company_logo' stores the path relative to the 'public' disk root
            // e.g., 'logos/old_logo.png'
            Storage::disk('public')->delete($job->company_logo);
        }

        // 2b. Store the new logo on the 'public' disk and get its path
        $logoPath = $request->file('company_logo')->store('logos', 'public');

        // 2c. Add/overwrite the 'company_logo' key in $validatedData ONLY if a new file was uploaded
        $validatedData['company_logo'] = $logoPath;
    }

        //submit to datbase
        $job->update($validatedData); // Create the job using mass assignment

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

        return redirect()->route('jobs.index')->with('success', 'Job listing updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */

     // @desc Delete a job listing
    // @route DELETE /jobs/{$id}



    public function destroy(JOB $job): RedirectResponse
    {
        //Check if the user is authorized to delete this job
        $this->authorize('delete',$job);

       //If kogo, then delete it

       if($job->company_logo){
           Storage::delete('storage/' . $job->company_logo);

        }
    $job->delete();

    // Check if request came from dashboard
    if(request()->query('from') == 'dashboard'){
        return redirect()->route('dashboard')->with('success', 'Job listing deleted successfully!');

    }

    return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully!');


    }

    // @desc Search for job listings
    // @route GET /jobs/search


    public function search(Request $request): View
    {
        $keywords = strtolower($request->input('keywords'));
        $location = strtolower($request->input('location'));

        $query = Job::query();
        if($keywords){
            $query->where(function($q) use ($keywords){
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . $keywords . '%'])
                ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $keywords . '%'])
                ->orWhereRaw('LOWER(tags) LIKE ?', ['%' . $keywords . '%']);
            });
        }


        if($location){
            $query->where(function($q) use ($location){
                $q->whereRaw('LOWER(address) LIKE ?', ['%' . $location . '%'])
                ->orWhereRaw('LOWER(city) LIKE ?', ['%' . $location . '%'])
                ->orWhereRaw('LOWER(state) LIKE ?', ['%' . $location . '%'])
                ->orWhereRaw('LOWER(zipcode) LIKE ?', ['%' . $location . '%']);

            });
        }

        $jobs = $query->paginate(12);

        return view('jobs.index')->with('jobs', $jobs);
    }

}
