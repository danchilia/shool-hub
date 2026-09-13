-- ============================================================
-- 018: Class Teacher Role Permissions
-- Grants the correct permissions to the "Class Teacher" role.
-- Uses subqueries so it works regardless of the role's auto-ID.
-- ============================================================

SET @role_id = (SELECT id FROM roles WHERE name = 'Class Teacher' LIMIT 1);

-- Remove any existing permissions for this role (clean slate)
DELETE FROM staff_privileges WHERE role_id = @role_id;

-- ── STUDENT ─────────────────────────────────────────────────
-- Student: View only (can see student list, not add/edit/delete)
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 1, 1, 0, 0, 0);

-- Student Id Card: View only
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 4, 1, 0, 0, 0);

-- Admission Request: View + Add (submit requests, not approve)
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 115, 1, 1, 0, 0);

-- ── ACADEMIC ────────────────────────────────────────────────
-- Classes: View only
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 23, 1, 0, 0, 0);

-- Subject: View only
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 26, 1, 0, 0, 0);

-- Class Timetable: View only
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 29, 1, 0, 0, 0);

-- ── ATTENDANCE ──────────────────────────────────────────────
-- Student Attendance: Add (take attendance)
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 49, 0, 1, 0, 0);

-- Student Attendance Report: View only
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 52, 1, 0, 0, 0);

-- ── EXAM ────────────────────────────────────────────────────
-- Exam Mark: View + Add + Edit (enter and update student marks)
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 38, 1, 1, 1, 0);

-- Report Card: View only
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 78, 1, 0, 0, 0);

-- ── HOMEWORK ────────────────────────────────────────────────
-- Homework: View + Add + Edit
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 32, 1, 1, 1, 0);

-- Homework Evaluate: View + Add
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 88, 1, 1, 0, 0);

-- ── DASHBOARD WIDGETS ───────────────────────────────────────
-- Student Count Widget
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 94, 1, 0, 0, 0);

-- Student Quantity Pie Chart
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 97, 1, 0, 0, 0);

-- Weekend Attendance Inspection Chart
INSERT INTO staff_privileges (role_id, permission_id, is_view, is_add, is_edit, is_delete)
VALUES (@role_id, 98, 1, 0, 0, 0);

SELECT CONCAT('Class Teacher permissions applied. Role ID = ', @role_id) AS result;
