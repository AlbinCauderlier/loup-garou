/**
 *
 * Animation of the scrolls in the same page
 */
$(function () {
  $('a[href*=\\#]:not([href=\\#])').click(function () {
    // DurÃ©e en milli-seconde du scroll
    var scroll_duration = 700;

    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {

      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        // Nouvelle position ciblÃ©e = [Position de la cible] - [Hauteur du header] - [Une marge d'esthÃ©tique (optionnal because scrollspy bugs)]
        var vertical_delta = target.offset().top - $('header').height() -30;
        $('html,body').animate({scrollTop: vertical_delta}, scroll_duration);
        return false;
      }
    }
  });
});


$(document).ready(function () {
	// Not display "Back to top" button before the scroll
	$(".bottom-link").css({"display":"none"});

	update_datatables();

	$('.light-dynamic-datatable').DataTable({
		pageLength: -1, //50,
		lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],

		language: {
			search:         "<i data-feather='search' class='mr-1'></i>",
			lengthMenu:    "<i data-feather='align-justify'></i> _MENU_ ",
			infoEmpty:      "",
			infoPostFix:    "",
		},
		bSort: false,
		dom: 'Blfrt',
		buttons: [
		{
			extend: 'copy',
			text: '<i data-feather="copy"></i> Copy',
			className: 'btn-gradient rounded-pill mx-2 ml-2'
		},
		{
			extend: 'print',
			text: '<i data-feather="printer"></i> Print',
			className: 'btn-gradient rounded-pill mr-2'
		},
		{
			extend: 'csv',
			text: '<i data-feather="download"></i> CSV',
			className: 'btn-gradient rounded-pill mr-2'
		},
		{
			extend: 'pdf',
			text: '<i data-feather="download"></i> PDF',
			className: 'btn-gradient rounded-pill mr-2',
			orientation: 'landscape'
		},
		{
			extend: 'excelHtml5',
			text: '<i data-feather="download"></i> Excel',
			className: 'btn-gradient rounded-pill mr-2',
			exportOptions: {
					modifier: {
					page: 'current'
				}
			}
		}
		]
	});

	$('.dataTables_length select').addClass("form-control");
	$('.dataTables_filter input').addClass("form-control rounded-pill mb-2");

	//Display the icones
	feather.replace();

	//Display the numbers and amounts
    amountSeperator();
    // Display in stats page
	numberSeperator();

	//Display the tooltips
	$('[data-toggle="tooltip"]').tooltip();
});



var bottom_link_offset = 200;
var fixed_menu_header_offset = 30;
var card_content_offset = 150;

$(window).scroll(function(){
	if(bottom_link_offset > $(this).scrollTop()){
		$(".bottom-link").css({"display":"none"});
	}
	else{
		$(".bottom-link").removeAttr('style');
	}

	// Fixed and scrollspy menus WITHOUT a streamer
	if( $(this).scrollTop() > card_content_offset+fixed_menu_header_offset ){
		$("aside nav#toc").css({"position":"fixed","top": $("header").height()+fixed_menu_header_offset});
	}
	else{
		$("aside nav#toc").removeAttr('style');
	}

	// Fixed and scrollspy menus WITH a streamer
	if( $(this).scrollTop() > $("section#streamer").height()+card_content_offset+fixed_menu_header_offset ){
		$("aside nav#streamer-toc").css({"position":"fixed","top": $("header").height()+fixed_menu_header_offset});
	}
	else{
		$("aside nav#streamer-toc").removeAttr('style');
	}
});



$('input[type="file"]').on('change',function(){
    //replace the label by the value
    $(this).next('.custom-file-label').html( $(this).val() );
})


$('input[type="file"].picture').on('change',function(){
    //replace the label by the value
    $(this).next('.custom-file-label').html( $(this).val() );

    readURL( this );
})

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#profile-picture').attr('src', e.target.result).fadeIn('slow');
        }
        reader.readAsDataURL(input.files[0]);
    }
}




const amountSeperator = () => {
    amounts = document.querySelectorAll(".amount");
    amounts.forEach((amount)=>{
        if(amount.innerHTML === ''){
            return;
        }

        var newValue = Number(amount.innerHTML);
        if (String(newValue).split(".").length < 2 || String(newValue).split(".")[1].length<=2){
            newValue = newValue.toFixed(2);
            amount.innerHTML = newValue;
        }

        amount.innerHTML = amount.innerHTML.replace(/\B(?=(\d{3})+(?!\d))/g, "'");
    });
};


const numberSeperator = () => {
    numbers = document.querySelectorAll(".number");
    numbers.forEach((number) => {
        number.innerHTML = number.innerHTML.replace(/\B(?=(\d{3})+(?!\d))/g, "'");
    });
};



function update_datatables( class_name = '.dynamic-datatable', responsive = true, order_column = 0, order_way = "asc" ){
	$( class_name ).DataTable({
		responsive: responsive,

		pageLength: -1, //50,
		lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],

		language: {
			//processing:     "Traitement en cours...",
			search:         "<i data-feather='search' class='mr-1'></i>",
			lengthMenu:    "<i data-feather='align-justify'></i> _MENU_ ",
			info:           "_START_ <> _END_ / _TOTAL_ ",
			infoEmpty:      "",
			//infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
			infoPostFix:    "",
			//loadingRecords: "Chargement en cours...",
			//zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
			//emptyTable:     "Aucune donnée disponible dans le tableau",
			paginate: {
				first:      "<i data-feather='chevrons-left'></i>",
				previous:   "<i data-feather='chevron-left'></i>",
				next:       "<i data-feather='chevron-right'></i>",
				last:       "<i data-feather='chevrons-right'></i>"
			},
			aria: {
				sortAscending:  ": activer pour trier la colonne par ordre croissant",
				sortDescending: ": activer pour trier la colonne par ordre décroissant"
			}
		},
		dom: 'lfrtip',
		"order": [[ order_column, order_way]]
	});
}





