
## Database

Through the database it is possible to manage tables from the provided 
connections. You can create, update and delete tables. Note the changes are
reflected to the database on save so please be cautious with your actions.

### Types

Fusio uses internally the doctrine DBAL library to create database vendor 
compatible SQL. Take a look at the [mapping] matrix for more detailed 
informations about the mapping from the type to a concrete database type.

[mapping]: http://doctrine-orm.readthedocs.io/projects/doctrine-dbal/en/latest/reference/types.html#mapping-matrix
