// Phase 1 prototype: all report data is hardcoded in this file.
// Changes made in the browser are temporary and are not saved to a backend.

const reports = [
  {
    id: "R001",
    type: "Road Accident",
    location: "Tahrir Square, Cairo",
    severity: "Critical",
    status: "Assigned",
    description: "Two cars collided near the intersection. Traffic is partially blocked and several people may need assistance.",
    affectedPeople: 4,
    reportedAt: "16 October 2026",
    statusDescription: "Response team assigned."
  },
  {
    id: "R002",
    type: "Fire",
    location: "Nasr City, Cairo",
    severity: "High",
    status: "Under Review",
    description: "Smoke was reported coming from a residential building. The situation requires assessment.",
    affectedPeople: 12,
    reportedAt: "16 October 2026",
    statusDescription: "Incident details are being reviewed."
  },
  {
    id: "R003",
    type: "Medical Emergency",
    location: "Dokki, Giza",
    severity: "Medium",
    status: "Resolved",
    description: "A person fainted in a public area. Assistance was reported and the incident is marked resolved.",
    affectedPeople: 1,
    reportedAt: "15 October 2026",
    statusDescription: "Incident resolved."
  }
];

function escapeHtml(value) {
  return String(value).replace(/[&<>"']/g, function (character) {
    return ({
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;"
    })[character];
  });
}

function severityClass(severity) {
  return "severity-" + severity.toLowerCase().replace(/\s+/g, "-");
}

function statusClass(status) {
  return "status-" + status.toLowerCase().replace(/\s+/g, "-");
}

function renderReports() {
  const body = document.getElementById("reports-table-body");
  if (!body) return;

  document.getElementById("report-count").textContent = reports.length;
  body.innerHTML = reports.map(function (report) {
    return `
      <tr>
        <td class="report-number">${escapeHtml(report.id)}</td>
        <td>${escapeHtml(report.type)}</td>
        <td>${escapeHtml(report.location)}</td>
        <td><span class="severity ${severityClass(report.severity)}">${escapeHtml(report.severity)}</span></td>
        <td><span class="status-badge ${statusClass(report.status)}">${escapeHtml(report.status)}</span></td>
        <td><a class="button button-small" href="request_details.html?id=${encodeURIComponent(report.id)}">View details</a></td>
      </tr>`;
  }).join("");

  const select = document.getElementById("status-report-id");
  if (select && select.options.length === 1) {
    reports.forEach(function (report) {
      const option = document.createElement("option");
      option.value = report.id;
      option.textContent = report.id + " — " + report.type;
      select.appendChild(option);
    });
  }
}

function setupStatusForm() {
  const form = document.getElementById("status-form");
  if (!form) return;

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    const reportId = document.getElementById("status-report-id").value;
    const description = document.getElementById("status-description").value.trim();
    const message = document.getElementById("status-message");
    const report = reports.find(function (item) { return item.id === reportId; });

    if (!report || !description) {
      message.textContent = "Please select a report and enter a status description.";
      message.classList.remove("success");
      return;
    }

    report.statusDescription = description;
    // This demo updates the visible status temporarily; it does not save to a server.
    report.status = description.length > 22 ? description.slice(0, 19) + "..." : description;
    renderReports();
    document.getElementById("status-report-id").value = reportId;
    document.getElementById("status-description").value = "";
    message.textContent = "Prototype update shown. This change is temporary and is not saved.";
    message.classList.add("success");
  });
}

function renderDetails() {
  const container = document.getElementById("report-details");
  if (!container) return;

  const params = new URLSearchParams(window.location.search);
  const id = params.get("id") || "R001";
  const report = reports.find(function (item) { return item.id === id; });
  const idLabel = document.getElementById("details-report-id");

  if (!report) {
    idLabel.textContent = "Report not found";
    container.innerHTML = '<div class="empty-state">No sample report matches this ID. <a href="reports.html">Return to reports</a>.</div>';
    return;
  }

  idLabel.textContent = report.id;
  container.innerHTML = `
    <div class="details-banner">
      <div>
        <h2>${escapeHtml(report.type)}</h2>
        <p>${escapeHtml(report.location)}</p>
      </div>
      <div>
        <span class="severity ${severityClass(report.severity)}">${escapeHtml(report.severity)}</span>
        <span class="status-badge ${statusClass(report.status)}">${escapeHtml(report.status)}</span>
      </div>
    </div>
    <div class="details-grid">
      <div class="detail-item">
        <span class="detail-label">Report ID</span>
        <div class="detail-value">${escapeHtml(report.id)}</div>
      </div>
      <div class="detail-item">
        <span class="detail-label">Incident type</span>
        <div class="detail-value">${escapeHtml(report.type)}</div>
      </div>
      <div class="detail-item">
        <span class="detail-label">Location</span>
        <div class="detail-value">${escapeHtml(report.location)}</div>
      </div>
      <div class="detail-item">
        <span class="detail-label">Severity level</span>
        <div class="detail-value">${escapeHtml(report.severity)}</div>
      </div>
      <div class="detail-item">
        <span class="detail-label">Estimated affected people</span>
        <div class="detail-value">${escapeHtml(report.affectedPeople)}</div>
      </div>
      <div class="detail-item">
        <span class="detail-label">Date reported</span>
        <div class="detail-value">${escapeHtml(report.reportedAt)}</div>
      </div>
      <div class="detail-item full-width">
        <span class="detail-label">Incident description</span>
        <div class="detail-value">${escapeHtml(report.description)}</div>
      </div>
      <div class="detail-item full-width">
        <span class="detail-label">Status description</span>
        <div class="detail-value">${escapeHtml(report.statusDescription)}</div>
      </div>
    </div>`;
}

document.addEventListener("DOMContentLoaded", function () {
  renderReports();
  setupStatusForm();
  renderDetails();
});
