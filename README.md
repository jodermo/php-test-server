# jodermo/php-test-server

##### *© 2023 - Moritz Petzka - [petzka.com](https://petzka.com/)*

## Very Simple PHP Test Server 
### With Docker, NGINX and Composer

#### Host your PHP code with Docker to: [localhost:8080](http://localhost:8080)

#### Or drop it to your Apache live server

##### Website Example: [https://php-test.dont-use.com/](https://php-test.dont-use.com/)


##### Includes example code for HTML, CSS and JavaScript
##### + PHP class for Mollie payment
##### + PHP class for end to end encrypted chat

- HTML/PHP index file: [src/index.php](./src/index.php) 

- CSS Styles: [src/css/styles.css](./src/css/styles.css) 

- JavaScript Code [src/js/app.js](./src/js/app.js) 

- Global PHP App Class: [src/App/App.php](./src/PHPTest/App.php) 

- Mollie Payment: [src/App/Modules/Mollie](./src/App/Modules/Mollie/) 

- Encyptet Chat: [src/App/Modules/Chat](./src/App/Modules/Chat/) 

<br>
<br>

# Get Started

<br>

## First edit the environment file `src/environment.json`

Example Code:
```

 /** for live: change the attribute for "domain" to your domain from your live server */
{
    "domain" : "https://php-test.dont-use.com/",
    "email":"php-test-app@petzka.com",
    "title":"PHP Test",
    "description":"test your php application",
    "keywords":"PHP, test",
    "author":"Moritz Petzka",
    "redirectUrl":"https://php-test.dont-use.com/?sucess=1",
    "webhookUrl":"https://php-test.dont-use.com/"
}
```

<br>

#### Start Docker Container:
```
docker-compose up
```

#### Rebuild Docker Container:
```
docker-compose up --build
```



# Use it live

##### Simple copy all Content from [/src/](./src/) to your server Apache server.
*(Note: The project has to be started/build with docker before vendor data is available)*