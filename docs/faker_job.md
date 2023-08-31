## Faker Job

FakerJob.php is run through an artisan command, `db:faker`, to de-identify database records for demos and 
walk-through videos. This modifies your existing project database. It only runs in non-production environments. **The 
Budgets database has a dependency on educ.persons, you must run this job on educ before running it in Budgets in 
order to update names/uwnetids with fake data.**

### How it works
Each model that needs to be de-identified (faked), should have a corresponding Faker class in `Updaters/Fakes/`, for 
example `PersonFaker`, that extends the BaseFaker class. These faker classes need to be listed in the 
`$fakerClasses` array in `FakerJob.php`. `FakerJob` instantiates new instances of each faker class and runs the 
updateAll() method on `BaseFaker`, which builds the upsert array and runs the query.

The upsert query gets the table's primary key from the faker class, usually 'id', and matches records based on that 
field. **It is important to include the primary key in the array returned from buildFake, otherwise a new row will 
be inserted instead up updating the existing row.** There is a limit on the length of a prepared statement,
so the queries are done in chunks of 1000. 

Each faker class is responsible for specifying which columns to fake and what the fake data should look like, much 
of the fake data is created using the FakerPHP library, which is a part of Laravel. For reference on formatter types,
see [https://fakerphp.github.io/formatters/](https://fakerphp.github.io/formatters/).

This job relies on the database config 'strict' setting being set to false in order to run the upserts without
specifying every not nullable field. This database has a separate ‘faker’ config that’s a copy of the 'mysql' config,
but has 'strict' set to false. The faker config is set as the default when the DbFaker job is called.

When the job runs, it adds a setting 'db-is-faked' with the value of 1 to the settings table. This can be used to 
check if the db has been faked. It's used in EDUC to prevent student pictures from showing when the setting exists.

### Notes
Development notes: [https://www.notion.so/Dummy-Data-for-Demos-aa10f837dfff453e8c0823da7c25ffc5](https://www.notion.so/Dummy-Data-for-Demos-aa10f837dfff453e8c0823da7c25ffc5)
