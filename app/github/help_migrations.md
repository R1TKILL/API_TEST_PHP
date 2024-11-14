## Commands for use in migrations, run the command in bash in root folder of project.

* generate migration:

```bash 
    
    $ vendor/bin/doctrine-migrations generate --configuration=migrations-config.php    

```

* list migrates:

```bash 
    
    $ vendor/bin/doctrine-migrations status --configuration=migrations-config.php --db-configuration=app/Configs/Database/Connection connection.php   

```

* In first migration use:

```bash 
    
    $ vendor/bin/doctrine-migrations migrate --configuration=migrations-config.php   

```

* migrate:

```bash 
    
    $ vendor/bin/doctrine-migrations migrate <version> --configuration=migrations-config.php --db-configuration=app/Configs/Database/Connection/connection.php    

```

* rollback:

```bash 
    
    $ vendor/bin/doctrine-migrations rollback <version> --configuration=migrations-config.php --db-configuration=app/Configs/Database/Connection/connection.php    

```
