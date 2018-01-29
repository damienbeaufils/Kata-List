import json
import os
from enum import Enum

from flask import Flask, jsonify, request


class State(Enum):
    dead = ' '
    alive = 'o'


def cell_array_to_grid(cells, width, height):
    grid = [[State.dead for i in range(width)] for j in range(height)]

    for cell in cells:
        x, y = cell['x'], cell['y']
        grid[y][x] = State.alive if cell['alive'] else State.dead

    return grid


def grid_to_cell_array(grid):
    width, height = len(grid[0]), len(grid)
    cells = []

    for y in range(height):
        for x in range(width):
            alive = grid[y][x] == State.alive
            cells.append({'x': x, 'y': y, 'alive': alive})

    return cells


def get_cell_array_dimensions(cells):
    return max([cell['x'] for cell in cells]) + 1, max([cell['y'] for cell in cells]) + 1


def init_grid_from_template(template):
    lines = template.split('\n')
    height = len(lines)
    width = len(lines[0])

    blank_grid = initialize_grid(width, height)

    for y in range(height):
        blank_grid[y] = [State.alive if cell == State.alive.value else State.dead for cell in lines[y]]

    return blank_grid


def initialize_grid(width, height):
    return [[State.dead for i in range(width)] for j in range(height)]


def count_living_neighbours(grid, x, y):
    neighbours = []

    if x > 0:
        neighbours.append(grid[y][x - 1])

    if y > 0:
        neighbours.append(grid[y - 1][x])

    if x > 0 and y > 0:
        neighbours.append(grid[y - 1][x - 1])

    if x > 0 and has_next_row(grid, y):
        neighbours.append(grid[y + 1][x - 1])

    if y > 0 and has_next_column(grid, x, y):
        neighbours.append(grid[y - 1][x + 1])

    if has_next_column(grid, x, y):
        neighbours.append(grid[y][x + 1])

    if has_next_column(grid, x, y) and has_next_row(grid, y):
        neighbours.append(grid[y + 1][x + 1])

    if has_next_row(grid, y):
        neighbours.append(grid[y + 1][x])

    return len(filter_out_dead_cells(neighbours))


def will_stay_alive(grid, x, y):
    living = count_living_neighbours(grid, x, y)

    if living == 3:
        return True

    if living == 2:
        return grid[y][x] == State.alive

    return False


def compute_evolutions(grid):
    width, height = get_grid_dimensions(grid)
    new_grid = initialize_grid(width, height)

    for y in range(height):
        for x in range(width):
            next_state = State.alive if will_stay_alive(grid, x, y) else State.dead
            new_grid[y][x] = next_state

    return new_grid


def has_next_row(grid, y):
    return y < len(grid) - 1


def has_next_column(grid, x, y):
    return x < len(grid[y]) - 1


def filter_out_dead_cells(neighbours):
    return [cell for cell in neighbours if cell == State.alive]


def get_grid_dimensions(grid):
    return len(grid[0]), len(grid)


api = Flask(__name__)


@api.route('/grid', methods=['POST'])
def evolve_grid():
    cells = json.loads(request.get_data(as_text=True))
    width, height = get_cell_array_dimensions(cells)
    grid = cell_array_to_grid(cells, width, height)
    new_grid = compute_evolutions(grid)
    new_cells = grid_to_cell_array(new_grid)
    return jsonify(new_cells)


@api.route('/grid', methods=['GET'])
def get_from_template():
    template = request.args.get('template')
    with open('grids/' + template + '.grid', 'r') as f:
        grid = init_grid_from_template(f.read())
    cells = grid_to_cell_array(grid)
    return jsonify(cells)


if __name__ == '__main__':
    port = int(os.environ.get('PORT', 5000))
    api.run(host='0.0.0.0', port=port)
