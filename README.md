ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

INSTALLATION
============

Clone this project
```
git clone https://github.com/lew13010/OC-PP-P7-BileMo.git
```

Install composer
```
$ php composer install
```

#### DATABASE

Create database
```
$ php bin/console doctrine:database:create
```

Update Table 
```
$ php bin/console doctrine:schema:update --force
```

TEST
====

you can install phpunit for unit and functionnal tests

in security.yml add firewalls before main :
```
test:
    http_basic: ~
```

USE
===

In first create a user for view list of task and add/edit/delete a task.

Only the owner of a task can delete it.

Only a user with role admin can edit other user and can delete a task without owner.