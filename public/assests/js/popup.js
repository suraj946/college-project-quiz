function showPopup(message) {
  document.getElementById("popupMessage").innerText = message;
  document.getElementById("popupAlert").style.right = "25px";
  setTimeout(() => {
    closePopup();
  }, 4000);
}

function closePopup() {
  document.getElementById("popupAlert").style.right = "-800px";
}

