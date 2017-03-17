# How to install
From the terminal, run `npm install` and all the needed packages will be installed as well as any
of their dependencies. You can look in the `node_modules` folder and see a list of all the packages.
>note: For this to work you have to install __Node.js__

### Bootstrap Sass
Run `gulp` to have all the Bootstrap styles ready to use. (This will also compile all the __.js__ files)
>note: For the command gulp to work you need to have (of course) __gulp__ installed.

[__Docs:__ Elixir ](http://laravel.com/docs/master/elixir "elixir")

You can overwrite all the Bootstrap’s existing variables with `!default`.

To find a list of all the variables you can customize open:
`node_modules/bootstrap-sass/assets/stylesheets/bootstrap/_variables.scss`


### Create tables
Run `php artisan migrate` to create all the tables and colummns.

### Create MQTT server 
Run `docker-compose -d up`
