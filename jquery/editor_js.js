// Load Tools
/*
You can upload Tools to your project's directory and connect them by relative links.

Also you can load each Tool from CDN or use NPM/Yarn packages.

/*
 * Custom plugins
 */

class textImage_block {
    static get toolbox() {
      return {
        title: 'Tekst - Image',
        icon: '<svg width="17" height="15" viewBox="0 0 336 276" xmlns="http://www.w3.org/2000/svg"><path d="M291 150V79c0-19-15-34-34-34H79c-19 0-34 15-34 34v42l67-44 81 72 56-29 42 30zm0 52l-43-30-56 30-81-67-66 39v23c0 19 15 34 34 34h178c17 0 31-13 34-29zM79 0h178c44 0 79 35 79 79v118c0 44-35 79-79 79H79c-44 0-79-35-79-79V79C0 35 35 0 79 0z"/></svg>'
      };
    }
    
    constructor({data, api}){
        this.data = data || {};
        this.api = api;
        this.simpleImage = null; // Initialize simpleImage instance
    }
  
    render(){
        const wrapper = document.createElement('div');
        const left = document.createElement('div');
    
        wrapper.classList.add('text-image', 'grid', 'grid-cols-2');
        left.classList.add('ce-paragraph', 'cdx-block');
        left.setAttribute('contenteditable', 'true');
        wrapper.appendChild(left);

        // Create input field for URL
        const urlInput = document.createElement('input');
        urlInput.type = 'text';
        urlInput.value = this.data.url || ''; // Set initial value from data
        urlInput.addEventListener('input', () => {
            this.data.url = urlInput.value; // Update data as input changes
            // Update SimpleImage with the new data

            this.simpleImage = new SimpleImage({
                data: this.data, // Pass data object to SimpleImage
                config: {}, // Replace {} with your config object
                api: this.api, // Replace {} with your api object
                readOnly: false // Replace false with your readOnly value
            });
            this.simpleImage.render();
        });
        wrapper.appendChild(urlInput);

        // const SimpleImage = EditorJS.tools.image.class;
        this.simpleImage = new SimpleImage({
            data: this.data, // Pass data object to SimpleImage
            config: {}, // Replace {} with your config object
            api: this.api, // Replace {} with your api object
            readOnly: false // Replace false with your readOnly value
        });
        wrapper.appendChild(this.simpleImage.render());

        return wrapper;
    }
  
    save(blockContent){
      return {
        url: blockContent.value
      }
    }

    // Method to update the image value
    updateImageValue(newValue) {
        if (this.simpleImage) {
            // Assuming SimpleImage has a method setValue to update its value
            this.simpleImage.setValue(newValue);
        }
    }
  
    static get contentless() {
      return true;
    }
}




/*
Read more at Tools Connection doc:
https://editorjs.io/getting-started#tools-connection
*/

// Initialization
/**
 * To initialize the Editor, create a new instance with configuration object
 * @see docs/installation.md for mode details
 */

function newEditor(holder, data, saveButton) {
    var editor = new EditorJS({
        /**
         * Enable/Disable the read only mode
         */
        readOnly: false,

        /**
         * Wrapper of Editor
         */
        holder: holder,

        /**
         * Common Inline Toolbar settings
         * - if true (or not specified), the order from 'tool' property will be used
         * - if an array of tool names, this order will be used
         */
        // inlineToolbar: ['link', 'marker', 'bold', 'italic'],
        // inlineToolbar: true,

        /**
         * Tools list
         */
        tools: {
            /**
             * Each Tool is a Plugin. Pass them via 'class' option with necessary settings {@link docs/tools.md}
             */
            header: {
                class: Header,
                inlineToolbar: ['marker', 'link'],
                config: {
                    placeholder: 'Header'
                },
                shortcut: 'CMD+SHIFT+H'
            },

            /**
             * Or pass class directly without any configuration
             */
            image: {
                class: ImageTool,
                // class: Image, 
                inlineToolbar: true,
                config: {
                    endpoints: {
                      byFile: '/php/editorjs_post.php', // Your backend file uploader endpoint
                      byUrl: '/php/editorjs_post.php', // Your endpoint that provides uploading by Url
                    }
                },
            },

            list: {
                class: List,
                inlineToolbar: true,
                shortcut: 'CMD+SHIFT+L'
            },

            checklist: {
                class: Checklist,
                inlineToolbar: true,
            },

            quote: {
                class: Quote,
                inlineToolbar: true,
                config: {
                    quotePlaceholder: 'Enter a quote',
                    captionPlaceholder: 'Quote\'s author',
                },
                shortcut: 'CMD+SHIFT+O'
            },

            warning: Warning,

            marker: {
                class:  Marker,
                shortcut: 'CMD+SHIFT+M'
            },

            code: {
                class:  CodeTool,
                shortcut: 'CMD+SHIFT+C'
            },

            delimiter: Delimiter,

            inlineCode: {
                class: InlineCode,
                shortcut: 'CMD+SHIFT+C'
            },

            linkTool: LinkTool,

            embed: Embed,

            table: {
                class: Table,
                inlineToolbar: true,
                shortcut: 'CMD+ALT+T'
            },

            block: {
                class: textImage_block,
                inlineToolbar: true,
                inlineToolbar: ['bold', 'italic', 'marker', 'link'],
            },

        },

        /**
         * Initial Editor data
         */
        data: data, 
        // onReady: function(){
        //     saveButton.click();
        // },
        // onChange: function(api, event) {
        //     console.log('something changed', event);
        // }
    });

    $('#' + saveButton).on('click', function () {
        editor.save()
        .then((savedData) => {
            console.log(savedData);
        })
        .catch((error) => {
            console.error('Saving error', error);
        });
    });
}