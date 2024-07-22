<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\emailNotification; // Adjust the namespace according to your application

class ProcessLeadsCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    protected $csvPath;

    /**
     * Create a new job instance.
     *
     * @param int $campaignID
     * @param string $csvPath
     * @return void
     */
    public function __construct($csvPath)
    {
        $this->csvPath = $csvPath;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        /*
        assuming that we use the loop that runs the actual messagin leads job

        $file = fopen($this->csvPath, 'r');
        $header = fgetcsv($file);

        //finding the total number of leads.
        $totalLeads = 0;
        while (($row = fgetcsv($file)) !== false) {
            $totalLeads++;
        }

        fseek($file, 0);
        fgetcsv($file);
        

        //this is just 
        while (($row = fgetcsv($file)) !== false) {
            DB::table('twitter_accounts')->insert([
                'username' => $row[0],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        //I need to figure out how to get the index of the loop from another job into this. I think I can set it as a parameter in this ones constructor, and when this gets dispatched 
        //from the other job it will give us the index?
        if ($totalLeads - $index <= 50) {
                emailNotification::dispatch();
                break;
        }
        
        */

        //ALLTERNATE
        /*
        $csvPath = storage_path('app/csv/twitter_accounts.csv');

        
        $csvData = array_map('str_getcsv', file($csvPath));

        
        foreach ($csvData as $index => $row) {
            
            DB::table('twitter_accounts')->insert([
                'username' => $row[0], // Assuming username is the first column
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            
            $totalLeads = count($csvData);
            $remainingLeads = $totalLeads - ($index + 1); 

            
            if ($remainingLeads <= 50) {
                emailNotification::dispatch();
                break;
            }
        }








        */


        //$csvPath = storage_path("C:\Users\saiku\FikaThreshhold\storage\FikaExampleLeads.csv"); 
        $file = fopen($this->csvPath, 'r');
        $header = fgetcsv($file);

        $totalLeads = 0;
        while (($row = fgetcsv($file)) !== false) {
            $totalLeads++;
        }

        fseek($file, 0);
        fgetcsv($file);
        

        $currentCount = 0;
        while (($row = fgetcsv($file)) !== false) {
            DB::table('twitter_accounts')->insert([
                'username' => $row[0],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $currentCount++;

            if ($totalLeads - $currentCount <= 50) {
                emailNotification::dispatch();
                break;
            }
        }

        fclose($file);
       
        /*
       Log::info('before dispatch');
        // Dispatch notification job here
        emailNotification::dispatch();
        */
        
    }
}