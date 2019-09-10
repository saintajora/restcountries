# Repository Title Goes Here

> Searches the Rest Country database found at https://www.restcountries.eu/

### Dependancies

All PHP packages required for <a href="https://laravel.com/docs/5.7">Laravel</a>

### Running the server

- To run on a local machine, from the project root directory issue the following command:

```shell
$ php artisan serve

- The page will then be available at <a href="localhost:8000">http://localhost:8000</a>

### Optional Database Cache

To cache the dataset to the server: 
- Create a MySQL database to contain the data.
- Create an .env file by copying .env.example.
- Populate your .env file with the necessary MySQL connection data (as indicated <a href="https://laravel.com/docs/5.7/database">here</a>.)
- From the project root directory, issue the following command:

```shell
$ php artisan migrate && php artisan db:seed
