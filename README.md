# Doctors REST API
This project allows users to fetch all doctors, all clinics and their relations in a RESTful manner.

## Installation

1. Download the Zip file and extract it.
2. Import the database using MySQL console or phpMyAdmin.
3. Make sure you change 'AllowOverride None' to 'AllowOverride All' in your httpd.conf file for URL rewriting.
4. Run 'composer install' to install all dependencies.
5. Run the server.

## What it does?

The project provides 4 end points as per given assignment.

1. /doctors?page=4 - For getting details of all doctors in paginated manner
2. /clinics?page=5 - For getting details of all clinics in paginated manner
3. /doctors/id - For getting doctor details and places where he/she practices. Here id is an integer
4. /clinics/id - For getting clinic details and all doctors who practice there.

## Dependencies

* "slim/slim": "^3.0"
