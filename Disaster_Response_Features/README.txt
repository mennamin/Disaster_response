DISASTER RESPONSE SYSTEM - TWO FEATURE STARTER
==============================================
Includes:
- Backup Request: create, list, and update resource requests in MySQL.
- Best Route: geocode places, display driving routes on a map, estimate distance/time, and save route history.
- SQL schema, shared blue CSS theme, and PHP configuration.

IMPORTANT SCOPE NOTE
--------------------
This is a runnable, independent starter for your two features. It is NOT merged into your teammates' exact code because their complete project files were not provided here. The database name is intentionally `disaster_response_features` so importing the SQL will not drop or alter their `disaster_response` database.

REQUIREMENTS
------------
- XAMPP with Apache and MySQL running
- PHP with PDO MySQL enabled (standard XAMPP setup)
- Internet access for the map, geocoding, and route API

INSTALL ON WINDOWS / XAMPP
--------------------------
1. Extract this folder into: C:\xampp\htdocs\Disaster_Response_Features
   (Use your actual XAMPP htdocs path if it is different.)
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open http://localhost/phpmyadmin
4. Choose the Import tab, select sql/schema.sql, and click Import/Go.
   The schema creates `disaster_response_features` and does not drop tables.
5. Open http://localhost/Disaster_Response_Features/
6. Use Backup Request or Best Route from the top navigation.

DATABASE SETTINGS
-----------------
config.php defaults to XAMPP's common local setup:
  host: localhost
  database: disaster_response_features
  user: root
  password: (blank)
Change those values only if your local MySQL settings are different.

HOW TO TEST
-----------
- Backup Request: choose an incident, department, resource, quantity, priority, and reason; submit; the request appears in the queue. In demo mode, status updates are available.
- Best Route: enter a starting location and destination; click Find best route. It uses Nominatim geocoding, OpenStreetMap tiles, and OSRM routing; a successful route is saved in route_searches.

BEFORE MERGING WITH YOUR TEAMMATES' PROJECT
-------------------------------------------
- Keep a backup of their project and database.
- Do not import schema.sql into their existing `disaster_response` database unchanged. This starter has its own table definitions and demo users/incidents.
- First compare their actual users/incidents tables with sql/schema.sql and adjust column/table names.
- Replace the demo fallbacks in config.php (`currentUserId`, `currentRole`) with their real authentication/session system. Do not use the demo defaults in a public deployment.
- If merging pages into their existing project, update navbar paths and stylesheet paths, and avoid redeclaring helper functions already defined by their includes.
- Route requests depend on public demo services and are not intended for real emergency dispatch decisions.
