/*
 * Change budget and contact FK to restrict on allocations tables
 * Change applied on production 7/15/2021 - hanisko
 */

ALTER TABLE allocations
DROP FOREIGN KEY allocations_budget_id_foreign;

ALTER TABLE allocations
ADD CONSTRAINT allocations_budget_id_foreign
  FOREIGN KEY (budget_id) REFERENCES budgets(id) ON DELETE RESTRICT;


ALTER TABLE allocations
DROP FOREIGN KEY allocations_faculty_contact_id_foreign;

ALTER TABLE allocations
ADD CONSTRAINT allocations_faculty_contact_id_foreign
  FOREIGN KEY (faculty_contact_id) REFERENCES contacts(id) ON DELETE RESTRICT;


ALTER TABLE effort_report_allocations
DROP FOREIGN KEY effort_report_allocations_budget_id_foreign;

ALTER TABLE effort_report_allocations
ADD CONSTRAINT effort_report_allocations_budget_id_foreign
  FOREIGN KEY (budget_id) REFERENCES budgets(id) ON DELETE RESTRICT;
