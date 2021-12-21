/*
 * Needed for initial deployment of Effort project
 * Adds budget:fiscal to managers and reconcilers since these became add-on permissions rather than base roles
 */
USE budgets;
INSERT into roles (uwnetid, role, created_at, updated_at)
SELECT uwnetid, 'budget:fiscal', now(), now() FROM roles r1
WHERE role = 'budget:manager'
  AND NOT EXISTS (
        SELECT 1
        FROM roles r2
        WHERE r1.uwnetid = r2.uwnetid
          AND role = 'budget:fiscal'
    );

USE budgets;
INSERT into roles (uwnetid, role, created_at, updated_at)
SELECT uwnetid, 'budget:fiscal', now(), now() FROM roles r1
WHERE role = 'budget:reconciler'
  AND NOT EXISTS (
        SELECT 1
        FROM roles r2
        WHERE r1.uwnetid = r2.uwnetid
          AND role = 'budget:fiscal'
    );
