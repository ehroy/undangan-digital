// Ambil parameter URL
const params = new URLSearchParams(window.location.search);
let guest = params.get("to");

// Decode nama tamu
if (guest) {
  guest = guest.replace(/\+/g, " ");
  guest = decodeURIComponent(guest);
  document.getElementById("guest-name").innerText = "Yth. " + guest;
}

// Function buka undangan
function openInvitation() {
  document.getElementById("cover").style.display = "none";
  document.getElementById("content").style.display = "block";
}

// Auto open jika ada #open
if (window.location.hash === "#open") {
  openInvitation();
}
