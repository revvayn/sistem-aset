SELECT id, serial_number, status, location, warranty_expiry, updated_at FROM assets WHERE deleted_at IS NULL ORDER BY id;
