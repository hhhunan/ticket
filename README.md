# 🎫 Ticket CRM

Ticket CRM is a simple customer support / ticket management system with
an admin panel, API statistics, and an embeddable ticket widget. The
project is fully dockerized for easy setup and deployment.

------------------------------------------------------------------------

##  Installation (Docker)

###  Clone the repository

``` bash
git clone <your-repository-url>
cd ticket
```

### Environment configuration

**Linux / macOS**

``` bash
cp .env.example .env
```

**Windows**

``` bat
copy .env.example .env
```
###  Build by Make

``` bash
make crm-build
```
⏳ Wait a few minutes until all services are built and running.

### Open the application

Admin panel login page:

    http://localhost/admin/login

API Documentation page:

    http://localhost/api/documentation

Widget page:

    http://localhost/widget

### Run tests (Make sure it's not a production)

``` bash
make test
```
------------------------------------------------------------------------

## Admin Access

**Manager credentials (default):** - Email: manager@ticket.com -
Password: password


------------------------------------------------------------------------

##  API Documentation

### Get ticket statistics

**Endpoint**

    GET /api/tickats/statistics

**Default behavior** - Filtered by day

**Query Parameters** - period=day (default) - period=week -
period=month - period=extended

**Example**

    GET /api/tickats/statistics?period=month

------------------------------------------------------------------------

## Ticket Widget

Embed the ticket form into your website:

``` html
<iframe src="YOUR_DOMAIN_NAME/widget"></iframe>
```

Replace `YOUR_DOMAIN_NAME` with your deployed Ticket CRM domain.

------------------------------------------------------------------------

##  Rate Limiting

-   One ticket per day
-   Per email or phone number

------------------------------------------------------------------------

## Tech Stack

-   PHP / Laravel
-   mysql
-   redis
-   nginx
-   Docker & Docker Compose
-   Make
-   REST API
-   Blade UI (Admin Panel)

------------------------------------------------------------------------

## Notes

-   Ensure ports 80 is free
-   Configure `.env` before production use
-   Enable HTTPS in production
