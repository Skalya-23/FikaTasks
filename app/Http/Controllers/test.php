<?php

namespace Tests\Feature;

use App\Jobs\emailNotification;
use App\Jobs\ProcessDataJob;
use App\Jobs\ProcessLeadsCsv;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessDataJobTest extends TestCase
{
    /** @test */
    public function it_processes_data_correctly()
    {
        Queue::fake();

        // Dispatch the job
        $data = ['some' => 'data'];
        ProcessLeadsCsv::dispatch($data);

        // Assert the job was pushed to the queue
        Queue::assertPushed(ProcessLeadsCsv::class, function ($job) use ($data) {
            return $job->data === $data;
        });
    }
}