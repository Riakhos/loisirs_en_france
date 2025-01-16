document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.querySelector('#invitationModal');
    const form = document.querySelector('#birthdayCardForm');

    // Mapping des champs de formulaire et de prévisualisation
    const fieldsToPreview = {
        howOld: document.querySelector('#personalize_birthday_card_howOld'),
        theme: document.querySelector('#personalize_birthday_card_theme'),
        when: document.querySelector('#personalize_birthday_card_when'),
        where: document.querySelector('#personalize_birthday_card_where'),
        phone: document.querySelector('#personalize_birthday_card_phone'),
        firstName: document.querySelector('#personalize_birthday_card_firstName'),
    };

    const previewElements = {
        howOld: document.querySelector('#preview_howOld'),
        theme: document.querySelector('#preview_theme'),
        when: document.querySelector('#preview_when'),
        where: document.querySelector('#preview_where'),
        phone: document.querySelector('#preview_phone'),
        firstName: document.querySelector('#preview_firstName'),
    };

    // Valeurs par défaut pour la réinitialisation
    const previewDefaults = {
        howOld: '42',
        theme: 'Soirée déguisée',
        when: '28/01/2025 15h30',
        where: '138 avenue Jean Jaurès<br>95100 Argenteuil',
        phone: '06.03.16.53.51',
        firstName: 'Richard',
    };

    // Synchroniser les champs de formulaire avec la prévisualisation
    Object.keys(fieldsToPreview).forEach(key => {
        fieldsToPreview[key].addEventListener('input', function (event) {
            const value = event.target.value;
            previewElements[key].innerHTML = value || previewDefaults[key];
        });
    });

    // Réinitialiser le formulaire et la prévisualisation à la fermeture de la modal
    modalElement.addEventListener('hidden.bs.modal', function () {
        // Réinitialiser le formulaire
        form.reset();

        // Réinitialiser la prévisualisation
        Object.keys(previewDefaults).forEach(key => {
            previewElements[key].innerHTML = previewDefaults[key];
        });
    });
});
