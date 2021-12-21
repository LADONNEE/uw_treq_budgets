SELECT BienniumYear,
       BudgetNbr,
       BudgetName,
       BudgetStatus,
       BudgetType,
       BudgetClass,
       BudgetClassDesc,
       EffectiveDate,
       OrgCode,
       OrgName,
       PayrollUnitCode,
       PrincipalInvestigatorId,
       PrincipalInvestigator,
       TotalPeriodBeginDate,
       TotalPeriodEndDate,
       FoodApprovalInd,
       AccountingIndirectCostRate
FROM sec.BudgetIndex
WHERE BienniumYear IN (__BIENNIUMS__)
  AND (
        OrgDeanLevel IN (__COLLEGE_ORGS__)
        OR OrgCode IN (__ORG_CODES__)
        OR BudgetNbr IN (__BUDGETS__)
    )
ORDER BY BudgetType
