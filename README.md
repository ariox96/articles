# Articles Project

Welcome to the Articles System project! This system is designed for managing articles. It's built with Laravel and uses Docker for easy development and testing. Below, you'll find instructions on how to set up and run the system on your local machine.

## Prerequisites

Before you get started, ensure you have the following tools installed on your system:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/ariox96/articles.git

2. **Navigate to Project Directory:**
   ```bash
   cd articles

3. **Create .env File:**
   ```bash
    cp .env.example .env

4. **Build and Start Docker Containers:**
   ```bash
    docker compose up -d --build

5. **Install Dependencies:**
   ```bash
    docker exec -it articles-app composer install

6. **Run Migrations and Seed Data:**
   ```bash
    docker exec -it articles-app php artisan migrate:fresh --seed

## Usage

 **Access the article management system through your web browser by visiting**:
   ```bash
   http://127.0.0.1:8000

