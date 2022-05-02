Task about a weather api.
The DB structure contains a table for cities for weather_data and for personal_access_tokens, along with default laravel tables like migrations. 
The cities and weather_data tables are connected by a foreign key inside the weather_data table city_id
The code contains two cli commands, one is for storing weather data into the weather_date table for a specific city, that is scheduled to run on every hour.
The data is collected from an open source weather API called open weather map.
For the cities table a seeder is provided (CitiesSeeder) to populate the table with some cities (approximately 40).
The second cli (artisan) command is for retrieving information for a specific city that is passed in the terminal.
Along side this there are controllers and models for both the tables that enable the user to perform simple CRUD operations.
A loging system is created for this application, and all the api routes are protected by a token. I used the pre-build laravel system for loging and identifying users (laravel sancthum), so that certain routes are not reachable if not logged in. 
Tests are also created for this application.

commands to run to get you started:
php artisan migrate
phpa artisan db:seed --class=CitiesSeeder

cli command for storing weather data:
php artisan weather:consume

cli command or retrieving city information:
php artisan city:info --cityName

