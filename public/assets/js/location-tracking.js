/**
 * Petugas Location Tracking
 * Auto-update location every 30 seconds when tracking is enabled
 */

let trackingInterval = null;
let trackingEnabled = false;

// Check if user is PETUGAS
const userRole = document.querySelector('meta[name="user-role"]')?.content;
const isPetugas = userRole === 'PETUGAS';

// Initialize tracking toggle
document.addEventListener('DOMContentLoaded', function() {
    if (!isPetugas) return;

    const toggleBtn = document.getElementById('toggle-tracking');
    const statusText = document.getElementById('tracking-status');
    const badge = document.getElementById('tracking-badge');

    // Check saved tracking state
    trackingEnabled = localStorage.getItem('locationTracking') === 'true';
    updateTrackingUI();

    // Toggle button handler
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleTracking();
        });
    }

    // Start tracking if enabled
    if (trackingEnabled) {
        startTracking();
    }
});

// Toggle tracking on/off
function toggleTracking() {
    if (trackingEnabled) {
        stopTracking();
    } else {
        startTracking();
    }
}

// Start location tracking
function startTracking() {
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung geolocation');
        return;
    }

    trackingEnabled = true;
    localStorage.setItem('locationTracking', 'true');
    updateTrackingUI();

    // Update immediately
    updateLocation();

    // Then update every 30 seconds
    trackingInterval = setInterval(updateLocation, 30000);

    showNotification('Tracking lokasi diaktifkan', 'success');
}

// Stop location tracking
function stopTracking() {
    trackingEnabled = false;
    localStorage.setItem('locationTracking', 'false');
    updateTrackingUI();

    if (trackingInterval) {
        clearInterval(trackingInterval);
        trackingInterval = null;
    }

    showNotification('Tracking lokasi dinonaktifkan', 'info');
}

// Update location to server
function updateLocation() {
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            fetch('/petugas/location/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ latitude, longitude })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Location updated:', data.data);
                    updateLastUpdateTime();
                } else {
                    console.error('Failed to update location:', data.message);
                }
            })
            .catch(error => {
                console.error('Error updating location:', error);
            });
        },
        function(error) {
            console.error('Geolocation error:', error);
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    showNotification('Akses lokasi ditolak. Mohon aktifkan izin lokasi di browser.', 'error');
                    stopTracking();
                    break;
                case error.POSITION_UNAVAILABLE:
                    showNotification('Lokasi tidak tersedia', 'warning');
                    break;
                case error.TIMEOUT:
                    console.log('Geolocation timeout, will retry...');
                    break;
            }
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

// Update UI based on tracking state
function updateTrackingUI() {
    const statusText = document.getElementById('tracking-status');
    const badge = document.getElementById('tracking-badge');

    if (statusText && badge) {
        if (trackingEnabled) {
            statusText.textContent = 'Tracking Aktif';
            badge.textContent = 'ON';
            badge.classList.remove('bg-secondary');
            badge.classList.add('bg-success');
        } else {
            statusText.textContent = 'Aktifkan Tracking';
            badge.textContent = 'OFF';
            badge.classList.remove('bg-success');
            badge.classList.add('bg-secondary');
        }
    }
}

// Update last update time indicator
function updateLastUpdateTime() {
    const now = new Date();
    localStorage.setItem('lastLocationUpdate', now.toISOString());
}

// Show notification toast
function showNotification(message, type = 'info') {
    // Check if toast notification library is available
    if (typeof Swal !== 'undefined') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        Toast.fire({
            icon: type,
            title: message
        });
    } else {
        // Fallback to console
        console.log(`[${type.toUpperCase()}] ${message}`);
    }
}

// Check tracking permission on page load
if (isPetugas && trackingEnabled) {
    navigator.permissions?.query({ name: 'geolocation' }).then(function(result) {
        if (result.state === 'denied') {
            stopTracking();
            alert('Izin lokasi ditolak. Tracking otomatis dinonaktifkan. Mohon aktifkan izin lokasi di pengaturan browser Anda.');
        }
    });
}
