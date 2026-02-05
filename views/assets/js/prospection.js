// Données globales
let prospects = allItemsProspects
let descentes = allItemsDescentes
const clientsDescente = []
let bootstrap // Declaration of bootstrap variable

// Initialisation
document.addEventListener("DOMContentLoaded", () => {
  loadSampleData()
  renderProspects()
  updateProspectCount()
  populateDescenteSelect()
})

// Charger des données d'exemple
function loadSampleData() {
//   prospects = [
//     {
//       id: 1,
//       name: "Jean Dupont",
//       phone: "0123456789",
//       email: "jean.dupont@email.com",
//       status: "nouveau",
//       address: "123 Rue de la Paix, Paris",
//       notes: "Intéressé par un terrain commercial",
//       dateAdded: "2024-01-15",
//     },
//     {
//       id: 2,
//       name: "Marie Martin",
//       phone: "0987654321",
//       email: "marie.martin@email.com",
//       status: "interesse",
//       address: "456 Avenue des Champs, Lyon",
//       notes: "Recherche terrain résidentiel",
//       dateAdded: "2024-01-20",
//     },
//     {
//       id: 3,
//       name: "Pierre Durand",
//       phone: "0147258369",
//       email: "pierre.durand@email.com",
//       status: "contacte",
//       address: "789 Boulevard Saint-Michel, Marseille",
//       notes: "Budget limité, à recontacter",
//       dateAdded: "2024-01-25",
//     },
//   ]

  // descentes = [
  //   {
  //     id: 1,
  //     name: "Visite Quartier Nord",
  //     date: "2024-02-15",
  //     location: "Quartier Nord, Paris",
  //     description: "Visite des nouveaux terrains disponibles",
  //     clients: [],
  //   },
  //   {
  //     id: 2,
  //     name: "Inspection Zone Sud",
  //     date: "2024-02-20",
  //     location: "Zone Sud, Lyon",
  //     description: "Évaluation des terrains commerciaux",
  //     clients: [],
  //   },
  // ]
}

// Afficher les prospects
function renderProspects() {
  const tbody = document.getElementById("prospectsTableBody")
  tbody.innerHTML = ""

  prospects.forEach((prospect) => {
    const row = document.createElement("tr")
    row.className = "hover:bg-muted/50 transition-colors duration-200"

    const statusBadge = getStatusBadge(prospect.statutProspect)

    row.innerHTML = `
            <td class="px-4 py-3 text-foreground font-medium">${prospect.nomProspect}</td>
            <td class="px-4 py-3 text-muted-foreground">${prospect.telephoneProspect}</td>
            <td class="px-4 py-3 text-muted-foreground">${prospect.emailProspect || "-"}</td>
            <td class="px-4 py-3 text-muted-foreground">${prospect.lieuProspection || "-"}</td>
            <td class="px-4 py-3">${statusBadge}</td>
            <td class="px-4 py-3 text-muted-foreground">${formatDate(prospect.dateCreateProspect)}</td>
            <td class="px-4 py-3 text-muted-foreground">${prospect.notesProspect}</td>
            <td class="px-4 py-3">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary btn-sm" onclick="editProspect(${prospect.idProspect})" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="addProspectToDescente(${prospect.idProspect})" title="Ajouter à une descente">
                        <i class="bi bi-geo-alt"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteProspect(${prospect.idProspect})" title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `

    tbody.appendChild(row)
  })
}

// Obtenir le badge de statut
function getStatusBadge(status) {
  const badges = {
    nouveau: '<span class="badge bg-primary text-primary-foreground">Nouveau</span>',
    contacte: '<span class="badge bg-secondary text-secondary-foreground">Contacté</span>',
    interesse: '<span class="badge bg-accent text-accent-foreground">Intéressé</span>',
    "non-interesse": '<span class="badge bg-muted text-muted-foreground">Non intéressé</span>',
  }
  return badges[status] || badges["nouveau"]
}

// Formater la date
function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString("fr-FR")
}

// Mettre à jour le compteur de prospects
function updateProspectCount() {
  document.getElementById("prospectCount").textContent = prospects.length
}

// Afficher le modal d'ajout de prospect
function showAddProspectModal() {
  const modal = new bootstrap.Modal(document.getElementById("addProspectModal"))
  document.getElementById("prospectForm").reset()
  modal.show()
}

// Ajouter un prospect
function addProspect() {
  const form = document.getElementById("prospectForm")
  if (!form.checkValidity()) {
    form.reportValidity()
    return
  }

  const newProspect = {
    id: Date.now(),
    name: document.getElementById("prospectName").value,
    phone: document.getElementById("prospectPhone").value,
    email: document.getElementById("prospectEmail").value,
    status: document.getElementById("prospectStatus").value,
    address: document.getElementById("prospectAddress").value,
    notes: document.getElementById("prospectNotes").value,
    dateAdded: new Date().toISOString().split("T")[0],
  }

  prospects.push(newProspect)
  renderProspects()
  updateProspectCount()

  const modal = bootstrap.Modal.getInstance(document.getElementById("addProspectModal"))
  modal.hide()

  showToast("Prospect ajouté avec succès", "success")
}

// Filtrer les prospects
function filterProspects() {
  const searchTerm = document.getElementById("searchProspect").value.toLowerCase()
  const statusFilter = document.getElementById("statusFilter").value
  const dateFilter = document.getElementById("dateFilter").value

  const filteredProspects = prospects.filter((prospect) => {
    const matchesSearch =
      prospect.name.toLowerCase().includes(searchTerm) ||
      prospect.phone.includes(searchTerm) ||
      (prospect.email && prospect.email.toLowerCase().includes(searchTerm))

    const matchesStatus = !statusFilter || prospect.status === statusFilter
    const matchesDate = !dateFilter || prospect.dateAdded === dateFilter

    return matchesSearch && matchesStatus && matchesDate
  })

  renderFilteredProspects(filteredProspects)
}

// Afficher les prospects filtrés
function renderFilteredProspects(filteredProspects) {
  const tbody = document.getElementById("prospectsTableBody")
  tbody.innerHTML = ""

  filteredProspects.forEach((prospect) => {
    const row = document.createElement("tr")
    row.className = "hover:bg-muted/50 transition-colors duration-200"

    const statusBadge = getStatusBadge(prospect.status)

    row.innerHTML = `
            <td class="px-4 py-3 text-foreground font-medium">${prospect.nomProspect}</td>
            <td class="px-4 py-3 text-muted-foreground">${prospect.telephoneProspect}</td>
            <td class="px-4 py-3 text-muted-foreground">${prospect.emailProspect || "-"}</td>
            <td class="px-4 py-3 text-muted-foreground">${prospect.lieuProspection || "-"}</td>
            <td class="px-4 py-3">${statusBadge}</td>
            <td class="px-4 py-3 text-muted-foreground">${formatDate(prospect.dateCreateProspect)}</td>
            <td class="px-4 py-3 text-muted-foreground">${prospect.notesProspect}</td>
            <td class="px-4 py-3">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary btn-sm" onclick="editProspect(${prospect.idProspect})" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="addProspectToDescente(${prospect.idProspect})" title="Ajouter à une descente">
                        <i class="bi bi-geo-alt"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteProspect(${prospect.idProspect})" title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `

    tbody.appendChild(row)
  })
}

// Réinitialiser les filtres
function resetFilters() {
  document.getElementById("searchProspect").value = ""
  document.getElementById("statusFilter").value = ""
  document.getElementById("dateFilter").value = ""
  renderProspects()
}

// Afficher le modal de création de descente
function showAddDescenteModal() {
  const modal = new bootstrap.Modal(document.getElementById("addDescenteModal"))
  document.getElementById("descenteForm").reset()
  modal.show()
}

// Créer une descente
function createDescente() {
  const form = document.getElementById("descenteForm")
  if (!form.checkValidity()) {
    form.reportValidity()
    return
  }

  const newDescente = {
    id: Date.now(),
    name: document.getElementById("descenteName").value,
    date: document.getElementById("descenteDate").value,
    location: document.getElementById("descenteLocation").value,
    description: document.getElementById("descenteDescription").value,
    clients: [],
  }

  descentes.push(newDescente)
  populateDescenteSelect()

  const modal = bootstrap.Modal.getInstance(document.getElementById("addDescenteModal"))
  modal.hide()

  showToast("Descente créée avec succès", "success")
}

// Afficher le modal client pour descente
function showClientDescenteModal() {
  const modal = new bootstrap.Modal(document.getElementById("clientDescenteModal"))
  document.getElementById("clientDescenteForm").reset()
  populateDescenteSelect()
  modal.show()
}

// Peupler la liste des descentes
function populateDescenteSelect() {
  const select = document.getElementById("selectDescente")
  select.innerHTML = '<option value="">Choisir une descente...</option>'

  descentes.forEach((descente) => {
    const option = document.createElement("option")
    option.value = descente.idDescente
    option.textContent = `${descente.nomDescente} - ${formatDate(descente.dateDescente)}`
    select.appendChild(option)
  })
}

// Ajouter un client à une descente
function addClientToDescente() {
  const form = document.getElementById("clientDescenteForm")
  if (!form.checkValidity()) {
    form.reportValidity()
    return
  }

  const descenteId = Number.parseInt(document.getElementById("selectDescente").value)
  const client = {
    id: Date.now(),
    name: document.getElementById("clientName").value,
    phone: document.getElementById("clientPhone").value,
    email: document.getElementById("clientEmail").value,
    descenteId: descenteId,
  }

  const descente = descentes.find((d) => d.id === descenteId)
  if (descente) {
    descente.clients.push(client)
  }

  const modal = bootstrap.Modal.getInstance(document.getElementById("clientDescenteModal"))
  modal.hide()

  showToast("Client ajouté à la descente avec succès", "success")
}

// Afficher le modal de liste des descentes
function showDescenteListModal() {
  const modal = new bootstrap.Modal(document.getElementById("descenteListModal"))
  renderDescentes()
  modal.show()
}

// Afficher les descentes
function renderDescentes() {
  const tbody = document.getElementById("descenteTableBody")
  tbody.innerHTML = ""

  descentes.forEach((descente) => {
    const row = document.createElement("tr")
    row.className = "hover:bg-muted/50 transition-colors duration-200"

    row.innerHTML = `
            <td class="text-foreground font-medium">${descente.nomDescente}</td>
            <td class="text-muted-foreground">${formatDate(descente.dateDescente)}</td>
            <td class="text-muted-foreground">${descente.location || "-"}</td>
            <td class="text-muted-foreground">
                <span class="badge bg-primary text-primary-foreground">${descente.clients.length}</span>
            </td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary btn-sm" onclick="viewDescenteDetails(${descente.idDescente})" title="Voir détails">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="editDescente(${descente.idDescente})" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteDescente(${descente.idDescente})" title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `

    tbody.appendChild(row)
  })
}

// Exporter vers PDF
function exportToPDF() {
  const { jsPDF } = window.jspdf
  const doc = new jsPDF()

  // Titre
  doc.setFontSize(20)
  doc.text("Liste des Prospects", 20, 20)

  // Date d'export
  doc.setFontSize(12)
  doc.text(`Exporté le: ${new Date().toLocaleDateString("fr-FR")}`, 20, 30)

  // Préparer les données pour le tableau
  const tableData = prospects.map((prospect) => [
    prospect.name,
    prospect.phone,
    prospect.email || "-",
    prospect.status,
    formatDate(prospect.dateAdded),
  ])

  // Créer le tableau
  doc.autoTable({
    head: [["Nom", "Téléphone", "Email", "Statut", "Date Ajout"]],
    body: tableData,
    startY: 40,
    styles: {
      fontSize: 10,
      cellPadding: 3,
    },
    headStyles: {
      fillColor: [5, 150, 105],
      textColor: 255,
    },
  })

  doc.save("prospects.pdf")
  showToast("PDF exporté avec succès", "success")
}

// Exporter les descentes vers PDF
function exportDescentesToPDF() {
  const { jsPDF } = window.jspdf
  const doc = new jsPDF()

  // Titre
  doc.setFontSize(20)
  doc.text("Liste des Descentes", 20, 20)

  // Date d'export
  doc.setFontSize(12)
  doc.text(`Exporté le: ${new Date().toLocaleDateString("fr-FR")}`, 20, 30)

  // Préparer les données pour le tableau
  const tableData = descentes.map((descente) => [
    descente.nomDescente,
    formatDate(descente.dateDescente),
    descente.location || "-",
    descente.clients.length.toString(),
    descente.description || "-",
  ])

  // Créer le tableau
  doc.autoTable({
    head: [["Nom", "Date", "Lieu", "Clients", "Description"]],
    body: tableData,
    startY: 40,
    styles: {
      fontSize: 10,
      cellPadding: 3,
    },
    headStyles: {
      fillColor: [16, 185, 129],
      textColor: 255,
    },
  })

  doc.save("descentes.pdf")
  showToast("PDF des descentes exporté avec succès", "success")
}

// Fonctions utilitaires
function editProspect(id) {
  // Implémentation de l'édition
  showToast("Fonction d'édition à implémenter", "info")
}

function deleteProspect(id) {
  if (confirm("Êtes-vous sûr de vouloir supprimer ce prospect ?")) {
    prospects = prospects.filter((p) => p.id !== id)
    renderProspects()
    updateProspectCount()
    showToast("Prospect supprimé avec succès", "success")
  }
}

function addProspectToDescente(id) {
  // Implémentation pour ajouter un prospect à une descente
  showToast("Fonction à implémenter", "info")
}

function viewDescenteDetails(id) {
  // Implémentation pour voir les détails d'une descente
  showToast("Fonction de détails à implémenter", "info")
}

function editDescente(id) {
  // Implémentation de l'édition de descente
  showToast("Fonction d'édition de descente à implémenter", "info")
}

function deleteDescente(id) {
  if (confirm("Êtes-vous sûr de vouloir supprimer cette descente ?")) {
    descentes = descentes.filter((d) => d.id !== id)
    renderDescentes()
    populateDescenteSelect()
    showToast("Descente supprimée avec succès", "success")
  }
}

function filterDescentes() {
  const startDate = document.getElementById("startDate").value
  const endDate = document.getElementById("endDate").value

  let filteredDescentes = descentes

  if (startDate) {
    filteredDescentes = filteredDescentes.filter((d) => d.date >= startDate)
  }

  if (endDate) {
    filteredDescentes = filteredDescentes.filter((d) => d.date <= endDate)
  }

  // Render filtered descentes (implémentation similaire à renderDescentes)
  showToast(`${filteredDescentes.length} descente(s) trouvée(s)`, "info")
}

// Système de notifications toast
function showToast(message, type = "info") {
  // Créer l'élément toast
  const toast = document.createElement("div")
  toast.className = `alert alert-${type === "success" ? "success" : type === "error" ? "danger" : "info"} position-fixed`
  toast.style.cssText = "top: 20px; right: 20px; z-index: 9999; min-width: 300px;"
  toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-${type === "success" ? "check-circle" : type === "error" ? "exclamation-triangle" : "info-circle"} me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `

  document.body.appendChild(toast)

  // Supprimer automatiquement après 3 secondes
  setTimeout(() => {
    if (toast.parentElement) {
      toast.remove()
    }
  }, 3000)
}
