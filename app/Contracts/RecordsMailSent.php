<?php
namespace App\Contracts;

use Carbon\Carbon;

interface RecordsMailSent
{
    public function wasSent(Carbon $sentAt): void;
}
