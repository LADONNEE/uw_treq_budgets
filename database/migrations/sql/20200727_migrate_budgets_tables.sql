USE budgets;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE budgets;
TRUNCATE TABLE notes;
TRUNCATE TABLE budgets_people;
TRUNCATE TABLE uw_budgets_cache;
TRUNCATE TABLE watchers;
TRUNCATE TABLE roles;
TRUNCATE TABLE budgetlog;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO budgets(
  id,
  budgetno,
  pi_person_id,
  fiscal_person_id,
  reconciler_person_id,
  business_person_id,
  purpose_brief,
  food,
  created_at,
  updated_at
)
SELECT
  id,
  budgetno,
  pi_person_id,
  fiscal_person_id,
  reconciler_person_id,
  business_person_id,
  purpose_brief,
  food,
  created_at,
  updated_at
FROM educ.b_budgets;

INSERT INTO notes(
  id,
  budget_id,
  section,
  note,
  created_by,
  updated_by,
  created_at,
  updated_at
)
SELECT
  id,
  budget_id,
  section,
  note,
  created_by,
  updated_by,
  created_at,
  updated_at
FROM educ.b_notes;

INSERT INTO budgets_people(
  id,
  budget_id,
  person_id,
  description,
  created_at,
  updated_at
)
SELECT
  id,
  budget_id,
  person_id,
  description,
  created_at,
  updated_at
FROM educ.b_people;

INSERT INTO uw_budgets_cache(
  id,
  BienniumYear,
  BudgetNbr,
  BudgetName,
  BudgetStatus,
  BudgetType,
  BudgetClass,
  BudgetClassDesc,
  EffectiveDate,
  OrgCode,
  PayrollUnitCode,
  PrincipalInvestigatorId,
  PrincipalInvestigator,
  TotalPeriodBeginDate,
  TotalPeriodEndDate,
  AccountingIndirectCostRate,
  budgetno,
  created_at,
  updated_at
)
SELECT
  id,
  BienniumYear,
  BudgetNbr,
  BudgetName,
  BudgetStatus,
  BudgetType,
  BudgetClass,
  BudgetClassDesc,
  EffectiveDate,
  OrgCode,
  PayrollUnitCode,
  PrincipalInvestigatorId,
  PrincipalInvestigator,
  TotalPeriodBeginDate,
  TotalPeriodEndDate,
  AccountingIndirectCostRate,
  budgetno,
  created_at,
  updated_at
FROM educ.b_uw_budgets_cache;

INSERT INTO watchers(
  id,
  budget_id,
  person_id,
  created_at,
  updated_at
)
SELECT
  id,
  budget_id,
  person_id,
  created_at,
  updated_at
FROM educ.b_watchers;

INSERT INTO roles(
    id,
    uwnetid,
    role,
    created_at,
    updated_at
)
SELECT
    id,
    uwnetid,
    role,
    created_at,
    updated_at
FROM educ.roles
WHERE role LIKE 'budget:%';

INSERT INTO budgetlog(
  id,
  created_at,
  uwnetid,
  eventtype,
  budget_id,
  person_id,
  data
)
SELECT
  id,
  created_at,
  uwnetid,
  eventtype,
  budget_id,
  person_id,
  data
FROM educ.budgetlog;
