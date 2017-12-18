# EloChart

Printing the chart
------------------

I can display the chart with all players (Player Name, Elo) sorted by decreasing Elo rank.

Adding a new player
-------------------
I can add a new player (Player Name). 
When I display the chart, the new player is displayed, with Elo rank = 1500.
The Player Name must not exist already in the chart.

Recording a match
-----------------
I can record a match between two players.
When I display the chart, the Elo rank of each player has been recalculated.
I cannot record a match between a player and herself.
I cannot record a match between players that are not in the chart.

Rules for calculating Elo rank of a player
------------------------------------------

    En+1 = En + Kn x (W - p(D))

    D = En - F
    F = Elo rank of opponent
    p(D) = 1 / (1 + 10^(-D/400) )

    K = 40 if n ≤ 30
    K = 20 if En < 2400
    K = 10 if EN ≥ 2400

    W = 1 if player won
    W = 0.5 if equality
    W = 0 if player lost

 

