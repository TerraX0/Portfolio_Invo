# Invo Project - Frontend Mockup
A frontend structure and visual mockup for the "Invo" portfolio project.
<br>**🌐 Live Demo:** You can view a mockup on my website: [alinakoellner.de](https://alinakoellner.de)

### What it does:
This repository demonstrates my skills in modern web layout design, structured HTML5/CSS3 coding, and component architecture. It serves as a visual and structural foundation for managing project portfolios.

**Originally Concept & Context:**  
The "Invo" project was originally conceptualized and built during my internship at the Main Police Directorate of Lower Saxony (Polizeidirektion Niedersachsen). It was designed as a secure and structured system for storing, searching, modifying, and outputting personal data. This repository showcases the frontend interface and layout architecture inspired by that project.

---

### Setup & Local Installation
Every time after cloning or downloading the project, the `public/assets` folder must be generated and compiled from the root `/assets` directory using Symfony's AssetMapper. 

Run the following commands from your root directory:
```bash
composer -V
php bin/console asset-map:compile
php bin/console cache:clear
```
---
In templates/partials/sidebar.css (or the corresponding Twig template), remember to replace sidebar-icon with nav-icon in these two locations:

    In the navigation loop:

HTML
```bash
<a href="{{ item.route }}" title="{{ item.label }}">
    <img src="{{ asset(item.icon) }}" alt="{{ item.label }} Icon" class="nav-icon">
</a>
```
---
    In the logout link:

HTML
```bash
<a href="#" title="Logout">
    {# Icons and label added for Logout #}
    <img src="{{ asset('images/icons/logout.svg') }}" alt="Logout Icon" class="nav-icon">
</a>
```
---
### **Setup & Local Installation in German**
Nach jedem herunterladen des Projekts: der ordner public/assets muss durch einen Befehl aus dem Root-Ordner /assets mit den entsprechenden Dateien (CSS, JS etc) gefüllt werden: <br>
```bash
composer -V
php bin/console asset-map:compile
php bin/console cache:clear
```
<br>
In templates/partials/sidebar.css an diesen zwei Stellen "sidebar-icon" durch "nav-icon" ersetzen<br>

```bash
                a href="{{ item.route }}" title="{{ item.label }}">
  img src="{{ asset(item.icon) }}" alt="{{ item.label }} Icon" class="sidebar-icon"> 
```
  
  und <br>
  ```bash
   a href="#" title="Logout"><br>
                {# Icons und Label für Logout ergänzt (Beispiel) #}<br>
    img src="{{ asset('images/icons/logout.svg') }}" alt="Logout Icon" class="sidebar-icon">
```
