const menuToggle = document.querySelector(".menu-toggle");
const nav = document.querySelector(".nav");

if (menuToggle && nav) {
  menuToggle.addEventListener("click", () => {
    nav.classList.toggle("active");
    menuToggle.classList.toggle("active");
  });
}

// ============================================================
// MESSAGE DU FORMULAIRE DE CONTACT
// ============================================================

const params = new URLSearchParams(window.location.search);
const formMessage = document.getElementById("form-message");

if (formMessage) {
  const success = params.get("success");
  const error = params.get("error");

  if (success === "1") {
    formMessage.textContent =
      "Votre message a bien été envoyé. Je vous répondrai dans les meilleurs délais.";

    formMessage.classList.add("success");
  }

  if (error === "1") {
    formMessage.textContent = "Veuillez remplir tous les champs obligatoires.";

    formMessage.classList.add("error");
  }

  if (error === "2") {
    formMessage.textContent = "Veuillez entrer une adresse email valide.";

    formMessage.classList.add("error");
  }

  if (error === "3") {
    formMessage.textContent =
      "Une erreur est survenue lors de l’envoi. Veuillez réessayer.";

    formMessage.classList.add("error");
  }

  if (error === "4") {
    formMessage.textContent =
      "Un ou plusieurs champs sont trop longs. Veuillez réduire votre message.";

    formMessage.classList.add("error");
  }
}
