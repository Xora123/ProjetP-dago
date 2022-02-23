

(function($){
    console.log(plugin_ajax_object);
    $(document).ready(function() {

        $('body').on('click','.fakeButton',function(){
            $(this).hide();
            $(this).parent().find($('.textFakeButton')).show();
        })
        $('body').on('click','.btnAnnuler',function(){
            $(this).parent().parent().find($('.fakeButton')).show();
            $(this).parent().parent().find($('.textFakeButton')).hide();
        })
        
        $('.texte_scan_en_cours').fadeIn().delay(5000).fadeOut();
    });


        function callback(){

        }

       
 // Ajout de la modification du Tableau Settings
    $('#tab').dataTable({
        "processing" : true,
        "serverSide" : true,
        ajax : {
            url : "http://localhost:10020/?username=root&db=local&table=wp_david_sites", // Aller sur un fichier php pour aovir une url 
        },
        language: {
            lengthMenu: "Afficher _MENU_ éléments",
            search : "Rechercher :",
            info: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            paginate: {
                "first":      "Premier",
                "last":       "Dernier",
                "next":       "Suivant",
                "previous":   "Précédent"
            },
            infoEmpty: "",
            emptyTable: "",
            zeroRecords: "",
            loadingRecords: "Chargement...",
            processing: "En cours..."
        },
        pagingType: "simple_numbers",
        lengthMenu:[10,20,30],
        pageLength: 10,
    });
})(jQuery);
