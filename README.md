# jodermo/php-test-server

##### *© 2023 - Moritz Petzka - [petzka.com](https://petzka.com/)*

## Very Simple PHP Test Server 
### With Docker, NGINX and Composer

#### Host your PHP code with Docker to: [localhost:8080](http://localhost:8080)

#### Or drop it to your Apache live server

Simple copy all Content from [/src/](./src/) to your server Apache server.
*(Note: The project has to be started/build with docker before vendor data is available)*

##### Example URL: [https://php-test.dont-use.com/](https://php-test.dont-use.com/)


##### Includes example code for  HTML, CSS and JavaScript, PHP Class and Mollie Payment:


- HTML index file: [src/index.php](./src/index.php) 

- CSS file: [src/css/styles.css](./src/css/styles.css) 

- JavaScript file [src/js/app.js](./src/js/app.js) 

- Global PHP App Class: [src/PHPTest/App.php](./src/PHPTest/App.php) 

- Mollie Payment: [src/PHPTest/Service/PayToMollie.php](./src/PHPTest/Service/PayToMollie.php) 


<br>
<br>

# Get Started

<br>

## First create the `src/environment.json` file with following code and change needed parameters:

Example Code:
```

 /** for live: change the attribute for "domain" to your domain from your live server */
{
    "domain" : "http://localhost:8080",
    "email":"<email address for system mails>",

    "title":"PHP Test",
    "description":"test your php application",
    "keywords":"PHP, test",
    "author":"Moritz Petzka",

    "mollieApiKey":"test_XXX",
    "mollieAccessKey":"access_XXX",

    "mollieAccountId":"XXX",
    "mollieProfileId":"pfl_XXX",
    "mollieOrganisationId":"org_XXX",

    "molliePartnerProfileId":"pfl_XXX",
    "molliePartnerOrganisationId":"org_XXX",

    "mollieRedirectUrl":"https://<your server uri>/?sucess=1",
    "mollieWebhookUrl":"https://<your server uri>/"
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



