# Rest Countries Search Engine

> Searches the Rest Country database found at https://www.restcountries.eu/

### Dependancies

All PHP packages required for [Laravel](https://laravel.com/docs/5.7)

### Running the server

- To run on a local machine, from the project root directory issue the following command:

```shell
$ php artisan serve
```
- The page will then be available at [http://localhost:8000](localhost:8000)

### Optional Database Cache

To cache the dataset to the server: 
- Create a MySQL database to contain the data.
- Create an .env file by copying .env.example.
- Populate your .env file with the necessary MySQL connection data as indicated [here](https://laravel.com/docs/5.7/database).)
- From the project root directory, issue the following command:

```shell
$ php artisan migrate && php artisan db:seed
```