# Manual de Implementación en AWS con RDS y EBS

## Tabla de Contenidos

- [Creación de la Base de Datos en Amazon RDS](#creación-de-la-base-de-datos-en-amazon-rds)
- [Configuración del Backend](#configuración-del-backend)
  - [Modificación del archivo Dockerfile](#modificación-del-archivo-dockerfile)
  - [Configuración de la Base de Datos con HeidiSQL](#configuración-de-la-base-de-datos-con-heidisql)
- [Creación del Entorno de Elastic Beanstalk para el Backend](#creación-del-entorno-de-elastic-beanstalk-para-el-backend)
- [Configuración del Frontend](#configuración-del-frontend)
  - [Modificación del archivo Dockerfile](#modificación-del-archivo-dockerfile-1)
  - [Creación del Entorno de Elastic Beanstalk para el Frontend](#creación-del-entorno-de-elastic-beanstalk-para-el-frontend)
- [Ajustes de Seguridad Finales](#ajustes-de-seguridad-finales)

## Creación de la Base de Datos en Amazon RDS

1. Acceder a la consola de AWS y dirigirse a **Amazon RDS**.
2. Hacer clic en **Crear base de datos**.
3. Seleccionar **Modo de Creación Estándar**.
4. Elegir el motor de base de datos adecuado (por ejemplo, MySQL o PostgreSQL).
5. Configurar los siguientes detalles:
   - **Nombre de la instancia**: Nombre identificador de la base de datos.
   - **Usuario maestro**: Definir el usuario para acceder a la base de datos.
   - **Contraseña maestra**: Asignar una contraseña segura.
6. En la configuración de conectividad:
   - **Permitir acceso público**: **Activar** (esto se desactivará luego por seguridad).
   - **VPC**: Seleccionar la VPC en la que se desplegará el backend y frontend.
   - **Grupo de seguridad**: Permitir conexiones entrantes temporales desde la dirección IP del equipo local.
7. Crear la base de datos y esperar a que AWS asigne la dirección DNS.

## Configuración del Backend

### Modificación del archivo Dockerfile

1. Abrir el archivo `Dockerfile` del backend.
2. Buscar la línea que define `DATABASE_URL`:
   ```Dockerfile
   ENV DATABASE_URL="username:password@dhHost/databaseName"
   ```
3. Reemplazar los valores:
   - **username**: Usuario establecido en RDS.
   - **password**: Contraseña definida en RDS.
   - **dhHost**: Dirección DNS asignada por AWS RDS.
   - **databaseName**: Nombre de la base de datos.

### Configuración de la Base de Datos con HeidiSQL

1. Descargar e instalar **HeidiSQL**.
2. Conectarse a la base de datos con los siguientes datos:
   - **Servidor**: Dirección DNS asignada por RDS.
   - **Usuario**: Usuario definido en RDS.
   - **Contraseña**: Contraseña establecida en RDS.
   - **Base de datos**: Nombre previamente definido.
3. Crear una nueva base de datos dentro de HeidiSQL.
4. Seleccionar la base de datos creada y ejecutar el script `init.sql`.

## Creación del Entorno de Elastic Beanstalk para el Backend

1. Acceder a **Elastic Beanstalk** en AWS.
2. Crear un nuevo entorno de aplicación para el backend.
3. En las configuraciones de red:
   - **Seleccionar la misma VPC utilizada en RDS**.
   - **No asignar dirección IP pública**.
4. Crear un archivo `.zip` del backend, excluyendo `docker-compose.yml`.
5. Subir el archivo comprimido al entorno de Elastic Beanstalk.

## Configuración del Frontend

### Modificación del archivo Dockerfile

1. Abrir el archivo `Dockerfile` del frontend.
2. Buscar la línea donde aparece `backend_domain_here` y reemplazarla con el nombre DNS del backend en EBS.

### Creación del Entorno de Elastic Beanstalk para el Frontend

1. Acceder a **Elastic Beanstalk**.
2. Crear un nuevo entorno para el frontend.
3. Seleccionar la misma VPC utilizada en el backend.
4. **Asignar una dirección IP pública**.
5. Crear un archivo `.zip` del frontend, excluyendo `docker-compose.yml`.
6. Subir el archivo comprimido al entorno de Elastic Beanstalk.

## Ajustes de Seguridad Finales

1. Acceder a la configuración de Amazon RDS.
2. Desactivar el **acceso público** a la base de datos para mayor seguridad.
3. Asegurar que solo los servicios necesarios tengan acceso mediante reglas de seguridad en el **Security Group**.