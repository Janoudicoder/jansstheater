$(document).ready(function () {

    // menu toggle
    // ===========
    $(".c-hamburger").click(function() {
        $('#hoofdmenu').toggleClass("menuopen");
    });

    $("#web-drop").click(function() {
        $('.cat-dropdown').toggleClass("menuopen");
        $(this).toggleClass( "active" );
    });

    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (window.scrollY > 150) {
            $("#scroll-to-cms-top").addClass('active');
        } else {
            $("#scroll-to-cms-top").removeClass('active');
        }
    }

    $("#scroll-to-cms-top").click(function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });

    $("#scroll-to-media-top").click(function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });

    (function() {

        "use strict";

        var toggles = document.querySelectorAll(".c-hamburger");

        for (var i = toggles.length - 1; i >= 0; i--) {
          var toggle = toggles[i];
          toggleHandler(toggle);
        };

        function toggleHandler(toggle) {
          toggle.addEventListener( "click", function(e) { 
            e.preventDefault();
            (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");
          });
        }

      })();

    // drag drop script voor veranderen menustructuur
    // ==============================================
	$(function() {
        $("#structuurCat").sortable({
            opacity: 0.6,
            cursor: 'move',
            update: function() {
                var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
                $.post("dragdrop/updateDB_categorie.php", order, function(theResponse) {
                    $("#melding-menu").html(theResponse);
                });
            }
        });
    });

    // $(function() {
    //     $("#menu").sortable({
    //         opacity: 0.6,
    //         cursor: 'move',
    //         update: function() {
    //             var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
    //             $.post("dragdrop/updateDB_websitemenu.php", order, function(theResponse) {
    //                 $("#melding-menu").html(theResponse);
    //             });
    //         }
    //     });
    //     $("#submenu").sortable({
    //         opacity: 0.6,
    //         cursor: 'move',
    //         update: function() {
    //             var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
    //             $.post("dragdrop/updateDB_websitemenusub.php", order, function(theResponse) {
    //                 $("#melding-menu").html(theResponse);
    //             });
    //         }
    //     });
    // });

    $(function() {
        $("#menu").nestedSortable({
            opacity: 0.6,
            cursor: 'move',
            handle: '.hoofditemlabel', // Adjust handle selector if needed
            forcePlaceholderSize: true,
            items: 'li',
            placeholder: 'menu-highlight',
            listType: 'ul',
            maxLevels: 2,
        //   toleranceElement: '> div', // Adjust tolerance if needed
          isTree: true,
          update: function(event, ui) {
            var itemID = ui.item.attr('id');
            var parentID = ui.item[0].offsetParent.id;

            if(parentID === "" || parentID === null) {
                $('#' + itemID).attr('data-menu', 'menu');
                $('#' + itemID + " .menu-item .hoofditemlabel").html('Menu item');
                $('#' + itemID).attr('id', 'recordsArray_'+itemID.split("_")[1]);
            } else {
                $('#' + itemID).attr('data-menu', 'submenu');
                $('#' + itemID + " .menu-item .hoofditemlabel").html('Submenu item');
                $('#' + itemID).attr('id', 'recordsArraySub_'+itemID.split("_")[1]);
            }
            
            if (parentID === "" || parentID === null) {
                var serializedData = $(this).nestedSortable("serialize") + '&action=updateRecordsListings';
                $.post("dragdrop/updateDB_websitemenu.php", serializedData, function(theResponse) {
                    $("#melding-menu").html(theResponse);
                });
            } else { // Submenu item moved
                var item_id = itemID.split("_")[1];
                var parent_id = parentID.split("_")[1]; // Extract parent ID
                var subMenuOrder = $(this).sortable("serialize") + '&action=updateRecordsListingsSub&item_id='+item_id+'&parent_id=' + parent_id;
                $.post("dragdrop/updateDB_websitemenusub.php", subMenuOrder, function(theResponse) {
                    $("#melding-menu").html(theResponse);
                });
            }
          }
        });
    });      

    // drag drop script voor veranderen formuliervelden
    // ==============================================
	$(function() {
		$(".volgorde ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListingsForm';
			$.post("dragdrop/updateDB_formulierveld.php", order, function(theResponse){
				$("#contentRight").html(theResponse);
			});
        }
        });
        $(".volgorde-option ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListingsFormOption';
			$.post("dragdrop/updateDB_formulierveldoption.php", order, function(theResponse){
				$("#contentRight").html(theResponse);
            });
        }
		});

    });

    // toggle box toevoegen categorie/kenmerk
    // ======================================
    $(function(){
        $('.toggle-box').hide();
        $('.clickme').each(function() {
            $(this).show(0).on('click', function(e) {
                e.preventDefault();
                $('.toggle-box').slideToggle('fast');
            });
        });
    });

    $(function(){
        $('.toggle-custom').hide();
        $('.open-custom').each(function() {
            $(this).show(0).on('click', function(e) {
                e.preventDefault();
                $('.toggle-custom').slideToggle('fast');
            });
        });
    });

    // datepicker
    // ==========
    $(function() {
        $( "#datepicker" ).datepicker({dateFormat: 'dd-mm-yy',});
        $( "#datepicker2" ).datepicker({dateFormat: 'dd-mm-yy',});
        $( "#datecustom-1" ).datepicker({dateFormat: 'dd-mm-yy',});
        $( "#datecustom-2" ).datepicker({dateFormat: 'dd-mm-yy',});
    });


    // realworks opties instellingen show/hide
    // =======================================
    $('#makelaar_nee').change(function () {
        $('.rw').hide();
    });
    $('#makelaar_ja').change(function () {
        $('.rw').show();
    });

    // afbeeldingsopties instellingen show/hide
    // =======================================
    $('#afbeeldingopties_nee').change(function () {
        $('.afb').hide();
    });
    $('#afbeeldingopties_ja').change(function () {
        $('.afb').show();
    });

    // sticky opslaan box
    $(".stickysave").stick_in_parent({
        offset_top: 25,
    });

    $(".sticky_previews").stick_in_parent({
        offset_top: 145,
    });

    $(".sticky_files").stick_in_parent({
        offset_top: 75,
    });

    // gebruiker uitklap menu
    // ======================
	$('.gebruiker_menu').click(function(event){
        event.stopPropagation();
         $(".gebruiker_opties").show();
    });

    $(".gebruiker_opties").on("click", function (event) {
        event.stopPropagation();
    });

	$(document).on("click", function () {
    	$(".gebruiker_opties").hide();
    });
    
    $('.nieuw_menu').click(function(event){
        event.stopPropagation();
         $(".nieuw_opties").show();
    });

    $(".nieuw_opties").on("click", function (event) {
        event.stopPropagation();
    });

	$(document).on("click", function () {
    	$(".nieuw_opties").hide();
	});

    $('.cookie-voorbeeld').on("click", function () {
        $('#termsfeed-com---nb').toggleClass('cookie-voorbeeld-active');
    })

    // alert
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 4000);

    $(".alert").on("click", function () {
        $( this ).fadeTo( "slow", 0 );
    });

    // fancybox responsive
    $(".responsive-fancy").fancybox({
        margin: [20,20,20,20],
        padding: [20,20,0,20],
        openEffect: 'fade',
        openEasing: 'easeInQuad',
        openSpeed: 400,
        title: false,
        scrolling : 'no',
        fitToView : false,
        autoSize: false,
        height: 'auto',
        width: '100%'
    });

    $(".responsive-fancy.desktop").fancybox({
        baseClass: 'desktop',
    });

    $(".responsive-fancy.tablet").fancybox({
        baseClass: 'tablet',
    });

    $(".responsive-fancy.mobiel").fancybox({
        baseClass: 'mobiel',
    });
    

});

function save_and_strip(id, editor) {
    var editorHTML = editor.getHTMLCode();
    var removeArr = ['xml', 'script', 'link', 'meta', 'style'];
    var removeEl = ['span', 'div', 'header', 'main', 'section', 'footer'];

    // Verwijder elementen in removeArr volledig, met alle inhoud
    removeArr.forEach(tag => {
        const regex = new RegExp(`<${tag}[^>]*>[\\s\\S]*?<\\/${tag}>`, 'gi');
        const regexSingle = new RegExp(`<${tag}[^>]*/>`, 'gi');

        editorHTML = editorHTML.replaceAll(regex, '');
        editorHTML = editorHTML.replaceAll(regexSingle, '');
    });

    // Verwijder alleen de tags in removeEl, maar dit behoud de inhoud
    removeEl.forEach(tag => {
        const openTagRegex = new RegExp(`<${tag}[^>]*>`, 'gi');
        const closeTagRegex = new RegExp(`</${tag}>`, 'gi');
        editorHTML = editorHTML.replaceAll(openTagRegex, '').replaceAll(closeTagRegex, '');
    });

    // Verwijder alle attributen van de overige elementen
    editorHTML = editorHTML.replaceAll(/\s+\w+="[^"]*"/gi, '');

    document.getElementById(id).value = editorHTML;
}

function rte_replaceSpan(editor, style, replace) {
    let content = editor.getHTML();
    let spanStyle = `style="${style}"`;
    let element = replace;
    
    const regex = new RegExp(`<span ${spanStyle}>(.*?)<\/span>`, 'g');
    content = content.replace(regex, `<${element}>$1</${element}>`);

    editor.setHTML(content);
}