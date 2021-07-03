## **Instructions to build and run the project:**

 1. Go to "backend" folder and run this commands one after another:
 - docker-compose up -d (the app is hosted on localhost:80)
- docker-compose exec app php artisan key:generate
- docker-compose exec app php artisan config:cache
- docker-compose exec db bash
- mysql -u root -p //password is root
- GRANT ALL ON laravel.* TO 'laraveluser'@'%' IDENTIFIED BY 'your_laravel_db_password
- FLUSH PRIVILEGES;
- EXIT;
 - exit
- docker-compose exec app php artisan migrate
- docker-compose exec app php artisan db:seed

2. Go to "frontend" folder and run this commands one after another: (the vue app is hosted on localhost:8080)
- docker build -t vuejs-cookbook/dockerize-vuejs-app .
- docker run -it -p 8080:80 --rm --name dockerize-vuejs-app-1 vuejs-cookbook/dockerize-vuejs-app
