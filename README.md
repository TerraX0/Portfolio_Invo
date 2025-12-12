Nach jedem herunterladen des Projekts: der ordner public/assets muss durch einen Befehl aus dem Root-Ordner /assets mit den entsprechenden Dateien (CSS, JS etc) gefüllt werden: <br>
composer -V <br>
php bin/console asset-map:compile <br>
php bin/console cache:clear <br>

########################<br>
Bitte in templates/partials/sidebar.css an diesen zwei Stellen "sidebar-icon" durch "nav-icon" ersetzen<br>

                a href="{{ item.route }}" title="{{ item.label }}"> <br>
  img src="{{ asset(item.icon) }}" alt="{{ item.label }} Icon" class="sidebar-icon">  <br>
  
  und <br>
  
   a href="#" title="Logout"><br>
                {# Icons und Label für Logout ergänzt (Beispiel) #}<br>
    img src="{{ asset('images/icons/logout.svg') }}" alt="Logout Icon" class="sidebar-icon">   <br>
