Contribution
============

Si vous desirez contribuer au projet *Todo and co*, voici la marche a suivre.

#### Pour signaler un bug/disfonctionnement :
Allez dans la partie "issues" et verifier si le problème est présent dans la liste.

 * Dans le cas où il serait déjà present :
  
  Vous pouvez participer à la discussion et donner votre propre retour d'experience pour nous aider à identifier le problème.
  
 * Dans le cas où il n'y serait pas :
 
 Créez un nouvel "issue" en décrivant le plus précisement possible votre retour d'expérience, accompagné des messages d'erreurs si possible.
 
#### Proposer une amélioration/fonctionnalite :

***Voir la partie sur les standards et conventions avant de contribuer au projet*** 

La première étape sera de faire un "fork" du projet sur votre compte GitHub.

Créez une nouvelle branche (ex: "dev") sur ce "fork" pour développer votre fonctionnalité avec les tests unitaires.

Une fois votre développement terminé, dans votre "repository" cliquer sur "New pull Request".

 * Selectionnez NOTRE "repository" comme "base fork" avec la branche "master".
 * Selectionnez VOTRE "repository" comme "head fork" avec la branche "dev".
 
Donnez un titre explicite à votre "pull request", puis détaillez le plus clairement possible votre travail dans les commentaires.

Dans le cas où votre "pull request" repondrait à une "issue" pensez à mettre la référence de celle-ci dans vos commentaires.

#### Standards et Conventions

Toutes les "pull-request" devront respecter les standards et les conventions du Framework Symfony 3.3,
mais également la methode TDD (Test-Driven development ou en français développement piloté par les tests).

Veuillez consulter pour celà la documentation de Symfony à propos des [conventions](https://symfony.com/doc/current/contributing/code/conventions.html)
et des [standards](https://symfony.com/doc/current/contributing/code/standards.html)
