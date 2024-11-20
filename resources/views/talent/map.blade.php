<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentMap - Find Nearby Developers</title>
    <link rel="stylesheet" href="https://unpkg.com/mapbox-gl@2.8.2/dist/mapbox-gl.css">
    <style>
        :root {
            --primary-color: #e29fee;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto;
        }

        .container {
            display: grid;
            grid-template-columns: 350px 1fr;
            height: 100vh;
        }

        .sidebar {
            padding: 2rem;
            background: #fff;
            border-right: 1px solid #eee;
            overflow-y: auto;
        }

        .map-container {
            width: 100%;
            height: 100%;
        }

        .search-box {
            margin-bottom: 2rem;
        }

        .search-box input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            font-size: 1rem;
        }

        .talent-list {
            display: grid;
            gap: 1rem;
        }

        .talent-card {
            padding: 1rem;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .talent-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .match-score {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: var(--primary-color);
            color: white;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .skill-tag {
            padding: 0.25rem 0.5rem;
            background: #f3f4f6;
            border-radius: 4px;
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="search-box">
                <input type="text" id="search" placeholder="Search skills...">
            </div>
            <div class="talent-list" id="talentList"></div>
        </div>
        <div class="map-container" id="map"></div>
    </div>

    <script src="https://unpkg.com/mapbox-gl@2.8.2/dist/mapbox-gl.js"></script>
    <script>
        class TalentMap {
            constructor() {
                this.map = null;
                this.markers = [];
                this.currentLocation = null;
                this.init();
            }

            async init() {
                await this.initializeMap();
                this.setupEventListeners();
                await this.getCurrentLocation();
                this.loadNearbyTalent();
            }

            async initializeMap() {
                // mapboxgl.accessToken = '1222';
                // mapboxgl.accessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}';
                // this.map = new mapboxgl.Map({
                //     container: 'map',
                //     style: 'mapbox://styles/mapbox/light-v10',
                //     center: [-74.006, 40.7128],
                //     zoom: 12
                // });

                this.map = new mapboxgl.Map({
                    container: "map",
                    center: [120.3049, 31.4751],
                    zoom: 12,
                    testMode: true,
                });
            }

            async getCurrentLocation() {
                return new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            this.currentLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            this.map.flyTo({
                                center: [this.currentLocation.lng, this.currentLocation.lat],
                                zoom: 12
                            });
                            resolve(this.currentLocation);
                        },
                        error => {
                            console.error('Error getting location:', error);
                            reject(error);
                        }
                    );
                });
            }

            async loadNearbyTalent() {
                if (!this.currentLocation) return;

                try {
                    const response = await fetch(
                        `/talent/nearby?lat=${this.currentLocation.lat}&lng=${this.currentLocation.lng}&radius=50`);
                    const talent = await response.json();
                    this.displayTalent(talent);
                } catch (error) {
                    console.error('Error loading nearby talent:', error);
                }
            }

            displayTalent(talent) {
                this.clearMarkers();
                const talentList = document.getElementById('talentList');
                talentList.innerHTML = '';

                talent.forEach(developer => {
                    // Add marker to map
                    const marker = new mapboxgl.Marker({
                            color: '#e29fee'
                        })
                        .setLngLat([developer.longitude, developer.latitude])
                        .addTo(this.map);

                    this.markers.push(marker);

                    // Add to sidebar
                    const card = this.createTalentCard(developer);
                    talentList.appendChild(card);
                });
            }

            createTalentCard(developer) {
                const card = document.createElement('div');
                card.className = 'talent-card';
                card.innerHTML = `
                    <h3>${developer.name}</h3>
                    <span class="match-score">${Math.round(developer.distance)}km away</span>
                    <div class="skills-list">
                        ${developer.skills.map(skill => `
                                <span class="skill-tag">${skill.name}</span>
                            `).join('')}
                    </div>
                `;
                return card;
            }

            clearMarkers() {
                this.markers.forEach(marker => marker.remove());
                this.markers = [];
            }

            setupEventListeners() {
                const searchInput = document.getElementById('search');
                searchInput.addEventListener('input', this.handleSearch.bind(this));
            }

            async handleSearch(event) {
                const skills = event.target.value.toLowerCase().split(',').map(s => s.trim()).filter(Boolean);
                if (!skills.length) {
                    this.loadNearbyTalent();
                    return;
                }

                try {
                    const response = await fetch('/talent/match', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            skills,
                            location: this.currentLocation,
                            radius: 50
                        })
                    });
                    const matches = await response.json();
                    this.displayTalent(matches.map(m => m.developer));
                } catch (error) {
                    console.error('Error searching talent:', error);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new TalentMap();
        });
    </script>
</body>

</html>
