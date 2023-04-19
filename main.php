<?php
/**
 * The Starship class represents a starship from the Star Wars universe.
 * It includes properties such as name, model, cargo capacity, max atmospheric speed, pilots, and crew size.
 * The class also provides getter methods for accessing these properties.
 * 
 * The script fetches data from the Star Wars API (swapi.dev), creates Starship objects, and sorts them by max atmospheric speed in descending order.
 * It then displays the starships in an HTML table, along with their properties and how much slower they are compared to the fastest starship.
 */

class Starship {
    private  $name;
    private  $model;
    private  $cargo_capacity;
    private  $max_atmosphering_speed;
    private  $pilots;
    private  $crew;

    /**
     * Constructs a Starship object with the given parameters.
     *
     * @param string $name Name of the starship
     * @param string $model Model of the starship
     * @param int $cargo_capacity Cargo capacity of the starship
     * @param int $max_atmosphering_speed Maximum atmospheric speed of the starship
     * @param array $pilots Array of pilots associated with the starship
     * @param int $crew Size of the crew on the starship
     */
    public function __construct($name, $model, $cargo_capacity, $max_atmosphering_speed, $pilots, $crew) {     $this->name = $name;
        $this->model = $model;
        $this->cargo_capacity = $cargo_capacity;
        $this->max_atmosphering_speed = $max_atmosphering_speed;
        $this->pilots = $pilots;
        $this->crew = $crew;
    }

    // Getter methods for accessing the properties of the Starship class

    public function getName() {
        return $this->name;
    }

    public function getModel() {
        return $this->model;
    }

    public function getCargoCapacity() {
        return $this->cargo_capacity;
    }

    public function getMaxAtmospheringSpeed() {
        return $this->max_atmosphering_speed;
    }

    public function getPilots() {
        return $this->pilots;
    }

    public function getCrew() {
        return $this->crew;
    }
}

/**
 * The fetch_data function fetches data from the specified URL using cURL and returns the response as an associative array.
 * It is used in this code to fetch data from the Star Wars API (swapi.dev) to obtain information about starships and their pilots.
 * The function makes an HTTP GET request to the specified URL and retrieves the JSON response. It then decodes the JSON data into an associative array.
 *
 * @param string $url The URL to fetch data from
 * @return array|null The fetched data as an associative array or null if the request fails
 */
function fetch_data(string $url): ?array {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}

// Fetch the first 15 starships
$starships = [];
$url = "https://swapi.dev/api/starships/";

while (count($starships) < 15 && !empty($url)) {
    $response = fetch_data($url);
    $url = $response['next'];

    // Iterate through the starships data in the API response
    foreach ($response['results'] as $starship_data) {
        // Fetch pilot data for each starship
        $pilots_data = [];
        foreach ($starship_data['pilots'] as $pilot_url) {
            $pilot_data = fetch_data($pilot_url);
            $pilots_data[] = $pilot_data;
        }

        // Create a Starship object using the fetched data
        $starship = new Starship(
            $starship_data['name'],
            $starship_data['model'],
            $starship_data['cargo_capacity'],
            $starship_data['max_atmosphering_speed'],
            $pilots_data,
            $starship_data['crew']
        );

        $starships[] = $starship;

        // Stop fetching starships once we have 15 of them
        if (count($starships) >= 15) {
            break;
        }
    }
}

// Sort starships by max_atmosphering_speed in descending order using a custom comparison function
usort($starships, function ($a, $b) {
    return $b->getMaxAtmospheringSpeed() - $a->getMaxAtmospheringSpeed();
});

// Get the max_atmosphering_speed of the fastest starship
$fastest_speed = $starships[0]->getMaxAtmospheringSpeed();

// Generate the table rows as a string
$tableRows = "";

foreach ($starships as $starship) {
    $slower_by = (($fastest_speed - $starship->getMaxAtmospheringSpeed()) / $fastest_speed) * 100;

    $tableRows .= "<tr>";
    $tableRows .= "<td>" . ($starship->getName() ?: '-') . "</td>";
    $tableRows .= "<td>" . ($starship->getModel() ?: '-') . "</td>";
    $tableRows .= "<td>" . ($starship->getCargoCapacity() ?: '-') . "</td>";
    $tableRows .= "<td>" . number_format($slower_by, 2) . "%</td>";

    $tableRows .= "<td>";
    $pilots = $starship->getPilots();
    if (!empty($pilots)) {
        foreach ($pilots as $pilot) {
            $tableRows .= "Name: " . $pilot['name'] . ", Height: " . $pilot['height'] . "<br>";
        }
    } else {
        $tableRows .= "N/A";
    }
    $tableRows .= "</td>";

    $tableRows .= "<td>" . ($starship->getCrew() ?: '-') . "</td>";
    $tableRows .= "</tr>";
}

// Output the table rows as a JSON object
header('Content-Type: application/json');
echo json_encode($tableRows);
