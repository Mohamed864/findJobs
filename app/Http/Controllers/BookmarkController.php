<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request; // Request is not used in this method
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // RedirectResponse is not used
use App\Models\Job; // Job model is used indirectly via the relationship
// use App\Models\User; // User model is used indirectly via Auth

class BookmarkController extends Controller
{
    /**
     * Display the authenticated user's bookmarked jobs.
     *
     * @return \Illuminate\View\View
     */

     // @desc Get all users bookmarked
    // @route GET /bookmarks
    public function index(): View
    {
        /** @var \App\Models\User $user */ // Type hint for better autocompletion
        $user = Auth::user();

        // Use paginate() instead of paginated()
        // The bookmarkedJobs() method returns the relationship query builder
        $bookmarks = $user->bookmarkedJobs()->orderBy('job_user_bookmarks.created_at', 'desc')->paginate(9);

        // Instead of dd(), pass the bookmarks to the view


        return view('jobs.bookmarked')->with('bookmarks', $bookmarks); // Assuming you have a view named 'bookmarks.index'
    }


    // @desc Create a new bookmark for the authenticated user
    // @route POST /bookmarks/{job}
    public function store(Job $job): RedirectResponse
    {
        /** @var \App\Models\User $user */ // Type hint for better autocompletion
        $user = Auth::user();

        // Check ifthe job is already bookmarked by the user

        if($user->bookmarkedJobs()->where('job_id', $job->id)->exists()){
            return back()->with('error', 'Job is already bookmarked!');
        }

        //Create new bookmark
        $user->bookmarkedJobs()->attach($job->id);

        return back()->with('success', 'Job bookmarked successfully!');
    }


      // @desc remove bookmark
    // @route delete /bookmarks/{job}
    public function destroy(Job $job): RedirectResponse
    {
        /** @var \App\Models\User $user */ // Type hint for better autocompletion
        $user = Auth::user();

        // Check ifthe job is not bookmarked by the user

        if(!$user->bookmarkedJobs()->where('job_id', $job->id)->exists()){
            return back()->with('error', 'Job is already bookmarked!');
        }

        //remove bookmark
        $user->bookmarkedJobs()->detach($job->id);

        return back()->with('success', 'Bookmarked removed successfully!');
    }
}
