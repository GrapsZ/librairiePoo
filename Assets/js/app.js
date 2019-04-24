$(document).ready(function () {
    $('#dtBasicExample').DataTable({
        "lengthChange": false,
        "paging": false,
        "language": {
            "search": "Rechercher : ",
            "decimal":        "",
            "emptyTable":     "No data available in table",
            "info":           "Affichage _START_ sur _END_ pour _TOTAL_ livres",
            "infoEmpty":      "Affichage 0 sur 0 pour 0 livres",
            "infoFiltered":   "(filtré depuis _MAX_ livres au total)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Afficher _MENU_ livres",
            "loadingRecords": "Chargement...",
            "processing":     "Processing...",
            "zeroRecords":    "Aucun résultat trouvé",
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "Next",
                "previous":   "Previous"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });
    $('.dataTables_length').addClass('bs-select');
});