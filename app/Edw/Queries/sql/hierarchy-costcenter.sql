SELECT
  CONCAT(WDH.OrganizationReferenceID, '') AS WorkdayCode,
  CONCAT(WDH.CostCenterHierarchyName, '') AS HierarchyName,
  CONCAT(PH.OrganizationReferenceID, '') AS ParentWorkdayCode
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
INNER JOIN UWODS.sec.CostCenterHierarchy PH
  ON WDH.Parent_CostCenterHierarchyKey = PH.CostCenterHierarchyKey
ORDER BY WDH.OrganizationReferenceID
