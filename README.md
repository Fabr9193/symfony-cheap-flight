# Cheap- Flight API

##Use 

#### /search GET
e.g : /search?fly_from=PAR&date_from=08/08/2020&adults=1&price_to=90&nights_in_dst_from=5

* fly_from: IATA code (PAR [= Paris], FR [= France]) <br/>
* date_from: date (08/08/2020) <br/>
* adults: int (1)<br/>
* price_to: int (90)<br/>
* nights_in_dst_from: int (5)

Response : 

{
    "total_results": 3,
    "data": [
        {
            "city_from": "Paris",
            "city_to": "Prague",
            "price": 82,
            "route": [
                [
                    "CDG",
                    "PRG"
                ],
                [
                    "PRG",
                    "BVA"
                ]
            ],
            "details": // link to kiwi.com to get more information and book the ticket
        }, ...
        ]


