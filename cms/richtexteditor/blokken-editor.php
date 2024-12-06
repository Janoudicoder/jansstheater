<?php 
    include ("../login/config.php");
    include ('../login/functions.php'); 

    $editorGallery = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE media = 'afbeelding' ORDER BY datum_geupload DESC") or die($mysqli->error.__LINE__);
    $editorGalleryImgs = []; // Initialize an empty array

    while ($row = $editorGallery->fetch_assoc()) {
        $editorGalleryImgs[] = $url . '/img/' . $row['naam'] . '.' . $row['ext']; // Append each row to the array
    }
    $editorGalleryImgsJson = json_encode($editorGalleryImgs);
?>

<script>
    const editorConfig = {
        toolbar: "sitework",
        skin: "rounded-corner",
        editorResizeMode: "none",
        url_base: "<? echo $url; ?>/cms/richtexteditor",
        insertimage: {
            byUrl: true,   // Allow inserting image by URL
            upload: false, // Disable uploading images
            gallery: false // Disable image gallery
        },
        imageItems: <?=$editorGalleryImgsJson;?>,
        galleryImages: <?=$editorGalleryImgsJson;?>
    };
    var editor1 = new RichTextEditor("#tekst_editor", editorConfig);

	editor1.attachEvent("exec_command_ctabutton", function (state, cmd, value) {
		state.returnValue = true;//set it has been handled

		var a = editor1.insertRootParagraph("a");
        a.classList.add("btn");
		a.innerHTML = "Voeg uw link toe";
	});
    editor1.attachEvent("exec_command_bold", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editor1, 'font-weight: bold;', 'strong');
        }, 100); 	
    });
    editor1.attachEvent("exec_command_italic", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editor1, 'font-style: italic;', 'i');
        }, 100); 	
    });
    editor1.attachEvent("exec_command_underline", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editor1, 'text-decoration-line: underline;', 'u');
        }, 100); 	
    });
    
    editor1.attachEvent("exec_command_strike", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editor1, 'text-decoration: line-through;', 's');
        }, 100); 	
    });
    
</script>