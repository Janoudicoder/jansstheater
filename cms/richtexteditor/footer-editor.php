<script>
    const editorConfigFooter = {
        toolbar: "siteworkfooter",
        skin: "rounded-corner",
        editorResizeMode: "none",
        url_base: "<? echo $url; ?>/cms/richtexteditor",
        insertimage: {
            byUrl: true,   // Allow inserting image by URL
            upload: false, // Disable uploading images
            gallery: false // Disable image gallery
        }
    };

    // Kolom 1
    var editorFooter1 = new RichTextEditor("#footer-kol-editor-1", editorConfigFooter);

	editorFooter1.attachEvent("exec_command_ctabutton", function (state, cmd, value) {
		state.returnValue = true;//set it has been handled

		var a = editorFooter1.insertRootParagraph("a");
        a.classList.add("btn");
		a.innerHTML = "Voeg uw link toe";
	});
    editorFooter1.attachEvent("exec_command_bold", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter1, 'font-weight: bold;', 'strong');
        }, 100); 	
    });
    editorFooter1.attachEvent("exec_command_italic", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter1, 'font-style: italic;', 'i');
        }, 100); 	
    });
    editorFooter1.attachEvent("exec_command_underline", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter1, 'text-decoration-line: underline;', 'u');
        }, 100); 	
    });
    editorFooter1.attachEvent("exec_command_strike", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter1, 'text-decoration: line-through;', 's');
        }, 100); 	
    });


    // Kolom 2
    var editorFooter2 = new RichTextEditor("#footer-kol-editor-2", editorConfigFooter);

	editorFooter2.attachEvent("exec_command_ctabutton", function (state, cmd, value) {
		state.returnValue = true;//set it has been handled

		var a = editorFooter2.insertRootParagraph("a");
        a.classList.add("btn");
		a.innerHTML = "Voeg uw link toe";
	});
    editorFooter2.attachEvent("exec_command_bold", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter2, 'font-weight: bold;', 'strong');
        }, 100); 	
    });
    editorFooter2.attachEvent("exec_command_italic", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter2, 'font-style: italic;', 'i');
        }, 100); 	
    });
    editorFooter2.attachEvent("exec_command_underline", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter2, 'text-decoration-line: underline;', 'u');
        }, 100); 	
    });
    editorFooter2.attachEvent("exec_command_strike", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter2, 'text-decoration: line-through;', 's');
        }, 100); 	
    });


    // Kolom 3
    var editorFooter3 = new RichTextEditor("#footer-kol-editor-3", editorConfigFooter);

	editorFooter3.attachEvent("exec_command_ctabutton", function (state, cmd, value) {
		state.returnValue = true;//set it has been handled

		var a = editorFooter3.insertRootParagraph("a");
        a.classList.add("btn");
		a.innerHTML = "Voeg uw link toe";
	});
    editorFooter3.attachEvent("exec_command_bold", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter3, 'font-weight: bold;', 'strong');
        }, 100); 	
    });
    editorFooter3.attachEvent("exec_command_italic", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter3, 'font-style: italic;', 'i');
        }, 100); 	
    });
    editorFooter3.attachEvent("exec_command_underline", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter3, 'text-decoration-line: underline;', 'u');
        }, 100); 	
    });
    editorFooter3.attachEvent("exec_command_strike", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter3, 'text-decoration: line-through;', 's');
        }, 100); 	
    });


    // Kolom 4
    var editorFooter4 = new RichTextEditor("#footer-kol-editor-4", editorConfigFooter);

	editorFooter4.attachEvent("exec_command_ctabutton", function (state, cmd, value) {
		state.returnValue = true;//set it has been handled

		var a = editorFooter4.insertRootParagraph("a");
        a.classList.add("btn");
		a.innerHTML = "Voeg uw link toe";
	});
    editorFooter4.attachEvent("exec_command_bold", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter4, 'font-weight: bold;', 'strong');
        }, 100); 	
    });
    editorFooter4.attachEvent("exec_command_italic", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter4, 'font-style: italic;', 'i');
        }, 100); 	
    });
    editorFooter4.attachEvent("exec_command_underline", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter4, 'text-decoration-line: underline;', 'u');
        }, 100); 	
    });
    editorFooter4.attachEvent("exec_command_strike", function (state, cmd, value) {
        setTimeout(() => {
            rte_replaceSpan(editorFooter4, 'text-decoration: line-through;', 's');
        }, 100); 	
    });
</script>