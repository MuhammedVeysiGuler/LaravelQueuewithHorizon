# Concurrent Job Processing using Laravel Horizon

## Sending Jobs Concurrently with Laravel Horizon

**Notes:**
- Redis must be installed and running on the server to use this application.
- It does not work on Windows machines due to ext-pcntl extension issues. Use it on non-Windows machines.

### Installation Steps

1. Run `composer update`.

2. Run `php artisan key:generate`.

3. Run `php artisan horizon:install`.

4. Update your `.env` file with the following configurations:

    - Configure mail settings:
        ```dotenv
        MAIL_MAILER=smtp
        MAIL_HOST=smtp.gmail.com
        MAIL_PORT=587
        MAIL_USERNAME=YOUR_MAIL_ADDRESS
        MAIL_PASSWORD="YOUR_PASSWORD"
        MAIL_ENCRYPTION=tls
        MAIL_FROM_ADDRESS=YOUR_MAIL_ADDRESS
        MAIL_FROM_NAME="MAIL_NAME"
        ```

    - Set `QUEUE_CONNECTION` to `redis`:
        ```dotenv
        QUEUE_CONNECTION=redis
        ```

   - Configure redis settings:
       ```dotenv
       REDIS_HOST=127.0.0.1
       REDIS_PASSWORD=null
       REDIS_PORT=6379
       REDIS_CLIENT=predis
       ```

5. Run `php artisan migrate` (for database tables).

6. Run `php artisan horizon` (to start Horizon).

7. Open your browser and go to `your-domain/horizon` to access the Horizon Dashboard.

---

**Note:** Make sure to follow these steps in order. Also, ensure that Redis is installed and running.

---

**Warning:** This example not work on Windows machines. It is recommended to use it in a non-Windows environment.
