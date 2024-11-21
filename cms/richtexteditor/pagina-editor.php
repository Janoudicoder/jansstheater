<?php 
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
    var editorPage = new RichTextEditor("#page_tekst_editor", editorConfig);

	editorPage.attachEvent("exec_command_ctabutton", function (state, cmd, value) {
		state.returnValue = true;//set it has been handled

		var a = editorPage.insertRootParagraph("a");
        a.classList.add("btn");
		a.innerHTML = "Voeg uw link toe";

        // try {
        //     // Create the dropdown panel
        //     var option = {};

        //     // Define the fillpanel function to populate the dropdown panel
        //     option.fillpanel = function (panel) {
        //         panel.style.padding = '8px';

        //         // Create input fields for URL, Text, Title, and Target
        //         panel.innerHTML = `
        //             <label for="link-url">URL:</label>
        //             <input type="text" id="link-url" class="rte-input" placeholder="https://">
        //             <br/>
        //             <label for="link-text">Text:</label>
        //             <input type="text" id="link-text" class="rte-input" placeholder="Click Me">
        //             <br/>
        //             <label for="link-title">Title:</label>
        //             <input type="text" id="link-title" class="rte-input" placeholder="Button Title">
        //             <br/>
        //             <label for="link-target">Target:</label>
        //             <select id="link-target" class="rte-input">
        //                 <option value="_self">_self</option>
        //                 <option value="_blank">_blank</option>
        //                 <option value="_parent">_parent</option>
        //                 <option value="_top">_top</option>
        //             </select>
        //             <br/>
        //             <button id="insert-link" class="btn btn-primary" style="margin-top: 8px;">Insert Link</button>
        //         `;

        //         // Add functionality to the insert button
        //         var insertButton = panel.querySelector('#insert-link');
        //         insertButton.onclick = function () {
        //             var url = panel.querySelector('#link-url').value;
        //             var text = panel.querySelector('#link-text').value;
        //             var title = panel.querySelector('#link-title').value;
        //             var target = panel.querySelector('#link-target').value;

        //             // Insert the link into the editor
        //             var linkHtml = `<a href="${url}" title="${title}" target="${target}">${text}</a>`;
        //             editorPage.insertHTML(linkHtml);

        //             // Close the dropdown panel
        //             editorPage.closeCurrentPopup();
        //             return false;
        //         };
        //     };

        //     // Create and return the dropdown button
        //     var suffix = "custom"; // Define the suffix or pass as needed
        //     var btn = editorPage.createToolbarDropDown(option, cmd, suffix);
        //     return btn;

        // } catch (error) {
        //     console.error("An error occurred:", error);
        // }

	});
    editorPage.attachEvent("exec_command_bold", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorPage, 'font-weight: bold;', 'strong');
        }, 100);
    });
    editorPage.attachEvent("exec_command_italic", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorPage, 'font-style: italic;', 'i');
        }, 100);	
    });
    editorPage.attachEvent("exec_command_underline", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorPage, 'text-decoration-line: underline;', 'u');
        }, 100); 	
    });
    editorPage.attachEvent("exec_command_strike", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorPage, 'text-decoration: line-through;', 's');
        }, 100); 	
    });
</script>