WITH cch AS (
  SELECT     CostCenterHierarchy.CostCenterHierarchyKey
  FROM       UWODS.sec.CostCenterHierarchy
  WHERE      OrganizationReferenceID = '__TOP_CCH_WID__'
  UNION ALL
  SELECT     child.CostCenterHierarchyKey
  FROM       UWODS.sec.CostCenterHierarchy child
  INNER JOIN cch
          ON child.Parent_CostCenterHierarchyKey = cch.CostCenterHierarchyKey
)
SELECT
  CONCAT(rw.RelatedWorktagIDType, '') AS DriverWorktagType,
  CONCAT(rw.RelatedWorktagIDValue, '') AS DriverWorktagID,
  CONCAT(rw.RelatedWorktagName, '') AS DriverWorktagName,
  cc.CostCenterReferenceID,
  cc.CostCenterName
FROM cch
INNER JOIN UWODS.sec.CostCenterHierarchyCostCenter cchcc
  ON cchcc.CostCenterHierarchyKey = cch.CostCenterHierarchyKey
INNER JOIN UWODS.sec.CostCenter cc
  ON cc.CostCenterKey = cchcc.CostCenterKey
INNER JOIN UWODS.sec.RelatedWorktagDefaultWorktag AS dw
	ON cc.CostCenterWID = dw.DefaultWorktagWID
INNER JOIN UWODS.sec.RelatedWorktag rw
  ON dw.RelatedWorktagKey = rw.RelatedWorktagKey
ORDER BY dw.DefaultWorktagIDType, dw.DefaultWorktagIDValue, cc.CostCenterReferenceID
