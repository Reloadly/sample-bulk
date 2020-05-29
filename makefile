
pink:=$(shell tput setaf 200)
blue:=$(shell tput setaf 27)
green:=$(shell tput setaf 118)
violet:=$(shell tput setaf 057)
reset:=$(shell tput sgr0)

ifeq ($(shell uname),Darwin)
  os=darwin
else
  os=linux
endif

install:
	$(info $(pink)------------------------------------------------------)
	$(info $(pink)Make ($(os)): Installing Reloadly Bulk Project...)
	$(info $(pink)------------------------------------------------------$(reset))
	@docker-compose build
	@docker-compose up -d
	@docker exec -it rbp_php cp .env.example .env
	@docker exec -it rbp_php composer install -vvv
	@docker exec -it rbp_php php artisan migrate
	@docker exec -it rbp_php php artisan db:seed
	@docker exec -it rbp_php php artisan sync:countries
	@docker-compose down
	@make -s start
start:
	$(info $(pink) Make ($(os)): Starting Reloadly Bulk Project.)
	@docker-compose up -d
	@docker exec -it rbp_php chmod -R 777 storage/
	@docker exec -it rbp_php chmod -R 777 bootstrap/
	@docker exec -it rbp_php service cron start
stop:
	$(info $(pink) Make ($(os)): Stopping Reloadly Bulk Project.)
	@docker-compose down

restart:
	$(info $(pink)Make ($(os)): Restarting Reloadly Bulk Project.)
	@make -s stop
	@make -s start
