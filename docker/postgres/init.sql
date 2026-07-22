-- Extensiones útiles para el futuro (generación de UUIDs/hashes a nivel de BD,
-- por si en Fase 2 necesitas funciones criptográficas para hashes de comprobantes)
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
