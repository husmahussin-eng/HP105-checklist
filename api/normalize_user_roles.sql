-- Normalize user roles: Convert all 'user' roles to 'admin'
-- Only one 'super_admin' should exist, all others should be 'admin'

-- Update all users with role 'user' to 'admin'
UPDATE users SET role = 'admin' WHERE role = 'user';

-- Ensure only one super_admin exists (keep the first one, change others to admin)
-- This query will keep the first super_admin (lowest ID) and change others to admin
UPDATE users u1
SET role = 'admin'
WHERE u1.role = 'super_admin'
AND u1.id > (
    SELECT MIN(id) FROM users u2 WHERE u2.role = 'super_admin'
);

