// 1. Toggle sidebar (existant)
const hamb = document.querySelector(".toggle-btn");
const toggler = document.querySelector("#icon");

hamb.addEventListener("click", function() {
    document.querySelector("#sidebar").classList.toggle("expand");
    toggler.classList.toggle("bx-chevrons-right");
    toggler.classList.toggle("bx-chevrons-left");
});

// 2. Fonction de chargement des données
async function loadDashboardData() {
    try {
        // On fait une requête au backend pour récupérer les données
        const response = await fetch('./admin.php');
        if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
        
        // On récupère les données sous forme de JSON
        const data = await response.json();
        return data; // On retourne les données
    } catch (error) {
        console.error('Erreur de chargement:', error);
        // En cas d'erreur, on retourne des valeurs par défaut
        return {
            users: [],
            stats: {
                total_users: 0,
                total_events: 0,
                month_events: 0
            },
            chart_data: {
                labels: [],
                values: []
            }
        };
    }
}

// 3. Mise à jour de l'interface
function updateUI(data) {
    // a. Mise à jour des cartes stats
    document.querySelectorAll('.card-body p.fw-bold')[0].textContent = data.stats.total_users;
    document.querySelectorAll('.card-body p.fw-bold')[1].textContent = data.stats.total_events;
    document.querySelectorAll('.card-body p.fw-bold')[2].textContent = data.stats.month_events;

    // b. Remplissage du tableau users
    const tableBody = document.querySelector('table tbody');
    tableBody.innerHTML = ''; // On vide le tableau avant de le remplir

    // On parcourt les utilisateurs et on crée une ligne pour chaque utilisateur
    data.users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <th scope="row">${user.id}</th>
            <td>${user.first_name}</td>
            <td>${user.last_name}</td>
            <td>${user.email}</td>
        `;
        tableBody.appendChild(row); // On ajoute la ligne au tableau
    });

    // c. Mise à jour du graphique
    renderChart(data.chart_data.labels, data.chart_data.values);
}

// 4. Fonction pour le graphique
function renderChart(labels, values) {
    const ctx = document.getElementById('bar-chart-grouped').getContext('2d');

    // Si un graphique existe déjà, on le détruit avant de le recréer
    if (window.userChart) {
        window.userChart.destroy();
    }

    // On crée un nouveau graphique avec les nouvelles données
    window.userChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Utilisateurs par rôle',
                backgroundColor: ['#3e95cd', '#8e5ea2', '#3cba9f'],
                data: values
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Répartition des utilisateurs'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

// 5. Initialisation au chargement
document.addEventListener('DOMContentLoaded', async () => {
    const dashboardData = await loadDashboardData();
    updateUI(dashboardData); // Mise à jour de l'interface avec les données récupérées
});
