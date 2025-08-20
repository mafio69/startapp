'EOF'
#!/bin/bash

echo "🚀 Starting PHP Development Environment..."
echo "Building and starting containers..."

docker-compose -f docker-compose.dev.yml build --no-cache
docker-compose -f docker-compose.dev.yml up

echo "Development environment ready!"
echo "🌐 Application: http://localhost:8080"
echo "🐛 Xdebug: localhost:9003"
EOF