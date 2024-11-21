## Commands for use in migrations, run the command in bash in root folder of project.

### in case of file migration.php in root folder, contains other name, insert the param: --configuration=name_file.php 

* generate migration:

```bash 
    
    $ vendor/bin/doctrine-migrations generate    

```

* list migrates:

```bash 
    
    $ vendor/bin/doctrine-migrations status  

```

* In first migration use:

```bash 
    
    $ vendor/bin/doctrine-migrations migrate  

```

* In the next migrates:

```bash 
    
    $ vendor/bin/doctrine-migrations migrate <version>

```

* rollback:

```bash 
    
    $ vendor/bin/doctrine-migrations rollback <version>  

```
