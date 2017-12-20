# Online Shopping

_(source -> https://github.com/simonrenoult/nodejs-application-architecture)_

Your task is to write an API for an online shopping site.
It may be a web REST API or just a set a code functions / classes.

## Domain

+ `Product` : have an identifier, a name, a price and a weight
+ `Order` : have a status (`pending`, `paid`, `cancelled`), a product list, a shipment amount, a total amount and a weight
+ `Bill` : have an amount and a creation date

## Features

Your API should allow the user to :
+ list, create, find by id, delete a product
+ list, create, find by id, delete an order
+ sort the products by name, price or weight
+ sort the bills

## Business rules

A quick look at the requirements indicated that :
+ A 5 % discount is applied to the `Bill` when the price of the `Order` exceeds 1000€
+ A `Bill` indicates the shipment costs : 25 € for every 10 kg (50 € for 20kg, 75 € for 30kg, etc.)
+ `Bills` are automatically generated when an `Order` status is set to `paid`
+ A `paid` `Order` cannot be deleted
+ An `Order` contains at least 1 `Product` 
