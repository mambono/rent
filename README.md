Prerequisite

Please ensure you have XAMPP with Apache and MYSQL Database up and running. You can also use any other PHP application server such as NGINX to run project.
Steps to install

    Clone or download this repository:

git clone https://github.com/mambono/bikerentals.git

    Ensure this project is in the root directory of the application you are using eg for xampp, it would usually be htdocs

C:\xampp\htdocs\bikerentals

    Install dependencies (Please ensure you have composer installed on your device before executing the following command)

composer install

    Genereate encryption key

php artisan key:generate

    Create database called crud-project

    Import/Dump the database

Import/Dump `bikerentals.sql` found inside app\database\dump directory in this repo

    Database credentials

Ensure you update the database credentails inside .env.example file if you are using credentails other than the default ones. Rename .env.example to .env

The default values for the Database credentials are
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bike_rental
DB_USERNAME=biker
DB_PASSWORD=R3ntal


Test Web App

    Navigate to http://localhost/bikerentals/public/

This will display the dashboard with the projects. You can now navigate through the different menu items

Test RESTful APIs

    GET http://localhost/bikerentals/public/api/bookings/all

This should return a json object of all projects

    GET http://localhost/bikerentals/public/api/bookings/ZuzuBikeshop

This should return a json object of all projects in Kenya

    GET http://localhost/bikerentals/public/api/bookings/ZuzuBikeshop

This should return a json object of all completed projects
