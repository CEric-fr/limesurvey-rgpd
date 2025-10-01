# limesurvey-rgpd
Extension RGPD pour LimeSurvey

Le règlement général sur la protection des données (RGPD) est entré en application le 25 mai 2018. Afin de respecter ce règlement de l'union européenne, les questionnaires de l'outil académique Limesurvey affichent désormais en page d'accueil la politique de protection des données.
Chaque propriétaire de questionnaire est en mesure d’interagir sur ces conditions d'utilisation par l'intermédiaire d'un nouveau sous-menu ajouté dans les "paramètres du questionnaire" intitulé : **Configuration RGPD**.



### Page de configuration RGPD d'un questionnaire


Chaque questionnaire dispose d'une personnalisation des différents champs placés dans les conditions d'utilisation des données personnelles.

* **Responsable** : le nom du responsable du traitement (ce champ peut rester vide, il est alors remplacé par celui de la configuration générale inscrit en grisé dans le champ correspondant).

* **Structure** : le nom de la structure qui réceptionne les données (ce champ peut rester vide, il est alors remplacé par celui de la configuration générale inscrit en grisé dans le champ correspondant).

* **Détail de la finalité du traitement** : la description en détail de la finalité du traitement (ce champ peut rester vide : la finalité par défaut proposée par la configuration générale peut être suffisante).

* **Service** : le nom du service qui réceptionne les données (ce champ peut rester vide, il est alors remplacé par celui de la configuration générale inscrit en grisé dans le champ correspondant).

* **Nom du responsable du questionnaire** : le nom de la personne qui réceptionne les données (ce champ est complété automatiquement avec le nom du propriétaire du questionnaire).

* **Courriel du responsable du questionnaire** : le courriel de la personne qui réceptionne les données (ce champ est complété automatiquement avec le courriel du propriétaire du questionnaire).

* **Contenu du texte RGPD du questionnaire** : ce contenu non éditable affiche les conditions d'utilisations telles qu'elles s'afficheront aux utilisateurs du questionnaire en prenant en compte les champs complétés en amont. Les champs sont signalés par du texte en vidéo inversée. Une info-bulle permet d'identifier le nom du champ.

Nota : le code HTML est interdit dans ces champs.

### Page de configuration générale

Uniquement accessible aux administrateurs généraux.
Cette page permet de configurer le plugin RGPD, elle est divisée en quatre groupes :
* **Valeurs par défaut pour chaque questionnaire** : élément par défaut constituant le texte du RGPD ces champs peuvent être définis pour chaque questionnaire.
	* **Responsable** : le nom du responsable du traitement.
	* **Structure** : le nom de la structure qui réceptionne les données.
	* **Détail de la finalité du traitement** : texte complémentaire du questionnaire pour la finalité du traitement.
	* **Service** : le nom du service qui réceptionne les données.
* **Texte du RGPD** : le texte des conditions d'utilisation et de traitement des données du questionnaire, il contient des variables `##rgpd_def_NOM##` qui seront remplacées par les valeurs des champs du premier groupe ou ceux rempli dans le questionnaire. Peut accepter tout champ donc le commentaire contient un nom de variable.
* **Paramètres généraux** :
	* *Nom de domaine des courriels* : le nom de domaine des courriels à transformer en lien "mailto" (l'éditeur de texte de **LimeSurvey** ne permettant pas de faire ce genre de lien).
	* **Attribut ALT du logo** : permet de définir l'attribut "ALT" de l'image du logo afficher en haut-gauche de la page du RGPD. Pour changer le logo, mettre dans le répertoire `/rgpd/assets/` une image au format *gif*, *png* ou *jpg* dont le nom commence par "logo_". La première image trouvé est retenue et est signaler en commentaire de cette variable.
* **Gestion des données** : permet de définir la durée de conservation des données et d'activer le lancer d'un "CRON" qui permet de faire le nettoyage des données au-delà.
	* **Durée de conservation des données (en mois)** : durée de conservation des données en mois.  On utilise la date de dernière modification de la table.
	* **Activer la suppression automatique des données au delà de la durée de conservation** : Active ou non la gestion automatique de conservation des données. Le commentaire contient aussi l'information de la date de la prochaine vérification automatique.
	* **Périodicité de la suppression automatique (en jours)** : si la gestion est activée, permet de définit la période à laquelle s'exécute le processus. Valeur au choix dans : 7, 15, 30 ou 60 jours.
	* **Lancer maintenant le processus de suppression** : permet de faire exécuter de suite le processus en cochant la case et en validant par *Sauvegarde*. Ne fonctionne que si la suppression automatique est activée. Cette case reste toujours décochée.

### Installation

Cette version n'a été testé qu'avec la version *3.22.1+200129* de *LimeSurvey*.

Copier le contenu dans le répertoire `/plugins/rgpd/` de *LimeSurvey*.

Aller dans la partie administrative *Configuration* -> *Gestionnaire d'extensions* -> *Activer* la ligne du plugin. Un item apparait alors dans le menu horizontal : *RGPD* contenant *Configuration générale*. Un item apparait aussi dans le menu vertical des questionnaires : *Configuration RGPD*.

### Fonctionnement

Cette extension rend obligatoire l'affichage d'une page de condition d'utilisation avant l'entrée dans un questionnaire et demande la validation du texte d'information sur la collecte des données à caractère personnel (RGPD).

Le processus de nettoyage supprime les tables "old" et vide les tables de réponses des questionnaires valides dont la date de conservation est dépassée. Il supprime aussi les tables de réponses dont le questionnaire est supprimé.
