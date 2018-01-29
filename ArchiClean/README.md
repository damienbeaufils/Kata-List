# Archi Clean

A kata made for learning the basics of clean / hexagonal architecture. 

## Starter code

In its initial form, the software consists of a set of functions used by a web API.

The API offers two features :
+ getting a grid by its name (see `grids/`)
+ posting a grid and returning its evolved form

See [wikipedia](https://en.wikipedia.org/wiki/Conway%27s_Game_of_Life) for a detailed description of the game of life.

## Goal

Your goal is to improve the design of the software following the principles of either _clean_ or _hexagonal_ architecture.

## Rules

+ You should not have to modify the tests : there are provided as regression tests, not structural tests
+ You may either refactor the code going "outside-in" or "inside-out", but try and stick to a single approach
+ Your naming should reflect the business domain
+ Dependencies only go inward ! The domain part does not depend on anything
+ SOLID principles apply