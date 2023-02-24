# Store manager app

This application based on laravel aims to make a simple administration of shops and their products.


## Installation
Download the repository and run composer 

    $ composer install

Once all the dependencies have been downloaded, run **sail** to raise the docker containers with the application.

    ./vendor/bin/sail up


## Application endpoints

### GET /api/stores (get all stores)
**Response**: 200 -> OK, 4xx -> KO

### GET /api/stores/`<store_hash>` (get detailed info about one particular store)
**Response**: 200 -> OK, 422 -> KO

### POST /api/stores/ (create store with and optionally create products)
**Body**:

    {
    	"name": "example",
    	"products": [
    		{
    			"name": "product1",
    			"stock": 4
    		},
    		{
    			"name": "product2",
    			"stock": 15
    		}
    	]
    }

**Response**: 201 -> OK, 422 -> KO

### PUT /api/stores/`<store_hash>` (update store with and optionally update products)
**Body**:

    {
    	"name": "example(updated)",
    	"products": [
    		{
    			"product_hash": "bf4d65746eec1473e63c1ab73c981e7d",
    			"name": "product1(updated)"
    		},
    		{
    			"product_hash": "2efa7f3616c3fc84b8bf3c5827c1fab0",
    			"name": "product2(updated)",
    			"stock": 55
    		}
    	]
    }

**Response**: 200 -> OK, 422 -> KO

### DELETE /api/stores/`<store_hash>` (delete one particular store with related products)
**Response**: 200 -> OK, 422 -> KO


### POST /api/stores/`<store_hash>`/`<product_hash>` (try to sell a product related to store with a number of item in the process)
**Body**: 

    {
    	"units_to_sell": 5
    }

**Response**: 200 -> OK, 422 -> KO