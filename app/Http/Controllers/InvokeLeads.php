<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessLeadsCsv;
use Illuminate\Http\Request;

class InvokeLeads extends Controller
{
    public function dispatchJob()
    {
        ProcessLeadsCsv::dispatch();

        return 'Job has been dispatched successfully!';
    }



}
