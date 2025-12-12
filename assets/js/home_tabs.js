document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.js-tabs-link');
    const sections = document.querySelectorAll('.js-tab-section');
    const headerTitle = document.getElementById('js-dynamic-header');

    // Überprüfen, ob es Tabs gibt
    if (tabs.length > 0) {
        // Setze den Titel auf den ersten Tab beim Laden der Seite
        headerTitle.textContent = tabs[0].textContent.trim();
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function (event) {
            //Verhindere, dass die Seite neu geladen wird bei Tab-Umschaltung
            event.preventDefault();

            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            sections.forEach(section => {
                section.style.display = 'none';
            });

            const target = document.getElementById(this.id + '-section');
            if (target) {
                target.style.display = 'block';
            }

            headerTitle.textContent = this.textContent.trim();
        });
    });
});