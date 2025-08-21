// Exécute après que le DOM soit prêt, et supporte deux classes possibles de boutons
document.addEventListener('DOMContentLoaded', () => {
  const boutons = document.querySelectorAll('.resultat_vote, .bouton_pour_voir_resultat');
  if (!boutons || boutons.length === 0) {
    console.debug('Aucun bouton de résultat trouvé (.resultat_vote or .bouton_pour_voir_resultat)');
  }

  // Boucle sur chacun des boutons trouvés
  boutons.forEach(bouton => {

    // Ajoute un écouteur d'événement "click" sur chaque bouton
    bouton.addEventListener("click", () => {

      // Récupère l'ID du vote associé à ce bouton depuis l'attribut data
      const id = bouton.getAttribute("data-id-du-btn-de-resultat-du-vote");

      // Récupère les données correspondant à ce vote depuis l'objet global "données_pour_charts"
      const data = window.données_pour_charts && window.données_pour_charts[id];

      // Si aucune donnée n'existe pour cet ID, on affiche une erreur et on sort
      if (!data) {
        console.error("Pas de données pour ce vote", id, 'window.données_pour_charts =', window.données_pour_charts);
        return;
      }

    // -----------------------------
    // --- PIE CHART (Camembert) ---
    // -----------------------------
  const canvasPie = document.getElementById(`graphe-${id}`); // récupère le canvas pour le pie chart
    if (canvasPie) { // vérifie que le canvas existe
      canvasPie.width = 500;  // fixe la largeur du canvas
      canvasPie.height = 500; // fixe la hauteur du canvas

      // Crée un nouveau graphique Chart.js
      new Chart(canvasPie.getContext('2d'), {
        type: 'pie', // type du graphique: camembert
        data: {
          labels: data.labels, // étiquettes pour chaque section du camembert
          datasets: [{
            data: data.values, // valeurs numériques correspondantes aux étiquettes
            backgroundColor: [ // couleurs pour chaque section
              'rgba(255, 99, 132, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 206, 86, 0.7)',
              'rgba(75, 192, 192, 0.7)',
              'rgba(153, 102, 255, 0.7)',
              'rgba(255, 159, 64, 0.7)'
            ],
            borderColor: '#fff', // couleur de bordure de chaque section
            borderWidth: 2 // largeur de la bordure
          }]
        },
        options: {
          responsive: false, // désactive le redimensionnement automatique
          maintainAspectRatio: false, // ignore le ratio automatique largeur/hauteur
          animation: { // paramètres d'animation
            animateRotate: true, // rotation du camembert à l'affichage
            animateScale: true,  // effet "zoom" depuis le centre
            duration: 1200,       // durée de l'animation en ms
            easing: 'easeOutQuart' // type de courbe de l'animation
          },
          plugins: { // configuration des plugins (titre, légende, tooltip)
            title: {
              display: true,          // affiche le titre
              text: 'Résultats du vote',
              font: { size: 20, weight: 'bold' }, // style du texte
              color: '#333'           // couleur du titre
            },
            subtitle: {
              display: true,          // affiche le sous-titre
              text: 'Répartition des choix des différents votants',
              font: { size: 14, style: 'italic' }, // style du sous-titre
              color: '#666'
            },
            legend: {
              position: 'bottom',      // position de la légende
              labels: {
                color: '#333',        // couleur du texte
                font: { size: 14 },   // taille du texte
                boxWidth: 20,         // largeur de la boîte de couleur
                padding: 15           // espace entre items
              }
            },
            tooltip: { // tooltip affiché au survol
              callbacks: {
                label: function(context) { // fonction pour formater le texte
                  const total = context.dataset.data.reduce((a, b) => a + b, 0); // somme totale
                  const value = context.raw; // valeur du segment
                  const pourcentage = ((value / total) * 100).toFixed(2); // calcule % avec 2 décimales
                  return `${context.label}: ${value} votes (${pourcentage}%)`; // texte final
                }
              }
            }
          }
        }
      });
    }

    // ----------------------------------
    // --- BAR CHART VERTICAL (Barres) ---
    // ----------------------------------
  const canvasBar = document.getElementById(`schema-${id}`); // récupère le canvas pour le bar chart
    if (canvasBar) { // vérifie que le canvas existe
      canvasBar.width = 500;  // largeur fixe
      canvasBar.height = 500; // hauteur fixe

      new Chart(canvasBar.getContext('2d'), {
        type: 'bar', // type du graphique: barres verticales
        data: {
          labels: data.labels, // étiquettes sur l'axe X
          datasets: [{
            label: 'Nombre de votes', // légende du dataset
            data: data.values,       // valeurs numériques
            backgroundColor: [ // couleurs des barres
              'rgba(255, 99, 132, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 206, 86, 0.7)',
              'rgba(75, 192, 192, 0.7)',
              'rgba(153, 102, 255, 0.7)',
              'rgba(255, 159, 64, 0.7)'
            ],
            borderColor: '#fff', // bordure blanche
            borderWidth: 2,      // épaisseur de la bordure
            borderRadius: 6      // coins arrondis pour un style plus pro
          }]
        },
        options: {
          responsive: false,        // désactive l'adaptation automatique
          maintainAspectRatio: false,
          animation: {              // animation du graphique
            duration: 1200,
            easing: 'easeOutQuart'
          },
          plugins: {                // plugins pour titre, légende, tooltip
            title: {
              display: true,
              text: 'Résultats du vote (Bar Chart)',
              font: { size: 20, weight: 'bold' },
              color: '#333'
            },
            subtitle: {
              display: true,
              text: 'Répartition des choix exprimés',
              font: { size: 14, style: 'italic' },
              color: '#666'
            },
            legend: { display: false }, // pas de légende
            tooltip: { // tooltip personnalisé
              callbacks: {
                label: function(context) {
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const value = context.raw;
                  const pourcentage = ((value / total) * 100).toFixed(2);
                  return `${context.label}: ${value} votes (${pourcentage}%)`;
                }
              }
            }
          },
          scales: { // configuration des axes
            x: {
              ticks: { color: '#333', font: { size: 12 } }, // style texte axe X
              grid: { color: 'rgba(0,0,0,0.05)' }           // couleur grille X
            },
            y: {
              beginAtZero: true,     // commence à zéro
              stepSize: 1,           // incrément des ticks en entier
              ticks: { color: '#333', font: { size: 12 } }, // style texte Y
              grid: { color: 'rgba(0,0,0,0.05)' }           // couleur grille Y
            }
          }
        }
      });
    }

    }); // fin du click event
  }); // fin de la boucle forEach sur les boutons
}); // fin DOMContentLoaded
