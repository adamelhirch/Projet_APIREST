function showPopup(numLicence) {
    const popup = document.getElementById('popup');
    popup.style.display = 'block';

    document.getElementById('modify-link').href = 'modifier_joueur.php?numero_licence=' + numLicence;
    document.getElementById('delete-link').href = '?delete=true&numero_licence=' + numLicence;
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
}
    // Fermer le popup en cliquant en dehors
window.addEventListener('click', function(event) {
    const popup = document.getElementById('popup');
    if (event.target === popup) {
        closePopup();
    }
});
/*------------------------------popup match-------------------------*/

function viewSelection(ID_Match) {
    // Redirige vers la page de la sélection des joueurs pour un match donné
    window.location.href = 'page_selection.php?ID_Match=' + ID_Match;
}

function showPopupMatch(ID_Match) {
    const popupMatch = document.getElementById('popup_match');
    popupMatch.style.display = 'block';

    document.getElementById('modify-link-match').href = 'Page_Modifier_Match.php?ID_Match=' + ID_Match;
    document.getElementById('delete-link-match').href = '?delete=true&ID_Match=' + ID_Match;
}

function closePopupMatch() {
    document.getElementById('popup_match').style.display = 'none';
}

// Fermer le popup en cliquant en dehors
window.addEventListener('click', function(event) {
    const popupMatch = document.getElementById('popup_match');
    if (event.target === popupMatch) {
        closePopupMatch();
    }
});


/*-----------------------------------------popup ajouter match ----------------------------*/

// Liste pour stocker les joueurs assignés
const joueursAssignes = {};

// Affiche le popup et assigne le poste sélectionné
function showpopupAjouterMatch(posteId) {
    document.getElementById('poste_id').value = posteId; // Met à jour le poste ID dans un champ caché
    document.getElementById('popupAjouterMatch').style.display = 'flex'; // Affiche le popup
}

// Cache le popup
function hidepopupAjouterMatch() {
    document.getElementById('popupAjouterMatch').style.display = 'none'; // Cache le popup
}

// Fermer le popup si on clique en dehors du contenu
window.addEventListener('click', function (event) {
    const popup = document.getElementById('popupAjouterMatch');
    if (event.target === popup) {
        hidepopupAjouterMatch();
    }
});

/*-----------------------------------------popup fuille de match ----------------------------*/
let currentPlayerId = null;

function openCommentPopup(playerId) {
    currentPlayerId = playerId;
    document.getElementById('popup-commentaire').style.display = 'flex';
}

function openNotePopup(playerId) {
    currentPlayerId = playerId;
    document.getElementById('popup-note').style.display = 'flex';
}

function closePopup(popupId) {
    document.getElementById(popupId).style.display = 'none';
    currentPlayerId = null;
}

function saveComment() {
    const comment = document.getElementById('commentaire-text').value;
    alert(`Commentaire pour le joueur ${currentPlayerId} : ${comment}`);
    closePopup('popup-commentaire');
}

function saveNote() {
    const note = document.getElementById('note-value').value;
    alert(`Note pour le joueur ${currentPlayerId} : ${note}/10`);
    closePopup('popup-note');
}

/* ------------------popup page selection-----------------------*/

// Fonction pour ouvrir le popup
function openPopup(numeroDeLicence) {
    document.getElementById('popup_match').style.display = 'block';
    document.getElementById('popup_numero_licence').value = numeroDeLicence;
}

// Fonction pour fermer le popup
function closePopup() {
    document.getElementById('popup_match').style.display = 'none';
}

window.addEventListener('click', function(event) {
    const popupMatch = document.getElementById('popup_match');
    if (event.target === popupMatch) {
        closePopupMatch();
    }
});

/*-------------------vérifier si le nombre de seletion est bonne----------------------*/
function validateForm() {
    const checkboxes = document.querySelectorAll('.titulaire-checkbox');
    const selectedCount = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;

    if (selectedCount !== 11) {
        alert(`Erreur : Vous devez sélectionner exactement 11 titulaires. Vous avez actuellement sélectionné ${selectedCount} joueur(s).`);
        return false; // Empêche la soumission du formulaire
    }

    return true; // Permet la soumission si tout est valide
}

document.addEventListener("DOMContentLoaded", function () {
    if (typeof isSuccess !== "undefined" && isSuccess) {
        alert("Les modifications ont été enregistrées avec succès.");
        window.location.href = "page_match.php";
    }
});
