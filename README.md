# bap_ecom

This example is the implementation of the test task of calculating due date with a given start datetime and a duration. If the resulting time is outside of business hours or is on weekend, the calculation should act accordingly and skip to next business day.

The solution contains an API and a CLI interface where you can try the functionality.

## Installation

To install this project, follow these steps:

- Clone the repository to your local machine
- Install the required dependencies by running and composer install
- You can immediately use the CLI
- In order to use the API, choose 1 of the following steps:
	- Run as a container: `docker compose up -d`
	- Run as local service: ```php artisan serve```

## Usage
To use this project, follow these steps:

### Use the API

Run requests in the below format:
```
localhost:8000/api/localhost:8000/api/due-date?submit_date=2023-08-28%2013:15&turnaround_time=35.7
```
You can adjust the url according to your environment.

### Use the CLI

```sh
$ php artisan calculate:due-date "2023-08-28 13:15" 12
Due date: 2023-08-30 09:15

$ php artisan calculate:due-date "2023-08-28 13:15" 27.7
Due date: 2023-08-31 16:56

$ php artisan calculate:due-date "2023-08-28 13:15" 27.8
Due date: 2023-09-01 09:03

$ php artisan calculate:due-date "2023-08-28 13:15" 35.7
Due date: 2023-09-01 16:57

$ php artisan calculate:due-date "2023-08-28 13:15" 35.8
Due date: 2023-09-04 09:02

$ php artisan calculate:due-date "2023-08-28 13:15" -1
The "-1" option does not exist.

$ php artisan calculate:due-date "2023-08-28 13:15" asd
Error: Invalid turnaround time. It should be greater than zero.

$ php artisan calculate:due-date "2023-08-28 13:15" 0
Error: Invalid turnaround time. It should be greater than zero.
```

### Credits

This project was created by Sándor Miglécz.