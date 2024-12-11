document.addEventListener("DOMContentLoaded", function () {
  let sortDirection = {};
  const table = document.querySelector(".bookings-table");

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

  function sortTable(column) {
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    sortDirection[column] = sortDirection[column] === "asc" ? "desc" : "asc";

    rows.sort((a, b) => {
      let aValue = a.querySelector(`td[data-${column}]`).dataset[column];
      let bValue = b.querySelector(`td[data-${column}]`).dataset[column];

      if (column === "total") {
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

  document.querySelectorAll(".delete-button").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const bookingId = this.dataset.id;

      if (confirm("Are you sure you want to delete this booking?")) {
        window.location.href = this.href;
      }
    });
  });
});
