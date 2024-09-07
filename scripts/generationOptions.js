let options = "";
for (let i = 1; i <= 24; i++) {
  const hour = String(i).padStart(2, "0"); // Pad hours with 0 for single digits
  options += `<option value="${hour}">${hour}:00</option>`;
}
document.getElementById("sleep-time").innerHTML = options;
document.getElementById("wake-time").innerHTML = options;
