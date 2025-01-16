document.addEventListener("DOMContentLoaded", function () {
    const formName = "personalize_birthday_card";

    // Fonction pour sauvegarder les valeurs dans localStorage
    function saveFormData() {
        const formData = {};
        const inputs = document.forms[formName].elements;
        for (let input of inputs) {
            if (input.name) {
                formData[input.name] = input.value;
            }
        }
        localStorage.setItem(formName, JSON.stringify(formData));
    }

    // Fonction pour restaurer les valeurs depuis localStorage
    function restoreFormData() {
        const savedData = localStorage.getItem(formName);
        if (savedData) {
            const formData = JSON.parse(savedData);
            const inputs = document.forms[formName].elements;
            for (let input of inputs) {
                if (input.name && formData[input.name] !== undefined) {
                    input.value = formData[input.name];
                    // Mettre à jour les champs du displayForm
                    const displayField = document.querySelector(`.personalize_birthday_card_${input.name.split("[")[1].split("]")[0]}`);
                    if (displayField) {
                        displayField.textContent = input.value;
                    }
                }
            }
        }
    }

    // Sauvegarder les données sur chaque modification
    const inputs = document.forms[formName].elements;
    for (let input of inputs) {
        input.addEventListener("input", saveFormData);
    }

    // Restaurer les données à l'ouverture de la modal
    const modal = document.getElementById("invitationModal");
    modal.addEventListener("show.bs.modal", restoreFormData);

    // Ajouter le comportement pour le bouton de soumission
    document.getElementById("personalize_birthday_card_submit").addEventListener("click", function (event) {
        // Empêche l'envoi classique du formulaire
        event.preventDefault();

        // Récupérer les données du formulaire
        const formData = new FormData(document.forms[formName]);

        // Afficher les données pour vérifier
        formData.forEach(function(value, key) {
            console.log(key + ': ' + value);
			
			// Assurez-vous que les données sont bien des valeurs simples (pas d'objets ou de tableaux)
			if (typeof value !== 'string' && typeof value !== 'number') {
				alert('Erreur : ' + key + ' contient une valeur non valide.');
			}
        });

        // Envoyer les données au serveur
        fetch("/generate-invitation-pdf", {
            method: "POST",
            body: formData,
        })
        .then(response => {
            if (response.ok) return response.blob();
            throw new Error("Erreur lors de la génération du PDF");
        })
        .then(blob => {
            const pdfURL = window.URL.createObjectURL(blob);
            window.open(pdfURL, "_blank");
        })
        .catch(error => {
            console.error(error);
            alert("Une erreur est survenue. Veuillez réessayer.");
        });
    });
});
