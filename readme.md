# Simple Laravel Blog API

# Installation

- Clone the repo on your local machine
```git clone https://github.com/tania-pets/larablog.git```
- Move to project's directory
```cd larablog```
- Install with composer
```composer install```
- copy .env_docker  to .env and update all MAIL_* envs to match your smtp credentials and ADMIN_MAIL to your email.
- Built the docker's composer
```docker-compose build```
- Launch the docker app
```docker-compose up -d```

You should now be able to see laravel's welcome page at http://localhost:8080/ and access docker's mysql with ```mysql -u homestead -P 3307 -h 127.0.0.1 -p secret``` (pass: 'secret')

- Create database
```docker-compose exec app php artisan migrate```
- Start listening the queue
```docker-compose exec app php artisan queue:work --tries=1```
- Seed database with dummy data
```docker-compose exec app php artisan db:seed```
- Genarate Swagger API documentation
```docker-compose exec app php artisan l5-swagger:generate && docker-compose exec app php artisan l5-swagger:publish```

You should be able to view API's documentation and test here: http://localhost:8080/api/documentation


** NOTE: Uses port 8080 for apache and 3307 for mysql. If ports not available,  modify them in docker-compose.yml
