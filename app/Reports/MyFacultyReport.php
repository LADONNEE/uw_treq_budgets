<?php

namespace App\Reports;

use App\Models\Contact;

class MyFacultyReport extends MyEffortReportsReport
{
    public function load()
    {
        return Contact::where('is_faculty', 1)
            ->where('fiscal_person_id', Contact::personToContact($this->person_id))
            ->orderBy('lastname')
            ->get();
    }
}
