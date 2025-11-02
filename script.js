const apiBase = "http://localhost/task%201/backend";

async function fetchWeather(city) {
  const res = await fetch(`${apiBase}/getWeather.php?city=${city}`);
  return await res.json();
}

async function fetchForecast(city) {
  const res = await fetch(`${apiBase}/getForecast.php?city=${city}`);
  return await res.json();
}

async function fetchHistory() {
  const res = await fetch(`${apiBase}/getHistory.php`);
  return await res.json();
}

async function clearHistory() {
  await fetch(`${apiBase}/deleteHistory.php`);
  loadHistory();
}

document.getElementById("getWeather").addEventListener("click", async () => {
  const city = document.getElementById("city").value.trim();
  if (!city) return alert("Enter a city!");

  const weather = await fetchWeather(city);
  if (weather.status === "success") {
    document.getElementById("weatherResult").innerHTML = `
      <div class="weather-icon">
        <img src="https://openweathermap.org/img/wn/${weather.icon}@2x.png">
      </div>
      <h3>${weather.city}</h3>
      <p>${weather.temperature}°C, ${weather.description}</p>
    `;

    const forecast = await fetchForecast(city);
    if (forecast.status === "success") {
      let html = '';
      let chartLabels = [];
      let chartData = [];

      forecast.forecast.forEach(day => {
        html += `
          <div class="col-md-2 forecast-card">
            <b>${day.date}</b><br>${day.temp}°C<br>${day.desc}
          </div>`;
        chartLabels.push(day.date);
        chartData.push(day.temp);
      });

      document.getElementById("forecast").innerHTML = html;
      drawChart(chartLabels, chartData);
      loadHistory();
    }
  } else {
    alert(weather.message);
  }
});

document.getElementById("clearHistory").addEventListener("click", clearHistory);

async function loadHistory() {
  const history = await fetchHistory();
  const list = document.getElementById("history");
  list.innerHTML = "";
  history.history.forEach(item => {
    const li = document.createElement("li");
    li.classList.add("list-group-item");
    li.textContent = `${item.city} - ${item.temperature}°C (${item.description})`;
    list.appendChild(li);
  });
}

function drawChart(labels, data) {
  const ctx = document.getElementById("chart").getContext("2d");
  new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Temperature (°C)',
        data,
        borderColor: '#007BFF',
        borderWidth: 2,
        fill: false
      }]
    },
    options: { responsive: true }
  });
}

loadHistory();
