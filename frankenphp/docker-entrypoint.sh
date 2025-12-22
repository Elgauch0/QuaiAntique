#!/bin/sh
set -e

if [ "$1" = 'frankenphp' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	echo "🚀 Starting PHP container setup..."
	
	# Toujours installer les dépendances au démarrage
	echo "📦 Installing Composer dependencies..."
	composer install --prefer-dist --no-progress --no-interaction
	
	# Vérifier et installer FrankenPHP runtime si absent
	echo "🔍 Checking FrankenPHP runtime..."
	if ! composer show runtime/frankenphp-symfony >/dev/null 2>&1; then
		echo "⚡ Installing FrankenPHP runtime..."
		composer require runtime/frankenphp-symfony --no-interaction --no-scripts
	else
		echo "✅ FrankenPHP runtime already installed"
	fi
	
	# Configurer Symfony pour Docker
	composer config --json extra.symfony.docker 'true' --no-interaction 2>/dev/null || true

	# Display information about the current project
	echo "📋 Project info:"
	php bin/console -V

	if grep -q ^DATABASE_URL= .env 2>/dev/null; then
		echo '🗄️  Waiting for database to be ready...'
		ATTEMPTS_LEFT_TO_REACH_DATABASE=60
		until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(php bin/console dbal:run-sql -q "SELECT 1" 2>&1); do
			if [ $? -eq 255 ]; then
				ATTEMPTS_LEFT_TO_REACH_DATABASE=0
				break
			fi
			sleep 1
			ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
			echo "⏳ Still waiting for database... $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left."
		done

		if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
			echo '❌ The database is not up or not reachable:'
			echo "$DATABASE_ERROR"
			exit 1
		else
			echo '✅ Database is ready!'
		fi

		if [ "$(find ./migrations -iname '*.php' -print -quit 2>/dev/null)" ]; then
			echo "🔄 Running migrations..."
			php bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing
		fi
	fi

	echo '✅ PHP app ready!'
fi

exec docker-php-entrypoint "$@"