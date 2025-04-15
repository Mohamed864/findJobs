<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Keep commented if not needed
use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get test user - use first() to avoid error if user doesn't exist
        $testUser = User::where('email', 'test@example.com')->first();

        if (!$testUser) {
            // Output an error message using the command object for better console integration
            $this->command->error('Test user (test@example.com) not found. Skipping BookmarkSeeder.');
            return; // Stop seeding if the required user isn't there
        }

        // Get all Job IDs as a simple array
        $jobIds = Job::pluck('id')->all();

        if (empty($jobIds)) {
            $this->command->warn('No jobs found to bookmark. Skipping BookmarkSeeder.');
            return; // Stop if there are no jobs
        }

        // Determine how many jobs to bookmark (up to 3, or fewer if not enough jobs exist)
        $countToBookmark = min(count($jobIds), 3);

        if ($countToBookmark > 0) {
            // Randomly select $countToBookmark *keys* from the $jobIds array
            // Cast to array because array_rand returns a single key if count is 1
            $randomKeys = (array) array_rand($jobIds, $countToBookmark);

            // Get the actual job IDs corresponding to the random keys
            $idsToAttach = [];
            foreach ($randomKeys as $key) {
                $idsToAttach[] = $jobIds[$key];
            }

            // Attach the selected jobs using the relationship.
            // Laravel handles inserting into the 'job_user_bookmarks' pivot table via the relationship definition.
            $testUser->bookmarkedJobs()->attach($idsToAttach);

            $this->command->info("Attached {$countToBookmark} bookmarks for user {$testUser->email}.");

        } else {
             // This case should technically not be reached if $jobIds wasn't empty, but good practice
             $this->command->warn('Could not select any jobs to bookmark.');
        }
    }
}
