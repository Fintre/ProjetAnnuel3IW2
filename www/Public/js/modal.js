/**
 * Fonction pour ouvrir la modale d'édition et remplir ses champs
 */
function openEditModal(id, name, description) {
  const modal = document.getElementById("editModal");

  // Remplissage des champs du formulaire
  document.getElementById("edit-id").value = id;
  document.getElementById("edit-name").value = name;
  document.getElementById("edit-desc").value = description;

  // Affichage de la modale
  modal.style.display = "flex";
}

/**
 * Fonction pour ouvrir la modale de suppression
 */
function openDeleteModal(id, name) {
  const modal = document.getElementById("deleteModal");

  // Mise à jour du texte de confirmation
  document.getElementById("delete-id").value = id;
  document.getElementById("delete-account-name").textContent = name;

  // Affichage de la modale
  modal.style.display = "flex";
}

/**
 * Fonction générique pour fermer une modale
 */
function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.style.display = "none";
}

/**
 * Fermeture de la modale en cliquant en dehors du contenu
 */
window.onclick = function (event) {
  if (event.target.classList.contains("modal-overlay")) {
    event.target.style.display = "none";
  }
};

/**
 * Fermeture avec la touche Échap
 */
document.onkeydown = function (event) {
  if (event.key === "Escape") {
    const modals = document.querySelectorAll(".modal-overlay");
    modals.forEach((modal) => {
      modal.style.display = "none";
    });
  }
};
