#!/bin/bash
# start-prod.sh

echo "🚀 Starting PRODUCTION environment..."

# Sprawdź czy to rzeczywiście produkcja
if [ "$NODE_ENV" != "production" ]; then
    export NODE_ENV=production
fi

# Użyj production compose
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml up -d --build

echo "✅ Production started on http://localhost:80"
echo "⚠️  Xdebug DISABLED, display_errors OFF"
