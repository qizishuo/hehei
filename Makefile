.PHONY: logs php database redis migrate reload deploy

logs:
	docker-compose logs -f php

php:
	docker-compose exec php sh

migrate:
	docker-compose exec php php artisan migrate

repl:
	docker-compose exec php php artisan tinker

test:
	docker-compose exec php vendor/bin/phpunit

reload:
	git pull origin master
	-docker-compose exec php composer install --optimize-autoloader --no-dev
	-docker-compose exec php php artisan migrate --force
	-docker-compose exec php php artisan config:cache
	-docker-compose exec php php artisan route:cache
	chown -R www-data:www-data .

deploy:
	ssh -tA root@www.dayouqifu.com "cd dayou && make reload"

reload-testing:
	-docker-compose exec php composer install
	-docker-compose exec php php artisan migrate
	chown -R www-data:www-data .

testing:
	rsync -r [!.]* --exclude={node_modules,vendor,.DS_Store} root@testing.dayouqifu.com:~/dayou
	ssh -tA root@testing.dayouqifu.com "cd dayou && make reload-testing"
