-----docs: https://symfony.com/doc/current/setup.html ------

# create projet

composer create-project symfony/website-skeleton studentapp

# start project

symfony server:start

# importation de namespace

PHP Namespace Resolver
PHP DocBlocker

# inerpollation

{{ }}

# boucle for

loop.index = 1 a ..
loop.index0 = 0...
loop.first
loop.last

# create database

php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# mettre à jour la base de données

php bin/console doctrine:schema:update --force

# fixtures pour les données fictives

composer require orm-fixtures --dev
composer require fzaninotto/faker

# créer une fixtures

php bin/console make:fixtures

# 8. remplir la base de données avec de fauses données grâce à fixtures

    php bin/console doctrine:fixtures:load --no-interaction

# create controller

php bin/console make:controller StudentController

# déclarer une variable

{% set url = path('students_index') %}
note: on l'appelle partout où l'on veut dans le template

# create formulaire

php bin/console make:form
::::: {{ form_widget(form.title) }} :: pour un champs
{{ form_row(form.title) }} :: affiche le label et gestion erreur

# définir le theme par défaut dans tous les pages html

# config/packages/twig.yaml

twig:
form_themes: ['bootstrap_4_layout.html.twig']

# CRUD

php bin/console make:crud [Entity]

{# <div class="form-group">

<!-- {{ form_label(form.nom) }}
{{ form_widget(form.nom, {'attr': {'class': 'form-control', 'placeholder': 'Nom de l\'étudiant'}}) }}
</div>
<div class="form-group">
{{ form_label(form.prenom) }}
{{ form_widget(form.prenom, {'attr': {'class': 'form-control', 'placeholder': 'Prénom de l\'étudiant'}}) }}
</div>
<div class="form-group">
{{ form_label(form.dateNaissance) }}
{{ form_widget(form.dateNaissance, {'attr': {'class': 'form-control', 'placeholder': 'Date de naissance de l\'étudiant'}}) }}
</div>
<div class="form-group">
{{ form_label(form.age) }}
{{ form_widget(form.age, {'attr': {'class': 'form-control', 'placeholder': 'Age de l\'étudiant'}}) }}
</div>
</div> -->

#}
