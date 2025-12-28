# WordPress Theme Development Environment

A Docker Compose setup for WordPress theme development with MariaDB.

## Prerequisites

- Docker
- Docker Compose

## Quick Start

1. **Start the containers:**
   ```bash
   docker-compose up -d
   ```

2. **Access WordPress:**
   - Open your browser and navigate to: `http://localhost:8080`
   - Follow the WordPress installation wizard

3. **Stop the containers:**
   ```bash
   docker-compose down
   ```

4. **Stop and remove volumes (clean slate):**
   ```bash
   docker-compose down -v
   ```

## Project Structure

```
.
├── docker-compose.yml
├── README.md
└── biederman-wp/
```

## Development Workflow

1. Create your theme in the `biederman-wp/` directory (at the project root)
2. The `biederman-wp` theme folder is mounted as a volume, so changes are reflected immediately
3. Activate your theme in WordPress admin: `Appearance > Themes`

## Services

### WordPress
- **Container:** `biederman-wp`
- **Port:** `8080` (mapped to container port 80)
- **Data Volume:** `wordpress_data` (persists WordPress core files)
- **Theme Volume:** `./biederman-wp` (mounted for development)

### MariaDB
- **Container:** `biederman-wp-db`
- **Port:** `3306`
- **Database:** `wordpress`
- **User:** `wordpress`
- **Password:** `wordpress`
- **Root Password:** `rootpassword`
- **Data Volume:** `db_data` (persists database)

### Node.js (for building blocks)
- **Container:** `biederman-wp-node`
- **Image:** `node:18`
- **Purpose:** Building Gutenberg blocks with npm
- **Profile:** `build` (only starts when explicitly requested)

## Database Connection

If you need to connect to the database from outside the containers:

- **Host:** `localhost`
- **Port:** `3306`
- **Database:** `wordpress`
- **Username:** `wordpress`
- **Password:** `wordpress`

## Useful Commands

### View logs
```bash
docker-compose logs -f
```

### View WordPress logs only
```bash
docker-compose logs -f wordpress
```

### Access WordPress container shell
```bash
docker exec -it biederman-wp bash
```

### Access MariaDB container shell
```bash
docker exec -it biederman-wp-db bash
```

### Access MariaDB CLI
```bash
docker exec -it biederman-wp-db mysql -u wordpress -pwordpress wordpress
```

### Build Gutenberg blocks
```bash
# Start the Node container (if not already running)
docker-compose --profile build up -d node

# Run npm install (first time only)
docker-compose exec node npm install

# Build all blocks
docker-compose exec node npm run build

# Or run a single command
docker-compose exec node npm run build
```

### Access Node container shell
```bash
docker exec -it biederman-wp-node bash
```

### Restart services
```bash
docker-compose restart
```

## Environment Variables

You can customize the setup by modifying the environment variables in `docker-compose.yml`:

- `MYSQL_DATABASE`: Database name
- `MYSQL_USER`: Database user
- `MYSQL_PASSWORD`: Database password
- `MYSQL_ROOT_PASSWORD`: Root password
- `WORDPRESS_DB_*`: WordPress database connection settings

## Notes

- WordPress files are persisted in the `wordpress_data` volume
- Database data is persisted in the `db_data` volume
- Theme files in `biederman-wp/` are mounted directly, so changes are immediate
- To reset everything, run `docker-compose down -v` (this will delete all data)

