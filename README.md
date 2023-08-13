## Larawars
***

This project includes pulling data about 'people', 'planets', 'starships' and 'vehicles' resources
from a Star Wars API (https://swapi.dev/documentation#start) and migrating this data to a database.
In this project, some relationships have been added to these resources for the sake of custom scenarios,
which will be explained later. <br/>

There are 4 people each of whom owns a starship and another 4 people each of whom owns a vehicle.
Additionally, there are 7 planets which contain the mine of force.

Data about these resources is accessible via API endpoints.

**Example endpoints:**

**GET  /api/people** -> Paginated data about all people <br/>
**GET  /api/people/1** -> Data of person with id of 1
***
In this project, 3 custom scenarios created around these resources have been added to the Star Wars universe.

**Invasion Scenario** <br/>
According to this scenario, a bunch of people can invade a planet if some necessary conditions are met. <br/>
There should be at least two invaders to start an invasion. <br/>
At least one of the invaders should have a starship and one of them should have a vehicle. <br/>
Already invaded planets can not be invaded again.

Example invasion request: <br/>
```
POST /api/invade
{
    "title": "New Invasion",
    "invaders": [
        "Anakin Skywalker",
        "Darth Vader"
    ],
    "planet": "Alderaan"
}
```

**Exploration Scenario** <br/>
In this scenario, a bunch of people can visit a planet. <br/>
Just like the invasion scenario at least one of the explorers should have a starship.<br/>
If the visited planet is one of the planets which contain the mine of force these explorers gain the ability to feel force.
If the planet is just a regular planet the explorers got nothing.

Example exploration request: <br/>
```
POST /api/explore
{
    "explorers": [
        "Luke Skywalker",
        "Owen Lars"
    ],
    "planet": "Tatooine"
}
```
    

**Immigration Scenario** <br/>
In this last scenario, a pilot and a bunch of people can go to a planet to immigrate there. <br/>
The pilot needs to have a starship. <br/>
People who already immigrated to another planet can not immigrate again. <br/>
If the population of the planet is greater than 2.000.000 the immigrants don't get accepted to the planet.
If not, the immigration successfully takes place and the population of the planet increases by the number of immigrants.

Example exploration request: <br/>
```
POST /api/immigrate
{
    "pilot": "Han Solo",
    "immigrants": [
        "Biggs Darklighter",
        "Wilhuff Tarkin"
    ],
    "planet": "Tatooine"
}
```
