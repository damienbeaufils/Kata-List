<?php

use \Slim\App;

class State {
    public static $dead = '-';
    public static $alive = 'o';
}

// Global
$app = null;

// Encapsulated for test purpose
function initApp()
{
    global $app;
    $app = new App([
        'settings' => [
            'displayErrorDetails' => true
        ]
    ]);
    
    $app->post('/grid',
        function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, array $args) {
            $body = $request->getParsedBody();
    
            $cells = \json_decode($body['data'], true);
            list($width, $height) = get_cell_array_dimensions($cells);
    
            $grid = cell_array_to_grid($cells, $width, $height);
            $new_grid = compute_evolution($grid);
            $new_cells = grid_to_cell_array($new_grid);
        
            $response->getBody()->write(json_encode($new_cells));
            return $response;
        }
    );
    
    $app->get('/grid',
        function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, array $args) {   
            $params = $request->getQueryParams();
            $template = $params['template'];
    
            
            $gridAsString = file_get_contents('./grids/'.$template.'.grid');
            $grid = init_grid_from_template($gridAsString);
            $cells = grid_to_cell_array($grid);
    
            $response->getBody()->write(json_encode($cells));
            return $response;
        }
    );
}

function cell_array_to_grid(array $cells, int $width, int $height) {
    $grid = [];
    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $grid[$y][$x] = State::$dead;
        }
    }

    foreach ($cells as $cell) {
        $grid[$cell['y']][$cell['x']] = ($cell['alive'] ? State::$alive : State::$dead);
    }
    return $grid;
}


function grid_to_cell_array(array $grid) {
    $cells = [];
    $y = 0;
    foreach ($grid as $row) {
        $x = 0;
        foreach ($row as $cell) {
            $alive = ($grid[$y][$x] == State::$alive);
            $cells[] = array('x' => $x, 'y' => $y, 'alive' => $alive);
            $x++;
        }
        $y++;
    }
    return $cells;
}

function get_cell_array_dimensions(array $cells) {
    $maxX = $cells[0]['x'];
    $maxY = $cells[0]['y'];
    foreach ($cells as $cell) {
        if ($cell['x'] > $maxX) {
            $maxX = $cell['x'];
        }
        if ($cell['y'] > $maxY) {
            $maxY = $cell['y'];
        }
    }
    return [$maxX + 1, $maxY + 1];
}

function initialize_grid(int $width, int $height) {
    $grid = [];
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
            $grid[$y][$x] = State::$dead;
        }
    }
    return $grid;
}

function init_grid_from_template(string $template) {
    $lines = explode(PHP_EOL, $template);
    $height = count($lines);
    $width = strlen($lines[0]);

    $grid = initialize_grid($width, $height);

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $grid[$y][$x] = ($lines[$y][$x] == State::$alive ? State::$alive : State::$dead);
        }       
    }

    return $grid;
}

function has_next_row($grid, $y) {
    return $y < (count($grid) - 1);
}

function has_next_column($grid, $x, $y)
{
    return $x < (count($grid[$y]) - 1);
}

function filter_out_dead_cells($neighbours) {
    return array_filter($neighbours, function ($value) {return $value == State::$alive;});
}

function count_living_neighbours(array $grid, int $x, int $y) {
    $neighbours = [];

    if ($x > 0) {
        $neighbours[] = $grid[$y][$x - 1];
    }
    if ($y > 0) {
        $neighbours[] = $grid[$y - 1][$x];
    }  
    if ($x > 0 && $y > 0) {
        $neighbours[] = $grid[$y - 1][$x - 1];
    }
    if ($x > 0 && has_next_row($grid, $y)) {
        $neighbours[] = $grid[$y + 1][$x - 1];
    }
    if ($y > 0 && has_next_column($grid, $x, $y)) {
        $neighbours[] = $grid[$y - 1][$x + 1];
    }
    if (has_next_column($grid, $x, $y)) {
        $neighbours[] = $grid[$y][$x + 1];
    }
    if (has_next_column($grid, $x, $y) && has_next_row($grid, $y)) {
        $neighbours[] = $grid[$y + 1][$x + 1];
    }
    if (has_next_row($grid, $y)) {
        $neighbours[] = $grid[$y + 1][$x];
    }
    return count(filter_out_dead_cells($neighbours));
}

function will_stay_alive(array $grid, $x, $y) {
    $living = count_living_neighbours($grid, $x, $y);

    if ($living == 3) {
        return true;
    }

    if ($living == 2) {
        return $grid[$y][$x] == State::$alive;
    }

    return false;
}

function get_grid_dimensions(array $grid) {
    return [count($grid[0]), count($grid)];
}

function compute_evolution(array $grid) {
    $dimensions = get_grid_dimensions($grid);
    $width = $dimensions[0];
    $height = $dimensions[1];

    $new_grid = initialize_grid($width, $height);

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $next_state = State::$dead;
            if (will_stay_alive($grid, $x, $y)) {
                $next_state = State::$alive;
            }
            $new_grid[$y][$x] = $next_state;
        }
    }
    return $new_grid;
}