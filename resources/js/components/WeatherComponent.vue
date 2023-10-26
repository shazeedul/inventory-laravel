<template>
  <div v-if="weatherData">
    <div class="card" style="color: #4b515d; border-radius: 35px">
      <div class="card-body p-4 my-3">
        <div class="d-flex flex-column text-center mt-1 mb-4">
          <h3 class="text-center fw-bold">
            {{ weatherData.name }}
          </h3>
          <h6 class="display-4 mb-0 font-weight-bold" style="color: #1c2331">
            {{ weatherData.main.temp }}°C
          </h6>
          <span class="" style="color: #868b94">{{
            weatherData.weather[0].description
          }}</span>
        </div>

        <div class="d-flex align-items-center">
          <div class="flex-grow-1" style="font-size: 1rem">
            <div>
              <i class="fas fa-wind fa-fw" style="color: #868b94"></i>
              <span class="ms-1">
                {{ weatherData.wind.speed }}
                km/h
              </span>
            </div>
            <div>
              <i class="fas fa-tint fa-fw" style="color: #868b94"></i>
              <span class="ms-1"> {{ weatherData.main.humidity }}% </span>
            </div>
            <div>
              <i class="fas fa-sun fa-fw" style="color: #868b94"></i>
              <span class="ms-1">
                {{
                  calculateSunDuration(
                    weatherData.sys.sunrise,
                    weatherData.sys.sunset
                  )
                }}
              </span>
            </div>
          </div>
          <div>
            <img
              :src="getWeatherIconClass(weatherData.weather[0].main)"
              style="width: 100px"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end -->
  <div v-else-if="locationPermissionDenied" class="card">
    <div class="card-body">
      <p class="card-text">
        Location permission denied. Please enable location access for weather
        forecast.
      </p>
      <button @click="requestLocationPermission" class="btn btn-primary">
        Enable Location
      </button>
    </div>
  </div>
  <div v-else class="card">
    <div class="card-body">
      <p class="card-text">{{ locationErrorMessage }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: ["apiKey", "assertsPath"],
  data() {
    return {
      weatherData: null,
      locationPermissionDenied: false,
    };
  },
  computed: {
    weatherIconUrl() {
      return this.weatherData
        ? `https://openweathermap.org/img/w/${this.weatherData.weather[0].icon}.png`
        : "";
    },
    locationErrorMessage() {
      if (this.locationPermissionDenied) {
        return "Location permission denied. Please enable location access for weather forecast.";
      } else {
        return "Loading weather data...";
      }
    },
  },
  methods: {
    fetchWeatherData() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const { latitude, longitude } = position.coords;
            const apiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${this.apiKey}&units=metric`;
            if (this.apiKey) {
              fetch(apiUrl)
                .then((response) => response.json())
                .then((data) => {
                  this.weatherData = data;
                })
                .catch((error) => console.error(error));
            }
          },
          (error) => {
            console.error(error);
            this.locationPermissionDenied = true;
          }
        );
      } else {
        console.error("Geolocation is not supported by this browser.");
      }
    },
    requestLocationPermission() {
      navigator.geolocation.getCurrentPosition(
        () => {
          this.locationPermissionDenied = false;
          this.fetchWeatherData();
        },
        (error) => {
          alert(
            "To enable location access, please go to your browser settings and allow access to your location."
          );

          console.error(error);
        }
      );
    },

    calculateSunDuration(sunrise, sunset) {
      const sunriseTime = new Date(sunrise * 1000);
      const sunsetTime = new Date(sunset * 1000);
      const durationInMillis = sunsetTime - sunriseTime;
      const hours = Math.floor(durationInMillis / (60 * 60 * 1000));
      const minutes = Math.floor(
        (durationInMillis % (60 * 60 * 1000)) / (60 * 1000)
      );
      return `${hours}.${minutes}h`;
    },
    getCurrentTime() {
      const now = new Date();
      const hours = now.getHours().toString().padStart(2, "0");
      const minutes = now.getMinutes().toString().padStart(2, "0");
      return `${hours}:${minutes}`;
    },
    getWeatherIconClass(weatherMain) {
      // Map weather conditions to FontAwesome icons
      const weatherIcons = {
        Clear: "sun", // Clear sky
        Clouds: "cloudy", // Cloudy
        Rain: "cloud-showers-heavy", // Rainy
        Snow: "snowflake", // Snowy
        Thunderstorm: "thunderstorm", // Thunderstorm
        Drizzle: "drizzle", // Drizzle
        Mist: "fog", // Misty
        Fog: "fog", // Foggy
        Haze: "fog", // Hazy
        Tornado: "tornado", // Tornado
        Smoke: "smoke", // Smoky
        Dust: "dust", // Dusty
        Sand: "sand", // Sandy
        Ash: "smoke", // Ashy
      };

      return (
        this.assertsPath +
        "/weather/" +
        (weatherIcons[weatherMain] || "not-match") +
        ".png"
      ); // Default icon for unknown conditions
    },
  },
  created() {
    this.fetchWeatherData();
    setInterval(this.fetchWeatherData, 600000);
  },
};
</script>

