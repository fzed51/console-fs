# console-fs

Ensemble de fonctions permettant de manipuler les fichiers système

## Installation

```shell
composer require fzed51/console-fs
```

## Usage

### Directory

Nom de la class : `\Console\FileSystem\Directory`

#### create

Fonction statique pour créer un dossier

```php
Directory::create('nom_nouveau_dossier'); // new Directory('nom_nouveau_dossier')
```

Si le nom de dossier n'est pas absolue, le dossier courant est pris en compte. Retourne une instance Directory du
nouveau dossier.

#### delete

Fonction statique pour supprimer un dossier

```php
Directory::delete('nom_dossier');
```

Supprime un dossier, lève une exception si le dossier n'est pas vide

```php
Directory::delete('nom_dossier', true);
```

Utiliser le 2eme argument pour supprimer un dossier non vide

#### exists

Fonction statique pour tester l'existance d'un dossier

```php
Directory::exist('nom_dossier') // true|false;
```

#### getName

Méthode qui donne le nom du dossier

```php
$dir = new Directory('./chemin/nom_dossier');
$dir->getName(); // "nom_dossier"
```

#### getFullName

Méthode qui donne le nom complet du dossier

```php
$dir = new Directory('./chemin/nom_dossier');
$dir->getFullName(); // "c:/chemin_absolut/chemin/nom_dossier"
```

#### empty

Méthode qui test si un dossier est vide

```php
$dir = new Directory('./chemin/nom_dossier');
$dir->empty(); // true|false
```

#### ls

Méthode qui liste les fichiers d'un dossier

```php
$dir = new Directory('./chemin/nom_dossier');
$dir->ls(); // ["fichier1"]
$dir->ls(true); // ["fichier1", "sub/fichier2"]
```

#### lsDirectory

Méthode qui liste les dossiers d'un dossier

```php
$dir = new Directory('./chemin/nom_dossier');
$dir->lsDirectory(); // ["sub"]
$dir->lsDirectory(true); // ["sub", "sub/sub-sub"]
```

#### copy

Méthode qui copy un dossier

```php
$dir = new Directory('./chemin/nom_dossier');
$dir->copy('destination') // new Directory('destination')
```

Retourne une instance Directory du dossier de destination

### File

Nom de la class : `\Console\FileSystem\File`

#### delete

Fonction statique pour supprimer un fichier

```php
File::delete('nom_fichier');
```

#### exists

Fonction statique pour tester l'existance d'un fichier

```php
File::exist('nom_fichier') // true|false;
```

#### getName

Méthode qui donne le nom du fichier

```php
$file = new File('./chemin/nom_fichier.ext');
$file->getName(); // "nom_fichier.ext"
```

#### getFullName

Méthode qui donne le nom complet du fichier

```php
$file = new File('./chemin/nom_fichier.ext');
$file->getFullName(); // "c:/chemin_absolut/chemin/nom_fichier.ext"
```

#### copy

Méthode qui copy un fichier

```php
$file = new File('./chemin/nom_fichier.ext');
$file->copy('directory_destination') // new File('./directory_destination/nom_fichier.ext')
$file->copy('nouveau/nom/') // new File('./nouveau/nom/nom_fichier.ext')
$file->copy('nouveau/nom') // new File('./nouveau/nom')
$file->copy('directory_destination/nouveau_nom.ext') // new File('./directory_destination/nouveau_nom.ext')
```

Retourne une instance Directory du dossier de destination
Si la destination est un dossier ou fini par un séparateur de dossier, alors le fichier garde son nom. Sinon le fichier
prend le nom de la destination.
