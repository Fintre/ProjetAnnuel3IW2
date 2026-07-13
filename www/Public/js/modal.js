function openEditModal({ id, name, description, rate, tax }) {
  const modal = document.getElementById("editModal");

  document.getElementById("edit-id").value = id;
  document.getElementById("edit-name").value = name;
  document.getElementById("edit-desc").value = description;
  document.getElementById("edit-annual_interest_rate").value = rate;
  document.getElementById("edit-tax_rate").value = tax;

  modal.style.display = "flex";
}

function openDeleteModal({ id, name }) {
  const modal = document.getElementById("deleteModal");

  document.getElementById("delete-id").value = id;
  document.getElementById("delete-account-name").textContent = name;

  modal.style.display = "flex";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

document.addEventListener("click", (event) => {
  const editBtn = event.target.closest(".edit-badge");
  if (editBtn) {
    openEditModal({
      id: editBtn.dataset.id,
      name: editBtn.dataset.name,
      description: editBtn.dataset.desc,
      rate: editBtn.dataset.rate,
      tax: editBtn.dataset.tax,
    });
    return;
  }

  const deleteBtn = event.target.closest(".delete-badge");
  if (deleteBtn) {
    openDeleteModal({
      id: deleteBtn.dataset.id,
      name: deleteBtn.dataset.name,
    });
  }
});

window.onclick = function (event) {
  if (event.target.classList.contains("modal-overlay")) {
    event.target.style.display = "none";
  }
};

document.onkeydown = function (event) {
  if (event.key === "Escape") {
    document.querySelectorAll(".modal-overlay").forEach((modal) => {
      modal.style.display = "none";
    });
  }
};
