const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("{{ url('/vue') }}");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("{{ url('/vue') }}");
});


