<h1 align="center">Composer script collection</h1>

Custom [Composer](https://getcomposer.org/) scripts for WordPress Project Template.

## Installation

Install this extension through [Composer](https://getcomposer.org/).

Either run

    php composer.phar require --prefer-dist studio-98/wp-composer-scripts "*"
    
or add to the require section:

    "studio-89/wp-composer-scripts": "*"

After that add the following lines to the scripts section:

    "scripts": {
        "wp:salts": "Studio89\\WP\\Composer\\Environment::generateSalts",
        "wp:dbPrefix": "Studio89\\WP\\Composer\\Environment::generateDbPrefix"
    }
    
## Available scripts

### wp:salts

Generate WordPress salts inside the `.env` file.<br>
Useful if you want to disable all old open sessions / cookies.

### wp:dbPrefix

Create unique db prefix (to improve security) and replace it inside the `.env` file.<br>
By default this script should be added to create-project command hooks.