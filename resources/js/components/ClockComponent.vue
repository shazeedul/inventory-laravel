<template>
  <div>
    <div class="d-flex flex-column text-center mt-1 mb-4">
      <h6 class="display-4 mb-0 font-weight-bold" style="color: #1c2331">
        {{ hours }}<span class="blink">:</span>{{ minutes }}
        {{ amPm }}
      </h6>
      <span class="my-2" style="color: #868b94">
        {{ dayOfWeek }}, {{ month }} {{ day }}, {{ year }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      hours: "",
      minutes: "",
      seconds: "",
      amPm: "",
      dayOfWeek: "",
      month: "",
      day: "",
      year: "",
    };
  },
  mounted() {
    this.updateClock();
    setInterval(this.updateClock, 1000); // Update the clock every second
  },
  methods: {
    updateClock() {
      const now = new Date();
      this.hours = now.getHours() % 12 || 12; // Convert to 12-hour format
      this.minutes = String(now.getMinutes()).padStart(2, "0");
      this.seconds = String(now.getSeconds()).padStart(2, "0");
      this.amPm = now.getHours() >= 12 ? "PM" : "AM";
      this.dayOfWeek = new Intl.DateTimeFormat("en-US", {
        weekday: "long",
      }).format(now);
      this.month = new Intl.DateTimeFormat("en-US", {
        month: "long",
      }).format(now);
      this.day = now.getDate();
      this.year = now.getFullYear();
    },
  },
};
</script>

<style scoped>
.blink {
  animation: blink 1s infinite;
}
@keyframes blink {
  0%,
  49% {
    opacity: 1;
  }
  50%,
  100% {
    opacity: 0;
  }
}
</style>
