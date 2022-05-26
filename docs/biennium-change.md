# Biennium Change

UW Biennium is a two year cycle that runs from July 1 of an odd year through June
30 of the following odd year.

In UW data Budget Numbers exist within bienniums. Within the UW Budget data you will
find both of the following rows.

    Biennium   BudgetNbr    Name                     Status   OrgCode
    2017       02-0820      UWORG TECH IR OPERATIONS   1        2581001000
    2019       02-0820      UWORG TECH IR OPERATIONS   1        2581001000

In this example the attributes of the Budget Number stayed consistent across
bienniums, but that does not need to be the case. Attributes can change and Budgets
can come into existence or cease to exist.

When we near a new biennium this college database will need to be told to start
loading data for the new biennium. Historically we have also deleted the trailing
biennium Budget metadata at the same time.


## Data Model

The use cases for this system are "What is true now?" not historical reporting.
So this system tracks metadata by BudgetNbr, not Biennium + BudgetNbr. 

However, when we want to look at UW attributes of BudgetNbrs, we must have a 
Biennium context specified. Here is a rough model of how these record relate.

    College Budget Record `budgets`
    ----------------------
    * BudgetNumber <- unique index & FK
      FiscalManager
      PI/BusinessOwner
      Purpose
      Notes
      etc
    
    UW Budget Record `uw_budgets_cache`
    -----------------
    * Biennium + BudgetNumber <- unique index
      Name
      OrgCode
      Status
      Dates
      BudgetType
      UWGrantPI


## Moving to Next Biennium

This project provides user managed setting for selecting the current biennium.

* https://treq.uworg.uw.edu/budgets/settings

This setting governs the default biennium for the home page, budget detail pages,
and scope of the search tool. It also is used when selecting the biennium data to 
load during nightly updates from the UW EDW.

__WARNING__ once you change the default biennium to a new one the budget detail 
view will be broken until you run update:budgets. The detail view looks for the 
current biennium UW data to render the page.

1. Change the current-biennium value in settings through web page
2. Run an update `php artisan update:budgets`
3. Delete the old biennium


## Delete a Biennium

We have a console command for deleting an old biennium. This deletes all the UW 
budget records `uw_budgets_cache` for that biennium, then it deletes any orphaned 
college budget records.

    php artisan biennium:delete 2017
