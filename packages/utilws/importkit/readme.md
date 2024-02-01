# ImportKit

An import is the structured handling of a series of records from a foreign 
system into the local system.

The core class is an ImportSource. An ImportSource is a wrapper for a stream of 
source data that implements the PHP Traversable interface so it can be used in 
a foreach loop.

The required argument for an ImportSource is a Reader (see ReaderContract). The
Reader provides one record at a time.

    $reader = new CsvFileReader('./myfile.csv');
	$source = new ImportSource($reader);
	
	foreach ($source as $record) {
		// import this $record
	}

	
## Why?

Data imports are like snowflakes: no two are identical. And I got tired of 
reimplementing an import stack because this source is pipe delimited, that 
source has dates with the year-month-date separated by commas, or the other 
source occasionally has garbage rows.

The goal of ImportKit is to provide a standard infrastructure for iterating 
through a data source and make it easy to inject just a little extra 
functionality to deal with that one oddity of a data source.


## Readers

An ImportSource has one required constructor argument: an object that implements 
ReaderContract. 

The Reader's job to provide the next record from the data source. Reader objects 
implement ReaderContract.

    public function read();
    public function reset();

This project provides a couple ReaderContract implementations.

### ArrayReader

Wraps a standard PHP array of values and provides the values through the
ReaderContract interface.

### FileReader

Provides access to a file one line at a time.

### CsvFileReader

Provides access to a Comma Separated Value file one line at a time providing 
each line as an array of values.

	
## Helpers

An ImportSource can accept various helpers to transform the data being imported 
from the source representation to a representation useful to the local system.

You have the option of providing **one** helper of each type.

If helpers are provided to the ImportSource instance they are applied in this 
order.

* SkipRule
* Extractor
* Mapper 
* Parsers
* Validator

Each of these helper types have a PHP Interface defined so you can add your own 
implementation. See the **Implementing Helpers** section below for details of 
the interfaces. 

Following describes the helpers' roles in the import process.

### SkipRule

A SkipRule is an object that can detect records that should not be imported, for 
example header records or blank records.

If a SkipRule is provided, it will be given each record as returned by the 
Reader and asked whether the record should be skipped.

### Extractor

An Extractor helper's job is to split a single record provided by the reader 
into individual fields. An example where an extractor is useful is a fixed width 
input file, an Extractor can be used to break a line into fields at specific 
break points.

### Mapper

The mapper's job is to transform the input record into a structure useful to the 
local system. This may include giving fields local names (by assigning array 
keys) and/or calculating local field values base on record values.

The mapper is provided a record that has made it through any SkipRule and been 
transformed by the optional Extractor.

### Parsers

Parsers are allowed to examine a single field value and transform the value to 
a local representation. Examples of field parsing include:

* Trim outer whitespace
* Recognize and reformat dates
* Convert strings "true" / "false" to boolean
* Extract an atomic value from an aggregate field, get Last Name from "Smith, John"

Parsers cannot be added directly to an ImportSource, they need to be add to a 
ParserLibrary which provides the Parser -> field configuration. So a 
ParserLibrary provides a collection of Parser strategies specific to an import 
process. 

If the ImportSource object is provided a ParserLibrary helper, the 
ParserLibrary will be given the record (after SkipRule, Extractor, Mapper) to 
apply its parsing strategies. 

Parsing strategies are applied when the "name" of a parser matches a field's 
array key in the record.

Find more details about parsers and the ParserLibrary in **Working with Parsers**
section below.

### Validator

If you provide a Validator helper to the ImportSource it will receive each 
record pre-processed by all previous helpers and can test the validity of the 
record about to be imported. 

When the validator returns false the ImportSource skips and continues to the 
next record.

Your Validator implementation may react further to the invalid record, for example 
logging or throwing an Exception.

	
## Composing an Import

The ImportSource class is the basic tool and a Reader is its single requirement.

You can use the included reader implementations for array data or file access or 
you can implement the two simple methods of ReaderContract for your own data 
source.

	$reader = new CsvFileReader('./my-file.csv');
	$import = new ImportSource($reader);
	
From there you can add any helpers needed to the import. The 
ImportSource::addHelper() method type checks for the known helper interfaces 
(see the `src/Contracts` directory or **Implementing Helpers** in this document)
and adds the helper to the correct slot.

    $skipRule = new MySkipRuleImplementation();
	$import->addHelper($skipRule);

The current ImportKit implementation supports a single helper of each type. If 
you add two Mappers the second will replace the previous.


## Configuring an Import

This project also provides a ConfiguredImportSource which does most or all of 
the required object construction based on a configuration array. This is an 
optional strategy.

	$config = [
		'id' => [
			'index' => [ 0 ],
			'parser' => Parse\Integer::class
		],
		'name' => [
			'index' => [ 1 ],
			'parser' => Parse\Text::class
		],
		'balance' => [
			'index' => [ 2 ],
			'parser' => Parse\Integer::class
		],
	];
	
	$reader = new CsvFileReader('./my-file.csv');
	
	$import = new ConfiguredImportSource($reader, $config);
	
	// This import now has a Mapper helper that will rename index 0 to "id", 
	// index 1 to "name", index 2 to "balance" and a ParserLibrary that includes 
	// parse strategies for "id", "name", and "balance"

The benefit of a ConfiguredImportSource is that you can see the behavior of an 
import in a short block of code. Adjusting behavior for several fields can 
happen in a single location. Additionally ConfiguredImportSource may DRY up 
your import: field labels (array indexes) don't need to be synced between mapper 
and the ParserLibrary. 

See the `/demo` directory in this project for an examples of configuration 
arrays. These demo files have additional details on the configuration layout.

* demo/configured-import.php
* demo/fixedwidth-configured-import.php

The ConfiguredImportSource, supplied a proper configuration array can set up a 
FixedWidthExtractor, Mapper, and ParserLibrary for your import.

ConfiguredImportSource extends the base ImportSource so you also have the option 
of creating a ConfiguredImportSource and using its `addHelper()` interface to 
further customize its behavior.


## Implementing Helpers

The purpose of ImportKit is to allow you to add simple implementations that deal 
with unique import conditions. You do this by providing a relevant reader or 
helper and adding that to an ImportSource. 

### ReaderContract

The Reader's job to provide the next record from the data source. The 
ReaderContract has two methods.

    public function read();
	
The `read()` method receives no arguments. It must return the next available 
record from the source. When there are no records left it must return boolean 
`false`.

    public function reset();
	
The `reset()` method receives no arguments. After `reset()` is called the next 
call to `read()` must return the first record from the source.

### SkipRuleContract

The SkipRule's job is to say which raw records should be skipped. It has a 
single method.

    public function shouldSkip($record);

The `shouldSkip()` method receives the raw record provided by the reader. It 
must return boolean `true` if the record should be skipped, `false` otherwise.

### ExtractorContract

The extractor's job is break a raw record into separate fields. Extractor is not 
always needed, in many cases the reader will provide a collection of separated 
fields. It has a single method.

    public function extract($record);

The `extract()` method receives a raw record from the reader that has passed 
through any skip rule. It must return an array that contains the individual 
field values.

### MapperContract

The mapper's job is to provide an array with indexes useful to the local system.
It has a single method.

    public function map($record);

The `map()` method receives an array (that was not skipped and has been 
extracted). It must return an array.

### ParserContract

See **Working with Parser** section of this document (below).

### ValidatorContract

The validator's job is to look at a final transformed records that has passed 
through all previous helpers and check that this the record contains good data.
It has a single method.

    public function valid($record);

The `valid()` method receives the record processed by all previous helpers. It 
must return boolean `true` if the record passes validation and can be used in 
the import, `false` otherwise.

When ImportSource gets `false` from the validator is passes over that record and 
continues on to the next.


## Working with Parsers

### ParserLibrary

The ParserLibrary manages a collection of parser implementations indexed by a 
string name.

When a ParserLibrary is provided as a helper to an ImportSource and a parser 
name (the string index provided to the library) matches an array index of the 
record being processed the parser is applied to the value.

For example:

    // New up a ParserLibrary
    $parserLibrary = new ParserLibrary();
	
	// Add a simple parser named "email" that converts to lower case
	$parserLibrary->add('email', function($val) {
		return strtolower($val);
	});
    
	// Provide the ParserLibrary as a helper to an ImportSource
	$import = new ImportSource($someReader, $parserLibrary);
	
	// When a record comes through the pipeline with an index of 'email'
	// the parse strategy will be applied converting the value to lower case

#### Adding Parsers to ParserLibrary

Parse strategies are stored in ParserLibrary as PHP callables. They can be a 
closure (anonymous function), object callable syntax `[ $obj, 'methodName' ]`, 
or the name of a function that provides the correct interface (accept a single 
argument input value and return the modified value).

    // New up a ParserLibrary
    $parserLibrary = new ParserLibrary();
	
	// Add a function
	$parserLibrary->add('email', 'strtolower');
	
	// Add a closure
	$parserLibrary->add('zip', function($val) {
		return substr($val, 0, 5);
	});
	
	// Add an object callable
	$myParser = new MyParserImplementation();
	$parserLibrary->add('student_number', [$myParser, 'parse']);
	
#### Default Parser

You can optionally provide a default parser to a ParserLibrary. This changes the 
overall behavior of the Parser library.

If no default parser is provided **only** fields that have a specific parser 
where the record index matches a parser "name" will be parsed. Fields in the 
record with no parser are left as is.

If ParserLibrary is given a default parser **all** fields go through some parse 
routine. Fields that have a specific parser where the record index matches a 
parser "name" will be parsed by the specific strategy. Everything else will be 
parsed using the default.

    // New up a ParserLibrary
    $parserLibrary = new ParserLibrary();
	
	// Add a default strategy
	$parserLibrary->default('trim');
	
	// Add a field specific strategy
	$parserLibrary->add('email', 'strtolower');
    
	// Provide the ParserLibrary as a helper to an ImportSource
	$import = new ImportSource($someReader, $parserLibrary);
	
	// During this import fields in records with index "email" get converted to 
	// lower case, all other fields get trim()ed.

### BaseParser

BaseParser is an abstract class that provides default functionality for parsers.
You are not required to use BaseParser to implement ParserContract. If you 
choose to use BaseParser you should leave its `parse()` method as is and 
implement the abstract method `parseValue()`;

    /**
     * Run parse routine on a scrubbed and non-empty value
     * @param string $input
     * @return mixed
     */
    abstract protected function parseValue($input);

Here is the utility the base class provides.

#### $currentInput

Stores the value provided to `parse()` as a protected property 
`$this->currentInput`. This can be useful for error handling or caching.

#### Scrub

BaseParser runs `scrub()` method on the value, by default it trims whitespace. 
Your parser implementation can provide its own `scrub()` if needed. Scrub step 
should be a non-destructive cleanup of input.

#### Empty Handling

Frequently empty values can be detected without fancy parsing. The BaseParser 
does an empty check and if the value is empty returns the preferred empty 
representation. Your parser implementation can provide its own empty handlers.

The default implementations are:

* `isEmpty($input)` returns true if the scrubbed value is identical to the empty 
  string or NULL.
* `emptyValue()` returns NULL.

#### Invalid Handling

Certain parse strategies might require specific input and the received input 
might not match expectation. For example dates have a variety of recognizable 
representations but input "XYZ" is probably not a valid date value.

BaseParser has an `invalid()` method that can be called by your implementation 
from the `parseValue()` method when an invalid input value is detected.

Method `invalid()` accepts an option string `$message` argument where you may 
further describe the situation.

If you choose to fire `invalid()` from your parser you are given multiple 
options for dealing with the situation.

By default `invalid()` throws a custom InvalidParserInputException. Which you 
can catch and handle if desired.

You can also provide the parser a callback via BaseParser::setInvalidCallback(). 
If that callback is provided no Exception will be thrown. The provided callback 
will be invoked with two arguments.

* $currentInput - the original unscrubbed, unparsed input value
* $message - the optional message provided to `invalid()`


## Tips

### SkipRule vs Validator

SkipRule and Validator have similar functionality: they look at a record and 
indicate whether it should be imported.

The difference is timing and pre-processing. SkipRule receives the raw record 
returned by the Reader. Validator receives the processed record that has been 
modified by all previous helpers.

Depending on the criteria you intend to apply you might use SkipRule or 
Validator or possibly both.

### Dealing with Bad Records

How you handle an incoming bad record is in the domain of your application. A 
custom validator might be a useful place to implement that reaction. That could 
mean aborting the import (throw an Exception), logging the problematic record 
for later investigation, or just skipping the bad item and continuing along.


## Run Tests

Run the project's PhpUnit tests.

    $ composer dumpautoload
	$ vendor/bin/phpunit

