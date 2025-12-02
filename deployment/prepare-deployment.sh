#!/bin/bash

# Build assets for production
npm run build

# Create deployment directory
mkdir -p deployment

# Copy necessary files
rsync -av --exclude='node_modules' \
          --exclude='deployment' \
          --exclude='.git' \
          --exclude='.env' \
          --exclude='storage/app/*' \
          --exclude='storage/framework/cache/*' \
          --exclude='storage/framework/sessions/*' \
          --exclude='storage/framework/views/*' \
          --exclude='storage/logs/*' \
          --exclude='bootstrap/cache/*' \
          --exclude='tests' \
          --exclude='.github' \
          . deployment/

# Create necessary directories
mkdir -p deployment/storage/app/public
mkdir -p deployment/storage/framework/{cache,sessions,views}
mkdir -p deployment/storage/logs
mkdir -p deployment/bootstrap/cache

# Set permissions
chmod -R 755 deployment/storage
chmod -R 755 deployment/bootstrap/cache

echo "Deployment package ready in ./deployment directory"
