## Commands for use in migrations, run the command in bash in root folder of project.

### in case of file migration.php in root folder, contains other name, insert the param: --configuration=name_file.php 

* generate migration:

```bash 
    
    $ vendor/bin/doctrine-migrations migrations:generate    

```

* list migrates:

```bash 
    
    $ vendor/bin/doctrine-migrations migrations:list  

```

* status migrates:

```bash 
    
    $ vendor/bin/doctrine-migrations migrations:status  

```


* migrate:

```bash 
    
    $ vendor/bin/doctrine-migrations migrate  

```

* specific migrate:

```bash 
    
    $ vendor/bin/doctrine-migrations migrations:migrate <version>

```

* rollback:

```bash 
    
    #examples:

    # Unix systems:
    $ php vendor/bin/doctrine-migrations migrations:execute My\Namespace\Version12345 --down

    # Windows systems:
    $ php vendor/bin/doctrine-migrations migrations:execute My\\Namespace\\Version12345 --down
  

```
