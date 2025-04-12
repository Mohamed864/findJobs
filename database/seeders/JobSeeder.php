<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Job; // Add this line to use the Job model
use App\Models\User;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load job listing from file
        $jobListings = include database_path('seeders/data/job_listings.php');

        //get test user id
        $testUserId = User::where('email', 'test@example.com')->value('id');




        //get all other user ids from user model
        $userIds = User::where('email','!=','test@example.com')->pluck('id')->toArray();

        // Add user_id, created_at, and updated_at to each job listing
        foreach ($jobListings as  $key => $listing) {

            if($key<2){
                // Assign the first to listings to the test user
                $jobListings[$key]['user_id'] = $testUserId;
            }else{
                //assign user id to listing
                $jobListings[$key]['user_id'] = $userIds[array_rand($userIds)];
            }


            $jobListings[$key]['created_at'] = now();
            $jobListings[$key]['updated_at'] = now();

        }

        //Insert job listings
        DB::table('job_listing')->insert($jobListings);
        echo 'Jobs created successfully';
    }
}
