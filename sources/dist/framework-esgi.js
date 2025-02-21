document.addEventListener("DOMContentLoaded", (n) => {
  document.querySelectorAll(".navbar__button").forEach(function(t) {
    t.addEventListener("click", (c) => {
      const e = t.parentElement.querySelector("ul");
      e.classList.contains("active") ? (e.classList.remove("active"), e.style.height = 0) : (e.classList.add("active"), e.style.height = e.scrollHeight + "px");
    });
  });
});
