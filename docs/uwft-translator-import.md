# UWFT Translator Import

UWFT Translator is a system that provides mapping between Workday worktags and legacy Budget Numbers
during the UWFT transition. This data set will not remain available (or achievable) after launch.

* https://uwft-prodweb1.s.uw.edu/FDMTWEB/

You must be on the UW network (campus or VPN) to access UWFT Translator.


## Download Data File

* https://uwft-prodweb1.s.uw.edu/FDMTWEB/

Choose "Basic Search" from left hand navigation. Configure the search as follows.

    System: FAS
    WD Object: Cost Center
    Filter: Org Code (Starts With) 258

Click "Search" button and wait for the data grid at the bottom to populate with results.

"Export to CSV" above data grid.

Save the export and then rename it something with today's date. Example 

* fdm-translator-20230206.csv

Change the report WD Object to "Program" then run it again. 

After you download the second file paste its rows at the bottom of file with Cost Centers so you 
have a single file where column C: "Workday Object Type" has rows with "Cost Center" and rows with
"Program".


## Import the Data File

FTP the data file to server somewhere you can access it from the server console, e.g. your home
directory.

The console command will take the absolute path to this file as an argument.

    cd /www/budgets
    php artisan import:translator ~/fdm-translator-20230206.csv
