#!/bin/bash

# Kolory dla lepszej czytelności
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Starting PHP Development Environment...${NC}"
echo "Building and starting containers..."

# Sprawdź czy docker-compose.dev.yml istnieje
if [ ! -f "docker-compose.dev.yml" ]; then
  echo -e "${RED}❌ Error: docker-compose.dev.yml not found!${NC}"
  exit 1
fi

# Build z obsługą błędów
if ! docker-compose -f docker-compose.dev.yml build --no-cache; then
  echo -e "${RED}❌ Build failed!${NC}"
  exit 1
fi

# Start kontenerów z obsługą błędów
if ! docker-compose -f docker-compose.dev.yml up -d; then
  echo -e "${RED}❌ Failed to start containers!${NC}"
  exit 1
fi

echo -e "${GREEN}✅ Development environment ready!${NC}"
echo -e "${BLUE}🌐 Application: http://localhost:8080${NC}"
echo -e "${BLUE}🐛 Xdebug: localhost:9003${NC}"

# Pokaż status kontenerów
echo -e "${BLUE}📊 Container status:${NC}"
docker-compose -f docker-compose.dev.yml ps
