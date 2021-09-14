install:
	docker-compose build
	cp .env.example .env
	docker run --rm --tty --interactive -v ${CURDIR}:/app composer install

up:
	docker-compose up -d

down:
	docker-compose down

test:
	docker-compose run --rm app php vendor/bin/phpunit tests --testdox