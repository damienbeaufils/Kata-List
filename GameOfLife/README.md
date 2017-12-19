# Conway's Game of Life

_(source -> http://codingdojo.org/kata/GameOfLife/)_

You start with a two dimensional grid of cells, where each cell is either alive or dead. In this version of the problem, the grid is finite, and no life can exist off the edges. When calcuating the next generation of the grid, follow these rules:

+ Any live cell with fewer than two live neighbours dies, as if caused by underpopulation.
+ Any live cell with more than three live neighbours dies, as if by overcrowding.
+ Any live cell with two or three live neighbours lives on to the next generation.
+ Any dead cell with exactly three live neighbours becomes a live cell.

You should write a program that can accept an arbitrary grid of cells, and will output a similar grid showing the next generation.