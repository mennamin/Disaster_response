```html
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Best Route | Disaster Response</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="topbar">
        <a class="brand" href="best-route.php">
            Disaster<span>Response</span>
        </a>

        <nav>
            <a href="backup-requests.php">Backup Request</a>
            <a class="active" href="best-route.php">Best Route</a>
        </nav>

        <span class="role-pill">Admin · UI Preview</span>
    </header>

    <main class="container">
        <div class="phase-note">
            <strong>PHASE 1 — UI DESIGN ONLY</strong>
            · Static preview. Forms, status updates, route calculation,
            and database operations are not active yet.
        </div>

        <section class="page-heading">
            <div>
                <p class="eyebrow">ROUTE PLANNING</p>
                <h1>Showing Best Route to Location</h1>
                <p class="muted">
                    Preview a driving route between two places,
                    including distance and estimated duration.
                </p>
            </div>
        </section>

        <div class="route-layout">
            <section class="panel route-controls">
                <h2>Plan a route</h2>
                <p class="muted">
                    Enter a place name or choose a saved incident.
                </p>

                <form onsubmit="return false">
                    <label>
                        Starting location
                        <input
                            value="Tahrir Square, Cairo, Egypt"
                            readonly
                        >
                    </label>

                    <label>
                        Destination
                        <input
                            value="Nasr City, Cairo, Egypt"
                            readonly
                        >
                    </label>

                    <label>
                        Use incident as destination
                        <select>
                            <option>Enter destination manually</option>
                            <option>Building fire · Tahrir Square</option>
                            <option>Road accident · Cairo Tower</option>
                        </select>
                    </label>

                    <div class="form-actions">
                        <button
                            class="btn primary static-control"
                            type="button"
                        >
                            Find best route
                        </button>

                        <button
                            class="btn secondary static-control"
                            type="button"
                        >
                            Swap locations
                        </button>
                    </div>
                </form>

                <div class="route-result">
                    <div>
                        <span>Distance (sample)</span>
                        <strong>12.4 km</strong>
                    </div>

                    <div>
                        <span>Estimated drive (sample)</span>
                        <strong>24 min</strong>
                    </div>
                </div>

                <p class="footnote">
                    Route calculation and live geocoding will be
                    implemented in Phase 2.
                </p>
            </section>

            <section class="panel map-panel">
                <div
                    class="mock-map"
                    aria-label="Illustrative map preview"
                >
                    <div class="map-river"></div>
                    <div class="map-road"></div>
                    <div class="map-road two"></div>
                    <div class="mock-route"></div>

                    <span class="map-pin start"></span>
                    <span class="map-pin end"></span>

                    <span class="map-label start">
                        START · TAHRIR SQUARE
                    </span>

                    <span class="map-label end">
                        DESTINATION · NASR CITY
                    </span>
                </div>

                <div class="map-legend">
                    <span>
                        <i class="origin-dot"></i>
                        Start
                    </
