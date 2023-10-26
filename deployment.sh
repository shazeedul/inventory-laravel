#!/bin/bash

# Define your deployment directory on the server
DEPLOY_DIR="/var/www/your-laravel-project"

# Define your Git repository URL
GIT_REPO="https://github.com/yourusername/your-laravel-project.git"

# Define the branch you want to deploy
BRANCH="main"

# Function to display a message in a consistent format
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

# Function to handle errors and exit the script
handle_error() {
    log "Error: $1"
    exit 1
}

# Go to the deployment directory
cd $DEPLOY_DIR || handle_error "Could not change to deployment directory: $DEPLOY_DIR"

# Pull the latest changes from the Git repository
log "Pulling the latest changes from $GIT_REPO..."
git pull origin $BRANCH || handle_error "Failed to pull changes from Git repository"

# fix permission
log "Fixing permissions..."
sudo chown -R www-data:www-data $DEPLOY_DIR || handle_error "Failed to fix permissions"
sudo chmod -R 775 $DEPLOY_DIR/storage || handle_error "Failed to fix permissions"
sudo chmod -R 775 $DEPLOY_DIR/bootstrap/cache || handle_error "Failed to fix permissions"


# Copy the .env.example file and configure it
log "Copying and configuring .env file..."
cp .env.example .env
# Set your .env variables here, e.g., APP_NAME, APP_URL, and database configuration


# Install or update Composer dependencies
log "Installing Composer dependencies..."
composer install --no-interaction --no-dev --prefer-dist || handle_error "Composer install failed"

# setting application key
log "Setting application key..."
php artisan key:generate || handle_error "Failed to set application key"

# Generate the optimized autoload files and clear cached views and routes
log "Optimizing Laravel..."
php artisan optimize || handle_error "Failed to optimize Laravel"
php artisan view:clear || handle_error "Failed to clear cached views"
php artisan route:clear || handle_error "Failed to clear cached routes"

# Migrate the database
log "Migrating the database..."
php artisan migrate --force || handle_error "Database migration failed"

# storage link 
log "Linking storage..."
php artisan storage:link || handle_error "Failed to link storage"

# module asset link
log "Linking module assets..."
php artisan module:asset-link || handle_error "Failed to link module assets"

# Optimize the application for production
log "Optimizing Laravel for production..."
php artisan config:cache || handle_error "Failed to cache configuration"
php artisan route:cache || handle_error "Failed to cache routes"
php artisan view:cache || handle_error "Failed to cache views"

# Restart your web server (e.g., Nginx or Apache)
log "Restarting the web server..."
sudo service nginx restart || handle_error "Failed to restart the web server"

log "Deployment completed successfully."
