up-all:
	docker-compose up --build
run-debug:
	docker-compose run -T telegram-notes-workspace php debug.php
run-shell:
	docker-compose run -T telegram-notes-workspace bash
run-long-polling:
	docker-compose run -T telegram-notes-workspace php long-polling.php
	#docker-compose run -T php-telegram-notes-fpm php long-polling.php