<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#example').DataTable();
        $('#customSearchBox').on('keyup', function() {
            table.search(this.value).draw();
        });
    });

//     $(document).ready(function() {
//             function initializeDataTable(tableId) {
//                 var tableElement = $(tableId);
//                 if (tableElement.length) {
//                     var disableSortingColumns = tableElement.data('disablesortingcolumns');
//                     var columns = disableSortingColumns.split(',').map(Number);


//                     if ($.fn.dataTable.isDataTable(tableId)) {

//                         tableElement.DataTable().destroy();
//                     }

//                     // Initialize DataTable
//                     var table = tableElement.DataTable({
//                         "columnDefs": [
//                             { "orderable": false, "targets": columns }
//                         ]
//                     });


//                     $('#customSearchBox').on('keyup', function() {
//                         table.search(this.value).draw();
//                     });
//                 }
//             }

//             // Initialize DataTable for the table with ID #example
//             initializeDataTable('#example');
//         });

// const Container=document.querySelector('.container');
// // Preview Popup
// document.querySelectorAll('.view_btn').forEach(button => {
//   button.addEventListener('click', (event) => {
//     const id = button.getAttribute('data-id');
//     console.log(`Button with data-id: ${id} clicked`);

//     const modal = document.getElementById(`modal_${id}`);
//     const previewPopup = document.getElementById(`preview_popup_${id}`);
    
//     console.log(`Modal:`, modal);
//     console.log(`Preview Popup:`, previewPopup);

//     if(modal && previewPopup) {
     
//       previewPopup.classList.add("open_popup");
//       Container.classList.add("active");
//       console.log(id);
//     } else {
//       console.error(`Modal or Preview Popup not found for id: ${id}`);
//     }
//     document.body.addEventListener('click', event => {
//       if (event.target.classList.contains('close')) {
//         const button = event.target;
//         const id = button.id.split('_').pop();
//         console.log(`Close button with id: ${id} clicked`);
  
//         const previewPopup = document.getElementById(`preview_popup_${id}`);
//         if (previewPopup) {
//           previewPopup.classList.remove('open_popup');
//           Container.classList.remove('active');
//         } else {
//           console.error(`Preview Popup not found for id: ${id}`);
//         }
//       }
//     });
//   });
// });



$(document).ready(function() {
    function initializeDataTable(tableId) {
        var tableElement = $(tableId);
        if (tableElement.length) {
            var disableSortingColumns = tableElement.data('disablesortingcolumns');
            var columns = disableSortingColumns.split(',').map(Number);

            
            if ($.fn.dataTable.isDataTable(tableId)) {
                tableElement.DataTable().destroy();
            }

            var table = tableElement.DataTable({
                "scrollY": "500px",
                "scrollX": "500px",
                "scrollCollapse": true,
                "paging": true,      
                "pageLength": 10,
                "lengthChange": false,
                "info": true,               
                "columnDefs": [
                    { "orderable": false, "targets": columns } 
                ],
                "language": {
                    "paginate": {
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });

            $('#customSearchBox').on('keyup', function() {
                table.search(this.value).draw();
            });
        }
    }

    initializeDataTable('#example');

    const container = document.querySelector('.container');

    document.body.addEventListener('click', function(event) {
        if (event.target.classList.contains('view_btn')) {
            const button = event.target;
            const id = button.getAttribute('data-id');
            console.log(`Button with data-id: ${id} clicked`);

            const modal = document.getElementById(`modal_${id}`);
            const previewPopup = document.getElementById(`preview_popup_${id}`);

            if (modal && previewPopup) {
                previewPopup.classList.add("open_popup");
                container.classList.add("active");
            } else {
                console.error(`Modal or Preview Popup not found for id: ${id}`);
            }
        }

        if (event.target.classList.contains('close')) {
            const button = event.target;
            const id = button.id.split('_').pop();

            const previewPopup = document.getElementById(`preview_popup_${id}`);
            if (previewPopup) {
                previewPopup.classList.remove('open_popup');
                container.classList.remove('active');
            } else {
                console.error(`Preview Popup not found for id: ${id}`);
            }
        }
    });
});
</script>

<?php
        if(isset($_SESSION['success'])): 
        ?>
        <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
        <script>
            iziToast.success({
                title: 'Success',
                message: '<?php echo $_SESSION['success']; ?>',
                position: 'bottomCenter',
                timeout: 5000
            });
        </script>
        <?php
        unset($_SESSION['success']);
        endif;
    ?>




