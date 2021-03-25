(function($){
  $(function(){

    $('.sidenav').sidenav();

    $('.parallax').parallax();

    $('select').formSelect();

    $('.datepicker').datepicker(
      {
        minDate: new Date(+new Date() + 86400000),
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 1, // Creates a dropdown of 15 years to control year
        i18n: {
          labelMonthNext: 'Mois suivant',
          labelMonthPrev: 'Mois précédent',
          labelMonthSelect: 'Selectionner le mois',
          labelYearSelect: 'Selectionner une année',
          months: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
          monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
          weekdays: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
          weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
          weekdaysAbbrev: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
          today: 'Aujourd\'hui',
          clear: 'Réinitialiser',
          cancel: 'Fermer'
        },
        format: 'dd/mm/yyyy',
        firstDay: 1,
        autoClose: true
      }
    );
   
  }); // end of document ready
})(jQuery); // end of jQuery name space
