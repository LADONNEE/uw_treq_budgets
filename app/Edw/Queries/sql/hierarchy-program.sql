SELECT
  CONCAT(WDH.ProgramHierarchyID, '') AS WorkdayCode,
  CONCAT(WDH.ProgramHierarchyName, '') AS HierarchyName,
  CONCAT(PH.ProgramHierarchyID, '') AS ParentWorkdayCode
FROM UWODS.sec.ProgramHierarchy WDH
INNER JOIN (
  SELECT DISTINCT ProgramHierarchyKey AS HiKey
  FROM UWODS.sec.ProgramHierarchy
  WHERE ProgramHierarchyName LIKE '__UWORG_HIERARCHY_LIKE__'
  UNION
  SELECT DISTINCT Parent_ProgramHierarchyKey AS HiKey
  FROM UWODS.sec.ProgramHierarchy
  WHERE ProgramHierarchyName LIKE '__UWORG_HIERARCHY_LIKE__'
) HPOP
  ON WDH.ProgramHierarchyKey = HPOP.HiKey
INNER JOIN UWODS.sec.ProgramHierarchy PH
  ON WDH.Parent_ProgramHierarchyKey = PH.ProgramHierarchyKey
ORDER BY WDH.ProgramHierarchyID
