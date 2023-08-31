SELECT
  CONCAT(WDO.ProgramID, '') AS WorkdayCode,
  CONCAT(WDO.ProgramName, '') AS WorktagName,
  CONCAT(WDH.ProgramHierarchyID, '') AS HierarchyCode
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
INNER JOIN UWODS.sec.ProgramHierarchyProgram H
  ON WDH.ProgramHierarchyKey = H.ProgramHierarchyKey
INNER JOIN UWODS.sec.Program WDO
  ON H.ProgramKey = WDO.ProgramKey
ORDER BY WDO.ProgramID
