# Le crud

## Pour accéder à une page
- La fonction {{path('nom_de_la_route')}}

## Pour créer un formulaire
- Créer le type du formulaire
    - L'associer à une entité si besoin
    - symfony console make:form ContactFormType
- Se baser sur le type du formulaire pour créer le formulaire 
    - dans la méthode du contrôleur souhaité
    - $form = $this->createForm(ContactFormType::class);
- Passer la partie visible du formulaire à la page(vue)
    - $form->createView();