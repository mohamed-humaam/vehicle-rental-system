document.addEventListener("DOMContentLoaded", function () {
  let sortDirection = {};
  const searchInput = document.querySelector(".search-box");
  const typeFilter = document.querySelector(".filter-select");
  const table = document.querySelector(".vehicles-table");

  document.querySelectorAll("th[data-sortable]").forEach((header) => {
    header.addEventListener("click", () => {
      const column = header.dataset.column;
      sortTable(column);

      document.querySelectorAll("th").forEach((th) => {
        th.classList.remove("sorted-asc", "sorted-desc");
      });

      header.classList.add(
        sortDirection[column] === "asc" ? "sorted-asc" : "sorted-desc"
      );
    });
  });

  searchInput.addEventListener("input", filterTable);
  typeFilter.addEventListener("change", filterTable);

  function sortTable(column) {
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    sortDirection[column] = sortDirection[column] === "asc" ? "desc" : "asc";

    rows.sort((a, b) => {
      let aValue = a.querySelector(`td[data-${column}]`).dataset[column];
      let bValue = b.querySelector(`td[data-${column}]`).dataset[column];

      if (column === "price") {
        aValue = parseFloat(aValue);
        bValue = parseFloat(bValue);
      }

      if (sortDirection[column] === "asc") {
        return aValue > bValue ? 1 : -1;
      } else {
        return aValue < bValue ? 1 : -1;
      }
    });

    while (tbody.firstChild) {
      tbody.removeChild(tbody.firstChild);
    }

    rows.forEach((row) => tbody.appendChild(row));

    rows.forEach((row, index) => {
      row.style.animation = `fadeIn 0.3s ease forwards ${index * 0.05}s`;
    });
  }

  function filterTable() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedType = typeFilter.value;
    const rows = table.querySelectorAll("tbody tr");

    rows.forEach((row) => {
      const name = row
        .querySelector("td[data-name]")
        .dataset.name.toLowerCase();
      const type = row.querySelector("td[data-type]").dataset.type;
      const matchesSearch = name.includes(searchTerm);
      const matchesType = selectedType === "" || type === selectedType;

      if (matchesSearch && matchesType) {
        row.style.display = "";
        row.style.animation = "fadeIn 0.3s ease forwards";
      } else {
        row.style.display = "none";
      }
    });
  }

  document.querySelectorAll(".vehicle-image").forEach((img) => {
    img.addEventListener("mouseenter", function () {
      this.style.zIndex = "1000";
    });

    img.addEventListener("mouseleave", function () {
      this.style.zIndex = "1";
    });
  });

  document.querySelectorAll(".delete-button").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const vehicleId = this.dataset.id;
      const vehicleName = this.dataset.name;

      if (confirm(`Are you sure you want to delete ${vehicleName}?`)) {
        window.location.href = this.href;
      }
    });
  });
});
