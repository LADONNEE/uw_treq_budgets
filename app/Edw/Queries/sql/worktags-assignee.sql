SELECT
  CONCAT(WDO.OrganizationReferenceID, '') AS WorkdayCode,
  CONCAT(WDO.AssigneeName, '') AS WorktagName,
  CONCAT('', '') AS HierarchyCode
FROM UWODS.sec.AssigneeHierarchy WDH
INNER JOIN (
  SELECT DISTINCT AssigneeHierarchyKey AS HiKey
  FROM UWODS.sec.AssigneeHierarchy
  WHERE AssigneeHierarchyName LIKE '__UWORG_HIERARCHY_LIKE__'
  UNION
  SELECT DISTINCT Parent_AssigneeHierarchyKey AS HiKey
  FROM UWODS.sec.AssigneeHierarchy
  WHERE AssigneeHierarchyName LIKE '__UWORG_HIERARCHY_LIKE__'
) HPOP
  ON WDH.AssigneeHierarchyKey = HPOP.HiKey
INNER JOIN UWODS.sec.AssigneeHierarchyProgram H
  ON WDH.AssigneeHierarchyKey = H.AssigneeHierarchyKey
INNER JOIN UWODS.sec.Program WDO
  ON H.ProgramKey = WDO.ProgramKey
ORDER BY WDO.ProgramID
