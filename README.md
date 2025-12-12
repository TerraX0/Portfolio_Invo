Nach jedem herunterladen des Projekts: der ordner public/assets muss durch einen Befehl aus dem Root-Ordner /assets mit den entsprechenden Dateien (CSS, JS etc) gefüllt werden: <br>
composer -V <br>
php bin/console asset-map:compile <br>
php bin/console cache:clear 
