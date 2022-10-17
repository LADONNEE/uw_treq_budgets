<?php

namespace App\Updaters;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class ContactUpdater
{
    /**
     * @param array $data
     * @return Contact
     */
    public static function updateOrCreateContact(array $data)
    {
        $field = null;

        if (isset($data['employeeid'])) {
            $field = 'employeeid';
        }

        if (isset($data['uwnetid'])) {
            $field = 'uwnetid';
        }

        $contact = Contact::updateOrCreate(
            [$field => $data[$field]],
            $data
        );

        ContactUpdater::linkUwPerson();

        return $contact;
    }

    /**
     * Updates contacts table using shared.uw_persons info
     */
    private static function linkUwPerson()
    {
        $sql = sqlInclude(__DIR__ . '/Queries/sql/uw_person_link.sql',
                    ['__DBSHARED__' => env('DB_DATABASE_SHARED', 'shared')],
                );
        DB::statement($sql);
    }
}
