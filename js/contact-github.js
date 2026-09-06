const githubContactForm = document.getElementById("github-contact-form");

if (githubContactForm) {
  githubContactForm.addEventListener("submit", function (event) {
    event.preventDefault();

    console.log("Formulaire GitHub prêt.");
  });
}
