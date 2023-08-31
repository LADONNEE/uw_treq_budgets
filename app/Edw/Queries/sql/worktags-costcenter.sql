SELECT
  CONCAT(WDO.CostCenterReferenceID, '') AS WorkdayCode,
  CONCAT(WDO.CostCenterName, '') AS WorktagName,
  CONCAT(WDH.OrganizationReferenceID, '') AS HierarchyCode
FROM UWODS.sec.CostCenterHierarchy WDH
INNER JOIN (
  SELECT DISTINCT CostCenterHierarchyKey AS HiKey
  FROM UWODS.sec.CostCenterHierarchy
  WHERE CostCenterHierarchyName LIKE '__UWORG_HIERARCHY_LIKE__'
  UNION
  SELECT DISTINCT Parent_CostCenterHierarchyKey AS HiKey
  FROM UWODS.sec.CostCenterHierarchy
  WHERE CostCenterHierarchyName LIKE '__UWORG_HIERARCHY_LIKE__'
) HPOP
  ON WDH.CostCenterHierarchyKey = HPOP.HiKey
INNER JOIN UWODS.sec.CostCenterHierarchyCostCenter H
  ON WDH.CostCenterHierarchyKey = H.CostCenterHierarchyKey
INNER JOIN UWODS.sec.CostCenter WDO
  ON H.CostCenterKey = WDO.CostCenterKey
ORDER BY WDO.CostCenterKey
