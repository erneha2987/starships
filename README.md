# Starships

This project displays a table of starships from the Star Wars universe ordered by fastest (descending).

## Table of Contents

* [Installation](#installation)
* [Usage](#usage)
* [API](#api)
* [Contributing](#contributing)
* [Output](#output)

## Installation

1. Clone the repository to your local machine.
2. Open the project directory in a text editor.
3. Make sure that you have a server set up to run the PHP script.
4. In the command line, navigate to the project directory and run the following command to start the server:

   ```php -S localhost:8000```

5. Open a web browser and go to http://localhost:8000 to view the table of starships.

## Usage

The table of starships is displayed on the main page of the project. The starships are ordered by fastest (descending), with the fastest starship listed first. The table includes the following columns:

* Name: The name of the starship
* Model: The model of the starship
* Cargo Capacity: The maximum cargo capacity of the starship
* Slower by: The difference in speed between this starship and the fastest starship in the table
* Pilots: A list of the pilots who have flown this starship
* Crew: The number of crew members required to operate the starship

## API

The data for the starships table is provided by a PHP script, which retrieves the data from the Star Wars API (SWAPI). The PHP script is located in the main.php file in the project directory.

## Contributing

If you would like to contribute to this project, please fork the repository and submit a pull request with your changes.

## Output
![data.png](https://drive.google.com/uc?export=view&id=13kFVJXA_QNyTdIWGpph5oVEE8_loY-_V)